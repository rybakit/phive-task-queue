<?php

namespace Phive\TaskQueue;

use Phive\Queue\CallbackIterator;
use Phive\Queue\QueueInterface;

class SerializerAwareQueue implements QueueInterface
{
    /**
     * @var QueueInterface
     */
    private $queue;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(QueueInterface $queue, SerializerInterface $serializer = null)
    {
        $this->queue = $queue;
        $this->serializer = $serializer ?: new Serializer();
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

        return new CallbackIterator($iterator, function ($item) {
            return $this->serializer->deserialize($item);
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
