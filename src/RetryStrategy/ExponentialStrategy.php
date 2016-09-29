<?php

namespace Phive\TaskQueue\RetryStrategy;

class ExponentialRetryStrategy implements RetryStrategy
{
    private $base = 60;

    public function __construct($base = null)
    {
        if (null !== $base) {
            $this->base = $base;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getDelay($attempt)
    {
        return pow($this->base, $attempt);
    }
}
