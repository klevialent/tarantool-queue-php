<?php

namespace WebDevTeam\example;

use Symfony\Component\Console\Exception\InvalidArgumentException;

class QueueProcessController extends \WebDevTeam\TarantoolQueuePhp\QueueProcessController
{
    protected $queuesNamespace = '\\exmaple';
}
