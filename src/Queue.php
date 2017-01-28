<?php

namespace WebDevTeam\TarantoolQueuePhp;

use Tarantool\Queue\Task;

class Queue
{
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
}
