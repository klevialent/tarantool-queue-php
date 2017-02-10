<?php

namespace WebDevTeam\example;

use WebDevTeam\TarantoolQueuePhp\TarantoolQueue;

class FoobarQueue extends TarantoolQueue
{
    public function process()
    {
        while (true) {
            $task = $this->take();
            if (empty($task)) {
                $this->client->disconnect();
                echo 'sleep' . PHP_EOL;
                sleep(10);
                continue;
            }

            echo $task->getId() . ' : ' . $task->getData() . ' : ' . $task->getState() . PHP_EOL;

            $this->ack($task->getId());
        }
    }
}
