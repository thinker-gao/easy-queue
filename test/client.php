<?php

require '../src/Queue.php';

//1.加载Redis
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);

//2.设置队列
$queue = new \EasyQueue\Queue($redis);

//3.投递队列
$name = 'order'; //队列名称
$time = time() + 120; //下单成功30秒需要处理
$orderId = uniqid();
if ($queue->add($name, $orderId, $time))
{
    echo 'success' . PHP_EOL;
}
else
{
    echo 'failed' . PHP_EOL;
}