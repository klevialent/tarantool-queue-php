<?php

namespace WebDevTeam\TarantoolQueuePhp;

use yii\base\Action;


class WorkerAction extends Action
{
    /**
     * Constructor.
     *
     * @param string $id the ID of this action
     * @param Controller $controller the controller that owns this action
     * @param array $config name-value pairs that will be used to initialize the object properties
     */
    public function __construct($queueClass)
    {
        
        $this->queueClass = $queueClass;
        parent::__construct(null, null, null);
    }
    
    public function run()
    {
        return $this->queueClass->process();
    }

    /**
     * @var AbstractQueue
     */
    private $queueClass;
}
