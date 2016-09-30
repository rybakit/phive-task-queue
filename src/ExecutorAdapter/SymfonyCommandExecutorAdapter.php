<?php

namespace Phive\TaskQueue\ExecutorAdapter;

use Phive\TaskQueue\ExecutionContext;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\StreamOutput;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class SymfonyCommandExecutorAdapter implements ExecutorAdapter
{
    /**
     * @var Application
     */
    private $application;

    /**
     * @var PropertyAccessorInterface
     */
    private $accessor;

    public function __construct(Application $application)
    {
        $this->application = $application;
        $this->accessor = PropertyAccess::createPropertyAccessorBuilder()
            ->enableExceptionOnInvalidIndex()
            ->getPropertyAccessor();
    }

    public function execute($payload, ExecutionContext $context)
    {
        $command = $this->createCommand($payload);
        $arguments = array_merge(
            ['command' => $command->getName()],
            $this->getArguments($payload)
        );

        $input = new ArrayInput($arguments);
        $output = new StreamOutput(STDOUT);

        $command->run($input, $output);
    }

    private function createCommand($payload)
    {
        if ($payload instanceof Command) {
            return $payload;
        }

        if ($this->accessor->isReadable($payload, '[command]')) {
            return $this->application->find(
                $this->accessor->getValue($payload, '[command]')
            );
        }

        if ($this->accessor->isReadable($payload, 'command')) {
            return $this->application->find(
                $this->accessor->getValue($payload, 'command')
            );
        }

        throw new \InvalidArgumentException('Unsupported payload type.');
    }

    private function getArguments($payload)
    {
        if ($this->accessor->isReadable($payload, '[args]')) {
            return $this->accessor->getValue($payload, '[args]');
        }

        if ($this->accessor->isReadable($payload, 'args')) {
            return $this->accessor->getValue($payload, 'args');
        }

        return [];
    }
}
