<?php

require '../src/DelayQueue.php';

//消费队列端逻辑

//1.加载Redis
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);

//2.设置延迟队列
DelayQueue::$name = 'order';
DelayQueue::$handler = $redis;

//3.查询时间小于当前时间的队列数据,默认输出10条
$time = time();
$list = DelayQueue::get($time);
foreach ($list as $value)
{
    //输出信息
    echo "订单Id:" . $value . PHP_EOL;

    //发送消息给订单关联的用户(伪代码)
    //sendMsgToUserByOrderId($value);
}
