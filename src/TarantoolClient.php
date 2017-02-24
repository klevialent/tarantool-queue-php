<?php

namespace tucibi\tarantoolQueuePhp;

class TarantoolClient extends \Tarantool\Client\Client implements ClientInterface
{
    /**
     * @inheritdoc
     */
    public static function createObject($option)
    {
        $connectionClass = static::DEFAULT_CONNECTION_CLASS;
        if (array_key_exists('connection', $option)) {
            if (is_array($option['connection'])) {
                $url = array_key_exists('url', $option['connection']) ? $option['connection']['url'] : null;
                $connection = new $option['connection']['class']($url);
            } else {
                $connection = new $option['connection']();
            }
        } else {
            $connection = new $connectionClass();
        }

        $packerClass = static::DEFAULT_PACKER_CLASS;
        if (array_key_exists('packer', $option)) {
            $packerClass = $option['packer'];
        }
        $packer = new $packerClass();

        return new static($connection, $packer);
    }

    /**
     * @inheritdoc
     */
    public function getQueue($name)
    {
        if (! array_key_exists($name, $this->queues)) {
            $className = static::DEFAULT_QUEUE_CLASS;
            $this->queues[$name] = new $className($name, $this);
        }

        return $this->queues[$name];
    }

    private $queues = [];

    const DEFAULT_CONNECTION_CLASS = \Tarantool\Client\Connection\StreamConnection::class;
    const DEFAULT_PACKER_CLASS = \Tarantool\Client\Packer\PurePacker::class;
    const DEFAULT_QUEUE_CLASS = \tucibi\tarantoolQueuePhp\TarantoolQueue::class;
}
