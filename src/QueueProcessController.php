<?php

namespace WebDevTeam\TarantoolQueuePhp;

class QueueProcessController extends \yii\console\Controller
{
    public function actionIndex($queueName)
    {
        $worker = $this->queuesNamespace . '\\' . ucfirst($queueName);

        if (! class_exists($worker)) {
            throw new \InvalidArgumentException("Unknown worker for queue \"$queueName\". You must define class \"$worker\".");
        }

        $worker::process();
    }

    public $queuesNamespace = '\\console\\workers';
}
