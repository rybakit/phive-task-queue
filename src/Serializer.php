<?php

namespace Phive\TaskQueue;

interface Serializer
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
     * @return mixed
     */
    public function deserialize($data);
}
