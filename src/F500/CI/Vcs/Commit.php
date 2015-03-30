<?php
/**
 * Created by PhpStorm.
 * User: mvriel
 * Date: 30/03/15
 * Time: 13:59
 */

namespace F500\CI\Vcs;


interface Commit
{
    /** @return CommitId */
    public function getId();

    /** @return string */
    public function getDescription();

    /** @return \DateTime */
    public function getDate();
}
