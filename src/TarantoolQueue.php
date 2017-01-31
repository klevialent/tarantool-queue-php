<?php

namespace WebDevTeam\TarantoolQueuePhp;

use MailRu\QueueProcessor\Task;
use MailRu\QueueProcessor\Queue\AbstractQueue;
use Tarantool\Client\Connection\StreamConnection;
use Tarantool\Client\Packer\PurePacker;
use Tarantool\Client\Tarantool;


class TarantoolQueue extends AbstractQueue
{
    /**
     * @param int $limit
     *
     * @return Task[]
     */
    protected function fetchTasks($limit)
    {
        $tarantool = new Tarantool(new StreamConnection(), new PurePacker());

        $queue = new Queue($tarantool, 'foobar');

        $tasks = [];
        while ($limit > 0) {
            if (! ($task = $queue->take())) break;

            $tasks[] = new Task($task->getId(), $task->getData());
            $limit--;
        }
        
        return $tasks;
    }

    /**
     * @param Task[]|null $tasks
     */
    public function unlockTasks(array $tasks = null)
    {

    }
}
