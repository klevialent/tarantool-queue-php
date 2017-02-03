<?php

namespace WebDevTeam\TarantoolQueuePhp;

use yii\console\Controller;

class QueueProcessController extends Controller
{
    public function run($route, $params = [])
    {
        echo $route; die();

//        $pos = strpos($route, '/');
//        if ($pos === false) {
//            return $this->runAction($route, $params);
//        } elseif ($pos > 0) {
//            return $this->module->runAction($route, $params);
//        } else {
//            return Yii::$app->runAction(ltrim($route, '/'), $params);
//        }
    }
}
