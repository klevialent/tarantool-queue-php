<?php

namespace WebDevTeam\TarantoolQueuePhp;


abstract class AbstractQueue
{
    /**
     * @return static
     */
    public final static function getInstance()
    {
        if (! static::$instance) {
            static::$instance = static::createQueue();
        }

        return static::$instance;
    }

    abstract public function process();

    protected final function __construct($name, $client)
    {
        $this->name = $name;
        $this->client = $client;
    }

    abstract protected function createQueue();

    /**
     * @var AbstractQueue
     */
    protected static $instance;

    protected $client;

    protected $name;
}
