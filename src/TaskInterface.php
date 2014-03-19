<?php

namespace Phive\TaskQueue;

interface TaskInterface
{
    /**
     * Retrieves task payload.
     *
     * @return mixed
     */
    public function getPayload();

    /**
     * Reschedules the task in the future (when an unexpected error occurred during execution).
     *
     * @return int|bool The timestamp of the next retry, false otherwise
     */
    public function reschedule();

    /**
     *  Returns a string representation of the task.
     *
     * @return string
     */
    public function __toString();
}
