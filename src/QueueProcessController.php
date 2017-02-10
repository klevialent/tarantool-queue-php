<?php

namespace WebDevTeam\TarantoolQueuePhp;

use Symfony\Component\Console\Exception\InvalidArgumentException;

class QueueProcessController extends \yii\console\Controller
{
    public function actionIndex($queueName)
    {
        $queueClass = $this->queuesNamespace . '\\' . ucfirst($queueName) . 'Queue';

        if (! class_exists($queueClass)) {
            throw new InvalidArgumentException("Unknown queue \"$queueName\". You must define class \"$queueClass\".");
        }

        $queueClass::getInstance()->process();
    }

    protected $queuesNamespace = '\\common\\queues';
}
