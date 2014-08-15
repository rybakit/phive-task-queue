<?php

require __DIR__.'/../vendor/autoload.php';

use Phive\Queue\SysVQueue;

$queue = new SysVQueue(0xDEADBEAF, true);

// send a task/payload object to the queue and delay execution for 5 seconds
// see supported item types: https://github.com/rybakit/phive-queue#item-types
$queue->push((object) ['name' => 'Stranger'], '+5 seconds');
