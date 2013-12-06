<?php

namespace Phive\TaskQueue;

interface SerializerInterface
{
    /**
     * @param mixed $data
     *
     * @return string
     */
    public function serialize($data);

    /**
     * @param string $data
     *
     * @return TaskInterface
     */
    public function deserialize($data);
}
