<?php
/**
 * Created by PhpStorm.
 * User: mvriel
 * Date: 30/03/15
 * Time: 14:05
 */

namespace F500\CI\Vcs;


class CommitHash implements CommitId
{
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function __toString()
    {
        return $this->value;
    }

}
