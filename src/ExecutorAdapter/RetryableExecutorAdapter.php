<?php

namespace Phive\TaskQueue\ExecutorAdapter;

use Phive\TaskQueue\ExecutionContext;
use Phive\TaskQueue\ExecutionFailedException;
use Phive\TaskQueue\RetryAwarePayload;
use Phive\TaskQueue\RetryStrategy\RetryStrategy;

class RetryableExecutorAdapter implements ExecutorAdapter
{
    private $adapter;
    private $retryStrategy;

    public function __construct(ExecutorAdapter $adapter, RetryStrategy $retryStrategy)
    {
        $this->adapter = $adapter;
        $this->retryStrategy = $retryStrategy;
    }

    public function execute($payload, ExecutionContext $context)
    {
        if (!$payload instanceof RetryAwarePayload) {
            $payload = new RetryAwarePayload($payload);
        }

        try {
            return $this->adapter->execute($payload->getPayload(), $context);
        } catch (ExecutionFailedException $e) {
            $context->getLogger()->error('Task failed: '.$e->getMessage(), ['payload' => $payload->getPayload()]);
        } catch (\Exception $e) {
            $delay = $this->retryStrategy->getDelay($payload->incRetry());

            if (null === $delay) {
                $context->getLogger()->error('Task failed: '.$e->getMessage(), ['payload' => $payload->getPayload()]);

                return true;
            }

            $context->getLogger()->error('An error occurred while executing the task: '.$e->getMessage(), ['payload' => $payload->getPayload()]);
            $context->getQueue()->push($payload, "+$delay seconds");
        }

        return true;
    }
}
