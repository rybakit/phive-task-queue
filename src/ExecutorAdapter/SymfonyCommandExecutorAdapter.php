<?php

namespace Phive\TaskQueue\ExecutorAdapter;

use Phive\TaskQueue\ExecutionContext;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\StreamOutput;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class SymfonyCommandExecutorAdapter implements ExecutorAdapter
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
        if (!$command = $this->accessor->getValue($payload, 'command')) {
            throw new \RuntimeException('Not supported.');
        }

        $app = new Application();
        $command = $app->find($command);

        $args = $this->accessor->isReadable($payload, 'args') ? $this->accessor->getValue($payload, 'args') : [];
        $arguments = array_merge(['command' => $command], $args);

        $input = new ArrayInput($arguments);
        $output = new StreamOutput(STDOUT);

        $command->run($input, $output);
    }
}
