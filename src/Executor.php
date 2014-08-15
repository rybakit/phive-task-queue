<?php

namespace Phive\TaskQueue;

use Phive\Queue\NoItemAvailableException;
use Phive\TaskQueue\ExecutorAdapter\ExecutorAdapter;

class Executor
{
    /**
     * @var ExecutorAdapter
     */
    private $adapter;

    /**
     * @var ExecutionContext
     */
    protected $context;

    public function __construct(ExecutorAdapter $adapter, ExecutionContext $context)
    {
        $this->adapter = $adapter;
        $this->context = $context;
    }

    /**
     * @return bool True if a task was processed, false otherwise.
     */
    public function execute()
    {
        $logger = $this->context->getLogger();

        try {
            $task = $this->context->getQueue()->pop();
        } catch (NoItemAvailableException $e) {
            $logger->debug($e->getMessage(), ['exception' => $e]);

            return false;
        }

        $logger->info('Start executing.', ['task' => $task]);

        try {
            $this->adapter->execute($task, $this->context);
            $logger->info('Task was successfully executed.', ['task' => $task]);
        } catch (\Exception $e) {
            $logger->error('An error occurred while executing task.', ['task' => $task, 'exception' => $e]);
        }

        return true;
    }
}
