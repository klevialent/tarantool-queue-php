<?php

namespace WebDevTeam\TarantoolQueuePhp\yii;

use WebDevTeam\TarantoolQueuePhp\WorkerInterface;
use Yii;
use yii\base\Object;
use WebDevTeam\TarantoolQueuePhp\QueueInterface;
use WebDevTeam\TarantoolQueuePhp\ClientInterface;

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
