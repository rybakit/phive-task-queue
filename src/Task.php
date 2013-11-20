<?php

namespace Phive\TaskQueue;

class Task extends AbstractTask
{
    /**
     * The number of times this task has been failed.
     *
     * @var int
     */
    private $errorCount = 0;

    /**
     * The maximum number of fails for this task.
     *
     * @var int
     */
    private $maxErrorCount = 2;

    /**
     * The time interval, in seconds, between task retries.
     *
     * @var int
     */
    private $retryDelay = 60;

    public function reschedule()
    {
        if ($this->maxErrorCount && $this->errorCount >= $this->maxErrorCount) {
            return false;
        }

        $this->errorCount++;

        return time();
    }

    /**
     * @return int
     */
    public function getErrorCount()
    {
        return $this->errorCount;
    }

    /**
     * @param int $count
     */
    public function setMaxErrorCount($count)
    {
        $this->maxErrorCount = $count;
    }

    /**
     * @return int
     */
    public function getMaxErrorCount()
    {
        return $this->maxErrorCount;
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

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return get_class($this);
    }

    public function __clone()
    {
        $this->errorCount = 0;
    }
}
