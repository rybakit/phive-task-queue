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
     * @return bool
     */
    public function execute()
    {
        $logger = $this->context->getLogger();

        try {
            $task = $this->context->getTaskQueue()->pop();
        } catch (\Exception $e) {
            $logger->error($e->getMessage());
            return false;
        }

        $logger->debug(sprintf('Dequeued "%s".', $task));

        try {
            $this->doExecute($task);
            $logger->info(sprintf('Task "%s" was successfully executed.', $task));
        } catch (TaskFailedException $e) {
            $logger->error(sprintf('Task "%s" failed: %s', $task, $e->getMessage()));
        } catch (\Exception $e) {
            if ($eta = $task->reschedule()) {
                $logger->error(sprintf('An error occurred while executing task "%s": %s', $task, $e->getMessage()));
                $this->context->getTaskQueue()->push($task, $eta);
            } else {
                $logger->error(sprintf('Task "%s" failed: %s.', $task, $e->getMessage()));
            }
        }

        return true;
    }

    abstract protected function doExecute(TaskInterface $task);
}
