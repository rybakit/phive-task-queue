<?php

namespace Phive\TaskQueue;

use Phive\Queue\QueueException;
use Phive\Queue\NoItemAvailableException;

abstract class AbstractExecutor
{
    protected $context;

    public function __construct(ExecutionContextInterface $context)
    {
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
        } catch (QueueException $e) {
            $e instanceof NoItemAvailableException
                ? $logger->debug($e->getMessage())
                : $logger->error($e->getMessage());

            return false;
        }

        if (!$task instanceof TaskInterface) {
            $task = new Task($task);
        }

        $logger->debug(sprintf('Dequeued "%s".', $task));

        try {
            $this->doExecute($task);
            $logger->info(sprintf('Task "%s" was successfully executed.', $task));
        } catch (TaskFailedException $e) {
            $logger->error(sprintf('Task "%s" failed: %s', $task, $e->getMessage()));
        } catch (\Exception $e) {
            try {
                $eta = $task->reschedule();
            } catch (TaskFailedException $e) {
                $logger->error(sprintf('Task "%s" failed: %s', $task, $e->getMessage()));
                return true;
            }

            $logger->error(sprintf('An error occurred while executing task "%s": %s', $task, $e->getMessage()));
            $this->context->getQueue()->push($task, $eta);
        }

        return true;
    }

    abstract protected function doExecute(TaskInterface $task);
}
