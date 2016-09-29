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

    public function incRetry()
    {
        return ++$this->retry;
    }

    public function __clone()
    {
        $this->retry = 0;
    }
}
