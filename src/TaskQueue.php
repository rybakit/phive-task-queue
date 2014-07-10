<?php

namespace Phive\TaskQueue;

use Phive\Queue\Queue;

class TaskQueue implements Queue
{
    /**
     * @var Queue
     */
    private $queue;

    /**
     * @var Serializer
     */
    private $serializer;

    public function __construct(Queue $queue, Serializer $serializer)
    {
        $this->queue = $queue;
        $this->serializer = $serializer;
    }

    /**
     * {@inheritdoc}
     */
    public function push($item, $eta = null)
    {
        $item = $this->serializer->serialize($item);
        $this->queue->push($item, $eta);
    }

    /**
     * {@inheritdoc}
     */
    public function pop()
    {
        $item = $this->queue->pop();

        return $this->serializer->deserialize($item);
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return $this->queue->count();
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        $this->queue->clear();
    }

    /**
     * @return Queue
     */
    public function getQueue()
    {
        return $this->queue;
    }

    /**
     * @return Serializer
     */
    public function getSerializer()
    {
        return $this->serializer;
    }
}
