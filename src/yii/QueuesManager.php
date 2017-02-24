<?php

namespace tucibi\tarantoolQueuePhp\yii;

use tucibi\tarantoolQueuePhp\ClientInterface;

class QueuesManager extends \yii\base\Component
{
    /**
     * @var ClientInterface|array|string
     */
    public $client;
 
    public $queues;

    public function init()
    {
        parent::init();

        if (! $this->client instanceof ClientInterface) {
            $options = $this->client;
            if (! is_array($options)) {
                $options = ['class' => $options];
            }
            
            $this->client = $options['class']::createObject($options);
        }
    }

    public function __get($name)
    {
        if (in_array($name, $this->queues)) {
            return $this->client->getQueue($name);
        }

        return parent::__get($name);
    }
}
