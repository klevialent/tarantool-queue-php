<?php

namespace tucibi\tarantoolQueuePhp;

interface WorkerInterface
{
    public function process();
}
