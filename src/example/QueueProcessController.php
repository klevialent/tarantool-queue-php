<?php

namespace tucibi\tarantoolQueuePhp\example;

use Symfony\Component\Console\Exception\InvalidArgumentException;

class QueueProcessController extends \tucibi\tarantoolQueuePhp\QueueProcessController
{
    protected $queuesNamespace = '\\exmaple';
}
