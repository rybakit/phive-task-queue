<?php

namespace Phive\TaskQueue\ExecutorAdapter;

use Phive\TaskQueue\ExecutionContext;
use Symfony\Component\Process\Process;
use Symfony\Component\PropertyAccess\PropertyAccess;

class ProcessExecutorAdapter implements ExecutorAdapter
{
    public function execute($payload, ExecutionContext $context)
    {
        $accessor = PropertyAccess::createPropertyAccessor();

        if (!$commandline = $accessor->getValue($payload, 'commandline')) {
            throw new \RuntimeException('Not supported.');
        }

        $process = new Process($commandline);

        $process->run(function ($type, $buffer) use ($context) {
            if (Process::ERR === $type) {
                $context->getLogger()->error($buffer);
            } else {
                $context->getLogger()->debug($buffer);
            }
        });
    }
}
