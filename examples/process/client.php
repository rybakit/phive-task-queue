<?php

require __DIR__.'/../../vendor/autoload.php';

use Phive\Queue\SysVQueue;

$queue = new SysVQueue(0xDEADBEAF, true);

$queue->push((object) ['commandline' => 'ls -la']);
