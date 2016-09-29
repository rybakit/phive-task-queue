<?php

namespace Phive\TaskQueue\RetryStrategy;

class LinearRetryStrategy implements RetryStrategy
{
    private $step = 60;

    public function __construct($step = null)
    {
        if (null !== $step) {
            $this->step = $step;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getDelay($attempt)
    {
        return $attempt * $this->step;
    }
}
