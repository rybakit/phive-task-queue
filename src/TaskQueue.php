<?php

namespace Phive\TaskQueue;

use Phive\Queue\CallbackIterator;
use Phive\Queue\QueueInterface;

class TaskQueue implements QueueInterface
{
    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * @var QueueInterface
     */
    private $queue;

    public function __construct(QueueInterface $queue, SerializerInterface $serializer = null)
    {
        $this->queue = $queue;
        $this->serializer = $serializer ?: new Serializer();
    }

    /**
     * @return QueueInterface
     */
    public function getQueue()
    {
        return $this->queue;
    }

    /**
     * {@inheritdoc}
     */
    public function push($item, $eta = null)
    {
        if (!$item instanceof TaskInterface) {
            $item = new Task($item);
        }

        $item = $this->serializer->serialize($item);
        $this->queue->push($item, $eta);
    }

    /**
     * {@inheritdoc}
     */
    public function pop()
    {
        if (false !== $item = $this->queue->pop()) {
            $item = $this->serializer->deserialize($item);
        }

        return $item;
    }

    /**
     * {@inheritdoc}
     */
    public function slice($offset, $limit)
    {
        $iterator = $this->queue->slice($offset, $limit);
        $serializer = $this->serializer;

        return new CallbackIterator($iterator, function ($item) use ($serializer) {
            return $serializer->deserialize($item);
        });
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
}
