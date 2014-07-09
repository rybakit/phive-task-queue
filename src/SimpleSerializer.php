<?php

namespace Phive\TaskQueue;

class SimpleSerializer implements Serializer
{
    /**
     * {@inheritdoc}
     */
    public function serialize($data)
    {
        return base64_encode(serialize($data));
    }

    /**
     * {@inheritdoc}
     */
    public function deserialize($data)
    {
        return unserialize(base64_decode($data));
    }
}
