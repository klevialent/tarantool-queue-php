<?php

namespace WebDevTeam\TarantoolQueuePhp;

use Tarantool\Client\Tarantool;
use Tarantool\Queue\Task;

abstract class AbstractQueue
{
    abstract public function process();
    
    abstract protected function createQueue();

    /**
     * @return static
     */
    public static function getInstance()
    {
        if (! static::$instance) {
            static::$instance = static::createQueue();
        }

        return static::$instance;
    }

    /**
     * @var AbstractQueue
     */
    protected static $instance;

    protected $client;

    protected $name;

    protected function __construct($client, $name)
    {
        $this->client = $client;
        $this->name = $name;
    }

    /**
     * @param mixed      $data
     * @param array|null $options
     *
     * @return Task
     */
    abstract public function put($data, array $options = null);

    /**
     * @param int|float|null $timeout
     *
     * @return Task|null
     */
    abstract public function take($timeout = null);

    /**
     * @param int $taskId
     *
     * @return Task
     */
    abstract public function ack($taskId);

    /**
     * @param int        $taskId
     * @param array|null $options
     *
     * @return Task
     */
    abstract public function release($taskId, array $options = null);

    /**
     * @param int $taskId
     *
     * @return Task
     */
    abstract public function peek($taskId);

    /**
     * @param int $taskId
     *
     * @return Task
     */
    abstract public function bury($taskId);

    /**
     * @param int $count
     *
     * @return int
     */
    abstract public function kick($count);

    /**
     * @param int $taskId
     *
     * @return Task
     */
    abstract public function delete($taskId);

    abstract public function truncate();

    /**
     * @param string|null $path
     *
     * @return array|int
     *
     * @throws \InvalidArgumentException
     */
    abstract public function stats($path = null);


    /**
     * @param int $limit
     *
     * @return Task[]
     */
    abstract protected function fetchTasks($limit);
}
