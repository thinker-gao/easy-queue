# EasyDelayQueue

#### 类库介绍
EasyDelayQueue简单易用的Redis延迟队列.基于Redis有序集合实现的Redis延迟队列,因此添加/删除/查找的复杂度都是 O(1),每个队列中可存储40多亿成员

#### 需要环境
PHP>=5.4+Redis


#### 安装教程

~~~
  composer require easy-delay-queue/easy-delay-queue
~~~

#### 生产者
~~~
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
~~~

#### 消费者
~~~
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
~~~
### Get参数说明
~~~
$e_score:大于多少
~~~