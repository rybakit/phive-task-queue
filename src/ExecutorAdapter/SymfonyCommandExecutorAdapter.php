<?php

namespace Phive\TaskQueue\ExecutorAdapter;

use Phive\TaskQueue\ExecutionContext;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\StreamOutput;
use Symfony\Component\PropertyAccess\PropertyAccess;

class SymfonyCommandExecutorAdapter implements ExecutorAdapter
{
    public function execute($payload, ExecutionContext $context)
    {
        $accessor = PropertyAccess::createPropertyAccessor();

        if (!$command = $accessor->getValue($payload, 'command')) {
            throw new \RuntimeException('Not supported.');
        }

        $app = new Application();
        $command = $app->find($command);

        $args = $accessor->isReadable($payload, 'args') ? $accessor->getValue($payload, 'args') : [];
        $arguments = array_merge(['command' => $command], $args);

        $input = new ArrayInput($arguments);
        $output = new StreamOutput(STDOUT);

        $command->run($input, $output);
    }
}
