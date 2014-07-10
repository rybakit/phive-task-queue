<?php

namespace Phive\TaskQueue\Task;

use Phive\TaskQueue\TaskFailedException;

class GenericTask extends AbstractTask
{
    /**
     * The number of failed attempts.
     *
     * @var int
     */
    private $attempt = 0;

    /**
     * The maximum number of failed attempts.
     *
     * @var int
     */
    private $maxAttempts = 3;

    /**
     * The time interval, in seconds, between task retries.
     *
     * @var int
     */
    private $retryDelay = 60;

    /**
     * {@inheritdoc}
     */
    public function reschedule()
    {
        if (!$this->maxAttempts || $this->attempt < $this->maxAttempts) {
            throw new TaskFailedException(sprintf(
                'The maximum number of failed attempts (%d) has been reached.',
                $this->maxAttempts
            ));
        }

        $this->attempt++;

        return time() + $this->retryDelay;
    }

    /**
     * @return int
     */
    public function getAttempt()
    {
        return $this->attempt;
    }

    /**
     * @param int $maxAttempts
     */
    public function setMaxAttempts($maxAttempts)
    {
        $this->maxAttempts = $maxAttempts;
    }

    /**
     * @return int
     */
    public function getMaxAttempts()
    {
        return $this->maxAttempts;
    }

    /**
     * @param int $delay
     */
    public function setRetryDelay($delay)
    {
        $this->retryDelay = $delay;
    }

    /**
     * @return int
     */
    public function getRetryDelay()
    {
        return $this->retryDelay;
    }

    public function __clone()
    {
        $this->attempt = 0;
    }
}
