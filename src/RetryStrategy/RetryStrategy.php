<?php

namespace Phive\TaskQueue\RetryStrategy;

interface RetryStrategy
{
    /**
     * @param int $attempt
     *
     * @return int|null
     */
    public function getDelay($attempt);
}
