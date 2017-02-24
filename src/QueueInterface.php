<?php

namespace WebDevTeam\TarantoolQueuePhp;

interface QueueInterface
{
    /**
     * @param string $name
     * @param ClientInterface $client
     */
    public function __construct($name, ClientInterface $client);
}
