<?php

namespace WebDevTeam\TarantoolQueuePhp;

use Yii;
use yii\base\InlineAction;
use yii\base\InvalidRouteException;
use yii\console\Controller;

class QueueProcessController extends Controller
{
    public function createAction($id)
    {
        $action = parent::createAction($id);
        if ($action) {
            return $action;
        }

        $queueClass = $this->queuesNamespace . '\\' . ucfirst($id) . 'Queue';

        if (! class_exists($queueClass)) {
            throw new InvalidRouteException('Unable to resolve the request: ' . $this->getUniqueId() . '/' . $id);
        }

        $queue = forward_static_call([$queueClass, 'getInstance']);
        $queue->process();
        
        exit(0);
        
//        call_user_func([$queueClass, ]);
//
////        return new InlineAction($id, $queueClass::getInstance(), 'process');
//
//        return $queueClass::getInstance()->process();
    }

    public function __call($name, $arguments = null)
    {
        $queueClass = $this->queuesNamespace . '\\' . str_replace('action', '', $name) . 'Queue';
        if (! class_exists($queueClass)) {
            echo 'class exists ' . $queueClass; die();
            throw new InvalidRouteException('Unable to resolve the request: ' . $this->getUniqueId() . '/' . $queueClass);
        }
//
//
        echo 'blin :( ' . $queueClass; die();

//        $instance = call_user_func([$queueClass]);

//        call_user_func_array([$queueClass::class::getInstance(), 'process'], $params);
//        
//        $queueClass::getInstance()->process();
    }
    
    public function actionOk()
    {
        echo 'ook!';
        die();
    }

    private $queuesNamespace = '\\common\\queues';
}
