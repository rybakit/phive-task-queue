<?php

namespace Phive\TaskQueue\ExecutorAdapter;

use Phive\TaskQueue\ExecutionContext;
use Symfony\Component\Process\Process;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class ProcessExecutorAdapter implements ExecutorAdapter
{
    /**
     * @var PropertyAccessorInterface
     */
    private $accessor;

    public function __construct(PropertyAccessorInterface $accessor = null)
    {
        $this->accessor = $accessor ?: PropertyAccess::createPropertyAccessor();
    }

    public function execute($payload, ExecutionContext $context)
    {
        if (!$commandline = $this->accessor->getValue($payload, 'commandline')) {
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
