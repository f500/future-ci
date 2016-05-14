<?php

/**
 * This file is part of the Future CI package.
 *
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 */

$tube = 'ci-builds';

$repoToConfMap = array(
    'gh-hook-test' => array(
        'suite'  => 'poolz.yml',
        'secret' => 'secret'
    ),
    'poolz-app' => array(
        'suite'  => 'poolz.yml',
        'secret' => 'secret'
    )
);

// retrieve input

$rawPayload = file_get_contents('php://input');
$payload    = json_decode($rawPayload, true);

if ($payload === null) {
    echo '[FAILED] payload invalid';
    exit(1);
}

// find repo (suite)

if (empty($payload['repository']['name'])) {
    echo '[FAILED] no repository found';
    exit(1);
}

if (!isset($repoToConfMap[$payload['repository']['name']])) {
    echo sprintf('[FAILED] %s: unknown repository', $payload['repository']['name']);
    exit(1);
}

$suite = $repoToConfMap[$payload['repository']['name']]['suite'];

// check signature

if (empty($_SERVER['HTTP_X_HUB_SIGNATURE']) || !preg_match('/^(.+)=(.+)$/', $_SERVER['HTTP_X_HUB_SIGNATURE'], $m)) {
    echo sprintf("[FAILED] %s: no secret found", $payload['repository']['name']);
    exit(1);
}

$algo = $m[1];

$githubSignature    = $m[2];
$generatedSignature = hash_hmac($algo, $rawPayload, $repoToConfMap[$payload['repository']['name']]['secret']);

if ($generatedSignature !== $githubSignature) {
    echo sprintf('[FAILED] %s: secret incorrect', $payload['repository']['name']);
    exit(1);
}

// find commit (branch)

if (empty($payload['head_commit']['id'])) {
    echo sprintf('[FAILED] %s: no commit-hash found', $payload['repository']['name']);
    exit(1);
}

$branch     = str_replace('refs/heads','',$payload['ref']);
$compare    = $payload['compare'];
$comment    = $payload['head_commit']['message'];
$author     = $payload['head_commit']['author']['name'];
$repo       = $payload['repository']['name'];

// push job
$fp = fsockopen('127.0.0.1', 11300, $errno, $errstr);

if (!$fp) {
    echo sprintf('[FAILED] %s: cannot connect to beanstalkd: %d %s', $payload['repository']['name'], $errno, $errstr);
    exit(1);
}

$data = json_encode(array(
    'suite'  => $suite,
    'params' => array(
        'branch'  => $branch,
        'compare' => $compare,
        'comment' => $comment,
        'author'  => $author,
        'repo'    => $repo
    )
));

if (function_exists('mb_strlen')) {
    $dataLength = mb_strlen($data, '8bit');
} else {
    $dataLength = strlen($data);
}

fwrite($fp, sprintf("use %s\r\n", $tube));
$useOutput = fread($fp, 512);
fwrite($fp, sprintf("put 1024 0 1200 %d\r\n%s\r\n", $dataLength, $data));
$putOutput = fread($fp, 512);

fclose($fp);

echo sprintf(
    "[OK] %s: job pushed: %s %s\r\n%s\r\n%s",
    $payload['repository']['name'],
    $suite,
    $branch,
    trim($useOutput),
    trim($putOutput)
);
