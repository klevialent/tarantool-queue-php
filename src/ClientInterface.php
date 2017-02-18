<?php

namespace WebDevTeam\TarantoolQueuePhp;

interface ClientInterface
{
    /**
     * @param $funcName
     * @param array $args
     * @return mixed
     */
    public function call($funcName, array $args = []);
    
    public function connect();
    
    public function disconnect();
}
