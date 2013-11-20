<?php

namespace Phive\TaskQueue;

abstract class AbstractTask implements TaskInterface
{
    private $payload;

    public function __construct($payload = null)
    {
        if (null !== $payload) {
            $this->setPayload($payload);
        }
    }

    public function setPayload($payload)
    {
        $this->payload = $payload;
    }

    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return get_class($this);
    }
}
