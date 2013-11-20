<?php

namespace Phive\TaskQueue;

class Serializer implements SerializerInterface
{
    /**
     * {@inheritdoc}
     */
    public function serialize(TaskInterface $task)
    {
        return base64_encode(serialize($task));
    }

    /**
     * {@inheritdoc}
     */
    public function deserialize($data)
    {
        return unserialize(base64_decode($data));
    }
}
