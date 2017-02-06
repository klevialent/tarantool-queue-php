<?php

namespace WebDevTeam\TarantoolQueuePhp;

use Tarantool\Client\Connection\StreamConnection;
use Tarantool\Client\Packer\PurePacker;
use Tarantool\Client\Tarantool;
use Tarantool\Queue\Task;


abstract class TarantoolQueue extends AbstractQueue
{
    /**
     * @return static
     */
    protected final function createQueue()
    {
        $tarantool = new Tarantool(new StreamConnection(), new PurePacker());
        $className = substr(static::class, strrpos(static::class, '\\') + 1);
        return new static(lcfirst(str_replace('Queue', '', $className)), $tarantool);
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
        $result = $this->command('queue.stats', [$this->name]);

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
     * @param int $limit
     *
     * @return Task[]
     */
    public function fetchTasks($limit)
    {
        $this->client->connect();
        $tasks = [];
        while ($limit > 0) {
            if (! ($task = $this->take())) break;

            $tasks[] = $task;
            $limit--;
        }

        $this->client->disconnect();

        return $tasks;
    }

    /**
     * @param string $command
     * @param array $args
     * @return array
     */
    private function command($command, $args = null)
    {
        return $this->client->call("queue.tube.$this->name:" . $command, $args)->getData()[0];
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
     * @var Tarantool
     */
    protected $client;
}
