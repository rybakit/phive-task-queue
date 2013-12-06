<?php

namespace Phive\TaskQueue;

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
        try {
            $task = $this->context->getQueue()->pop();
        } catch (\Exception $e) {
            $this->context->getLogger()->error($e->getMessage());
            return false;
        }

        if (false === $task) {
            return false;
        }

        $logger = $this->context->getLogger();
        $logger->debug(sprintf('Dequeued "%s".', $task));

        if (!$task instanceof TaskInterface) {
            $task = new Task($task);
        }

        try {
            $this->doExecute($task);
            $logger->info(sprintf('Task "%s" was successfully executed.', $task));
        } catch (TaskFailedException $e) {
            $logger->error(sprintf('Task "%s" failed: %s', $task, $e->getMessage()));
        } catch (\Exception $e) {
            if ($eta = $task->reschedule()) {
                $logger->error(sprintf('An error occurred while executing task "%s": %s', $task, $e->getMessage()));
                $this->context->getQueue()->push($task, $eta);
            } else {
                $logger->error(sprintf('Task "%s" failed: %s.', $task, $e->getMessage()));
            }
        }

        return true;
    }

    abstract protected function doExecute(TaskInterface $task);
}
