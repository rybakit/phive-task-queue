<?php

namespace Phive\TaskQueue\RetryStrategy;

class ConstantRetryStrategy implements RetryStrategy
{
    private $delay = 60;

    public function __construct($delay = null)
    {
        if (null !== $delay) {
            $this->delay = $delay;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getDelay($attempt)
    {
        return $this->delay;
    }
}
