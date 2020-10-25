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


//(1).在队列中查询time<=1603597141 and time>=1603597132 的元素返回10条即可,不删除队列数据,仅查看数据
$time1 = 1603597693;
$time2 = 1603597132;
$list = DelayQueue::get($time1, $time2, 10, true);
var_dump($list);
