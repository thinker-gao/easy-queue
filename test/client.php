<?php

require '../src/DelayQueue.php';

//创建订单加入延迟队列逻辑
function a($a,...$b)
{
    var_dump($a,$b);
}


$b = [2,3];
a(1,...$b);

die();
//1.加载Redis
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);

//2.设置延迟队列
DelayQueue::$name = 'order';
DelayQueue::$handler = $redis;

//3.投递队列
$time = time() + 30; //下单成功30秒需要处理
$orderId = uniqid();
DelayQueue::add($time, $orderId);