<?php

namespace WebDevTeam\example;


class QueueController
{
    public function actionPut($value = 'hey')
    {
        $rand = mt_rand(100000, 999999);

        echo $rand . '<br>';

        $task = FoobarQueue::getInstance()->put($value . $rand);

        echo $task->getId() . ' - ' . $task->getData() . ' - ' . $task->getState() . '<br>';
    }
}
