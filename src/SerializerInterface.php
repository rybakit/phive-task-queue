<?php

namespace Phive\TaskQueue;

interface SerializerInterface
{
    /**
     * @param TaskInterface $task
     *
     * @return string
     */
    public function serialize(TaskInterface $task);

    /**
     * @param string $data
     *
     * @return TaskInterface
     */
    public function deserialize($data);
}
