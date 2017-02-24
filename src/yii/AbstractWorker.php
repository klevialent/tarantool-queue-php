<?php

namespace tucibi\tarantoolQueuePhp\yii;

use yii;
use yii\base\Object;
use tucibi\tarantoolQueuePhp\QueueInterface;
use tucibi\tarantoolQueuePhp\ClientInterface;
use tucibi\tarantoolQueuePhp\WorkerInterface;

/**
 * @property string $queueName
 * @property QueueInterface $queue
 * @property ClientInterface $client
 */
abstract class AbstractWorker extends Object implements WorkerInterface
{
    /**
     * @return QueueInterface
     */
    public function getQueue()
    {
        if ($this->_queue === null) {
            $this->_queue = Yii::$app->queue->{$this->getQueueName()};
        }
        
        return $this->_queue;
    }

    /**
     * @return ClientInterface
     */
    public function getClient()
    {
        return $this->getQueue()->getClient();
    }
    
    public function getQueueName()
    {
        return lcfirst(substr($this->className(), strrpos($this->className(), '\\') + 1));
    }
    
    private $_queue;
}
