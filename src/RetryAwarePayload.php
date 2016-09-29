<?php

namespace Phive\TaskQueue;

class RetryAwarePayload
{
    private $payload;
    private $retry = 0;

    public function __construct($payload)
    {
        $this->payload = $payload;
    }

    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * @return int
     */
    public function getRetry()
    {
        return $this->retry;
    }

    public function incRetry()
    {
        ++$this->retry;
    }

    public function __clone()
    {
        $this->retry = 0;
    }
}
