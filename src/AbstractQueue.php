<?php

namespace WebDevTeam\TarantoolQueuePhp;


abstract class AbstractQueue
{
    abstract protected function createQueue();
    
    abstract public function process();
    
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

    /**
     * @param String $name
     * @param $client
     */
    protected final function __construct($name, $client)
    {
        $this->name = $name;
        $this->client = $client;
    }
    
    /**
     * @var static
     */
    protected static $instance;

    protected $client;

    /**
     * @var String
     */
    protected $name;
}
