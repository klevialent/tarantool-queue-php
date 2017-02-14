<?php

namespace WebDevTeam\TarantoolQueuePhp\yii;

use Tarantool\Client\Client;
use Tarantool\Client\Connection\StreamConnection;
use Tarantool\Client\Packer\PurePacker;
use yii\base\Component;

class Queue extends Component
{
    public $queues;
    
    public function __get($name)
    {
        if (array_key_exists($name, $this->queues)) {
            $queueClass = $this->queues[$name]['class'];
            $client = new Client(new StreamConnection(), new PurePacker());
            return new $queueClass($client, $name);
        }

        return parent::__get($name);
    }
}
