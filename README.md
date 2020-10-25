<p><h4>EasyDelayQueue简单易用的PHP-Redis延迟队列</h4></p>

## <h4 style="text-align:left">  队列介绍 </h4>
<p>基于Redis有序集合实现的Redis延迟队列，添加/删除/查找的复杂度都是 O(1)，每个队列中可存储40多亿元素。</p>

## <h4 style="text-align:left">  环境依赖 </h4>
~~~
"php": ">=5.4"
~~~

## <h4>  Composer安装 </h4>

~~~
  composer require easy-delay-queue/easy-delay-queue
~~~

## <h5>【A】. 快速入门->生产者 </h5>
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

## <h5>【B】. 快速入门->消费者 </h5>
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