# EasyDelayQueue

#### 介绍
简单易用的PHP-Reids延迟队列

#### 软件架构
软件架构说明


#### 安装教程

1.  xxxx
2.  xxxx
3.  xxxx

#### 生产者

//创建订单加入延迟队列逻辑

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

#### 消费者

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