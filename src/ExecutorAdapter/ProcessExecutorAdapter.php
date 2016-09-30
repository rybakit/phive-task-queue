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

    public function __construct()
    {
        $this->accessor = PropertyAccess::createPropertyAccessorBuilder()
            ->enableExceptionOnInvalidIndex()
            ->getPropertyAccessor();
    }

    public function execute($payload, ExecutionContext $context)
    {
        $process = $this->createProcess($payload);

        $process->run(function ($type, $buffer) use ($context) {
            if (Process::ERR === $type) {
                $context->getLogger()->error($buffer);
            } else {
                $context->getLogger()->debug($buffer);
            }
        });
    }

    private function createProcess($payload)
    {
        if ($payload instanceof Process) {
            return $payload;
        }

        if (is_string($payload)) {
            return new Process($payload);
        }

        if ($this->accessor->isReadable($payload, '[commandline]')) {
            return new Process($this->accessor->getValue($payload, '[commandline]'));
        }

        if ($this->accessor->isReadable($payload, 'commandline')) {
            return new Process($this->accessor->getValue($payload, 'commandline'));
        }

        throw new \InvalidArgumentException('Unsupported payload type.');
    }
}
