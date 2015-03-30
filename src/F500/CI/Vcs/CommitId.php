<?php
/**
 * Created by PhpStorm.
 * User: mvriel
 * Date: 30/03/15
 * Time: 14:04
 */

namespace F500\CI\Vcs;


interface CommitId
{
    public function __construct($value);

    public function __toString();
}
