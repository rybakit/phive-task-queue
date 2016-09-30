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
            $payload = $this->context->getQueue()->pop();
        } catch (NoItemAvailableException $e) {
            $logger->debug($e->getMessage());

            return false;
        }

        $logger->info('Start executing.', ['payload' => $payload]);

        try {
            $this->adapter->execute($payload, $this->context);
            $logger->info('Payload was successfully executed.', ['payload' => $payload]);
        } catch (\Exception $e) {
            $logger->error(sprintf('An error occurred while executing payload: %s.', $e->getMessage()), ['payload' => $payload]);
        }

        return true;
    }
}
