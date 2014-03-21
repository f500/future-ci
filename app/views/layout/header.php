<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>Future CI</title>

    <link href="<?= $baseUrl ?>/assets/css/f500ci<?= $minify ? '.min' : '' ?>.css" rel="stylesheet" type="text/css" />

    <!--[if lt IE 9]>
    <link href="<?= $baseUrl ?>/bower_components/html5shiv/dist/html5shiv.js" rel="stylesheet" type="text/css" />
    <link href="<?= $baseUrl ?>/bower_components/respond/dest/respond.min.js" rel="stylesheet" type="text/css" />
    <![endif]-->
</head>

<body>

<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?= $baseUrl ?>/">Future <em>CI</em></a>
        </div>

        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
                <li class="active"><a href="<?= $baseUrl ?>/">Home</a></li>
                <li><a href="<?= $baseUrl ?>/">About</a></li>
                <li><a href="<?= $baseUrl ?>/">Contact</a></li>
            </ul>
        </div>
    </div>
</div>
