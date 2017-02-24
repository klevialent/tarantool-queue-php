<?php

namespace tucibi\tarantoolQueuePhp;

interface ClientInterface
{
    /**
     * @param array|string $option
     * @return ClientInterface
     */
    public static function createObject($option);

    /**
     * @param string $name
     * @return QueueInterface   
     */
    public function getQueue($name);
    
    /**
     * @param string $funcName
     * @param array $args
     * @return mixed
     */
    public function call($funcName, array $args = []);
    
    public function connect();
    
    public function disconnect();
}
