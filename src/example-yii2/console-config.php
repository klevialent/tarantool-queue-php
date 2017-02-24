<?php

$config = [
    'controllerMap' => [
        
        //minimal
        'queue-process' => \tucibi\tarantoolQueuePhp\yii\QueueProcessController::className(),
        
        //extended with defaults
        'queue-process-changed-namespace' => [
            'class' => \tucibi\tarantoolQueuePhp\yii\QueueProcessController::className(),
            'queuesNamespace' => '\\console\\workers'
        ]
        
    ],
];

return $config;
