<?php
$config = [
    'components' => [
        
        //minimal
        'queue' => [
            'class' => \tucibi\tarantoolQueuePhp\yii\QueuesManager::className(),
            'client' => \tucibi\tarantoolQueuePhp\TarantoolClient::class,
            'queues' => ['foobar'],
        ],
        
        //extended with defaults
        'queueExtended' => [
            'class' => \tucibi\tarantoolQueuePhp\yii\QueuesManager::className(),
            'client' => [
                'class' => \tucibi\tarantoolQueuePhp\TarantoolClient::class,
                'queue' => \tucibi\tarantoolQueuePhp\TarantoolQueue::class,
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
