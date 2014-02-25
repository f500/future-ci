<?php

namespace F500\CI\Task;

use F500\CI\Run\Toolkit;
use Psr\Log\LogLevel;

class DummyTask extends BaseTask
{

    /**
     * @param Toolkit $toolkit
     * @return bool
     */
    public function run(Toolkit $toolkit)
    {
        $this->startRun($toolkit);
        $toolkit->getLogger()->log(LogLevel::INFO, 'Doing nothing (dummy task).');
        $this->finishRun($toolkit);

        return true;
    }
}
