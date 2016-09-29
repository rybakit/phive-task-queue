<?php

namespace Phive\TaskQueue\RetryStrategy;

class LimitedRetryStrategy implements RetryStrategy
{
    /**
     * @var RetryStrategy
     */
    private $retryStrategy;

    /**
     * The maximum number of retry attempts.
     *
     * @var int
     */
    private $retryLimit = 2;

    public function __construct(RetryStrategy $retryStrategy, $retryLimit = null)
    {
        $this->retryStrategy = $retryStrategy;

        if (null !== $retryLimit) {
            $this->retryLimit = $retryLimit;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getDelay($attempt)
    {
        if ($attempt <= $this->retryLimit) {
            return $this->retryStrategy->getDelay($attempt);
        }
    }
}
