<?php

$config = [
    'controllerMap' => [
        
        //minimal
        'queue-process' => \Klevialent\TarantoolQueuePhp\yii\QueueProcessController::className(),
        
        //extended with defaults
        'queue-process-changed-namespace' => [
            'class' => \Klevialent\TarantoolQueuePhp\yii\QueueProcessController::className(),
            'queuesNamespace' => '\\console\\workers'
        ]
        
    ],
];

return $config;
