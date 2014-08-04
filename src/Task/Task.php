<?php

namespace Phive\TaskQueue\Task;

interface Task
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
     * @return int The timestamp of the next retry
     *
     * @throws \Phive\TaskQueue\TaskFailedException
     */
    public function reschedule();

    /**
     * Returns a string representation of the task.
     *
     * @return string
     */
    public function __toString();
}
