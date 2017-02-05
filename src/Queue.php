<?php

namespace WebDevTeam\TarantoolQueuePhp;

use Tarantool\Client\Tarantool;
use Tarantool\Queue\Task;

abstract class Queue
{
    abstract function process();

    /**
     * @return Queue
     */
    public static function getInstance()
    {
        if (! static::$instance) {
            $tarantool = new Tarantool(new StreamConnection(), new PurePacker());
            $className = substr(static::class, strrpos(static::class, '\\') + 1);
            static::$instance = new static($tarantool, lcfirst(str_replace('Queue', '', $className)));
        }

        return static::$instance;
    }

    /**
     * @param string $name
     * @return Queue
     */
    public static function get($name)
    {
        if (! array_key_exists($name, static::$queues)) {
            $tarantool = new Tarantool(new StreamConnection(), new PurePacker());

            static::$queues[$name] = new FoobarQueue($tarantool, $name);
        }

        return static::$queues[$name];
    }

    protected $name;

    /**
     * @var Queue[]
     */
    protected static $queues = [];

    /**
     * @var Queue
     */
    protected static $instance;

//    protected function __construct()
//    {
//
//    }

    /**
     * @var \Tarantool\Client\Client
     */
    private $client;

    private $tubeName;
    private $prefix;

    public function __construct($client, $tubeName)
    {
        $this->client = $client;
        $this->tubeName = $tubeName;
        $this->prefix = "queue.tube.$tubeName:";
    }

    /**
     * @param mixed      $data
     * @param array|null $options
     *
     * @return Task
     */
    public function put($data, array $options = null)
    {
        $args = $options ? [$data, $options] : [$data];

        return $this->resultTask('put', $args);
    }

    /**
     * @param int|float|null $timeout
     *
     * @return Task|null
     */
    public function take($timeout = null)
    {
        $args = null === $timeout ? [] : [$timeout];
        $result = $this->command('take', $args);

        return empty($result) ? null : Task::createFromTuple($result);
    }

    /**
     * @param int $taskId
     *
     * @return Task
     */
    public function ack($taskId)
    {
        return $this->resultTask('ack', [$taskId]);
    }

    /**
     * @param int        $taskId
     * @param array|null $options
     *
     * @return Task
     */
    public function release($taskId, array $options = null)
    {
        $args = $options ? [$taskId, $options] : [$taskId];

        return $this->resultTask('release', $args);
    }

    /**
     * @param int $taskId
     *
     * @return Task
     */
    public function peek($taskId)
    {
        return $this->resultTask('peek', [$taskId]);
    }

    /**
     * @param int $taskId
     *
     * @return Task
     */
    public function bury($taskId)
    {
        return $this->resultTask('bury', [$taskId]);
    }

    /**
     * @param int $count
     *
     * @return int
     */
    public function kick($count)
    {
        return $this->command('kick', [$count])[0];
    }

    /**
     * @param int $taskId
     *
     * @return Task
     */
    public function delete($taskId)
    {
        return $this->resultTask('delete', [$taskId]);
    }

    public function truncate()
    {
        $this->command('truncate');
    }

    /**
     * @param string|null $path
     *
     * @return array|int
     *
     * @throws \InvalidArgumentException
     */
    public function stats($path = null)
    {
        $result = $this->command('queue.stats', [$this->tubeName]);

        if (null === $path) {
            return $result[0];
        }

        $result = $result[0];
        foreach (explode('.', $path) as $key) {
            if (!isset($result[$key])) {
                throw new \InvalidArgumentException(sprintf('Invalid path "%s".', $path));
            }
            $result = $result[$key];
        }

        return $result;
    }

    /**
     * @param string $command
     * @param array $args
     * @return array
     */
    private function command($command, $args = null)
    {
        return $this->client->call($this->prefix . $command, $args)->getData()[0];
    }

    /**
     * @param string $command
     * @param array $args
     * @return Task
     */
    private function resultTask($command, $args = null)
    {
        return Task::createFromTuple($this->command($command, $args));
    }

    /**
     * @param int $limit
     *
     * @return Task[]
     */
    protected function fetchTasks($limit)
    {
        $tasks = [];
        while ($limit > 0) {
            if (! ($task = $queue->take())) break;

            $tasks[] = new Task($task->getId(), $task->getData());
            $limit--;
        }

        $tarantool->disconnect();

        return $tasks;
    }
}
