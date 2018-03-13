<?php

$config = [
    'components' => [
        
        //minimal
        'queue' => [
            'class' => \Klevialent\TarantoolQueuePhp\yii\QueuesManager::className(),
            'client' => \Klevialent\TarantoolQueuePhp\TarantoolClient::class,
            'queues' => ['foobar'],
        ],
        
        //extended with defaults
        'queueExtended' => [
            'class' => \Klevialent\TarantoolQueuePhp\yii\QueuesManager::className(),
            'client' => [
                'class' => \Klevialent\TarantoolQueuePhp\TarantoolClient::class,
                'queue' => \Klevialent\TarantoolQueuePhp\TarantoolQueue::class,
                'connection' => [
                    'class' => \Tarantool\Client\Connection\StreamConnection::class,
                    'url' => 'tcp://127.0.0.1:3301',
                ],
                'packer' => \Tarantool\Client\Packer\PurePacker::class,
            ],
            'queues' => ['foobar'],
        ],
        
    ],
];

return $config;
