<?php

namespace frontend\controllers;

use yii;

class QueueController
{
    public function actionPut($value = 'hey')
    {
        $rand = mt_rand(100000, 999999);

        echo $rand . '<br>';

        $task = Yii::$app->queue->foobar->put($value . $rand);

        echo $task->getId() . ' - ' . $task->getData() . ' - ' . $task->getState() . '<br>';
    }
}
