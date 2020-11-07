<?php

require '../src/Queue.php';

//1.加载Redis
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);

//2.设置队列
$queue = new \EasyQueue\Queue($redis);

//3.消费队列
$name = 'order';
$res = $queue->get($name);
if ($res)
{
    var_dump($res);
}
