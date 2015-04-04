<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace spec\F500\CI\Vcs;

use F500\CI\Vcs\CommitHash;
use F500\CI\Vcs\GitCommit;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class CommitHashSpec
 *
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   spec\F500\CI\Suite
 */
class GitCommitSpec extends ObjectBehavior
{
    const DESCRIPTION = 'description';

    const AUTHOR = 'author';

    private $date;

    private $id;

    function let(CommitHash $id)
    {
        $this->id   = $id;
        $this->date = new \DateTime();

        $this->beConstructedWith($this->id, $this->date, self::AUTHOR, self::DESCRIPTION);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('F500\CI\Vcs\GitCommit');
        $this->shouldImplement('F500\CI\Vcs\Commit');
    }

    function it_has_an_id()
    {
        $this->getId()->shouldReturn($this->id);
    }

    function it_has_a_date()
    {
        $this->getDate()->shouldReturn($this->date);
    }

    function it_has_an_author()
    {
        $this->getAuthor()->shouldReturn(self::AUTHOR);
    }

    function it_has_a_description()
    {
        $this->getDescription()->shouldReturn(self::DESCRIPTION);
    }
}
