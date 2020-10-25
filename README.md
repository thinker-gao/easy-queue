<p><h4>EasyDelayQueue简单易用的PHP-Redis延迟队列</h4></p>

## <h4 style="text-align:left">  队列介绍 </h4>
<p>基于Redis有序集合实现的Redis延迟队列，添加/删除/查找的复杂度都是 O(1)，每个队列中可存储40多亿元素。具体实现请阅读我发布的文章:<a href="https://www.gaojiufeng.cn/?id=516">机票</a></p>

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

## <h5>【C】. 快速入门->其他操作 </h5>
~~~
(1).在队列中查询time<=1603597141 and time>=0 的元素(同时队列中会自动删除符合条件的元素)
$time = 1603597141;
$list = DelayQueue::get($time);

(2).在队列中查询time<=1603597141 and time>=1603597132 的元素(同时队列中会自动删除符合条件的元素)
$time1 = 1603597413;
$time2 = 1603597132;
$list = DelayQueue::get($time1, $time2);

(3).在队列中查询time<=1603597141 and time>=1603597132 的元素,只返回1条(同时队列中会自动删除这条元素)
$time1 = 1603597555;
$time2 = 1603597132;
$list = DelayQueue::get($time1, $time2,1);

(4).在队列中查询time<=1603597141 and time>=1603597132 的元素返回10条即可,不删除队列数据,仅查看数据
$time1 = 1603597693;
$time2 = 1603597132;
$list = DelayQueue::get($time1, $time2, 10, false);
~~~

## <h5>【D】. 快速入门->元素重复 </h5>
~~~
Redis有序集合中元素内容不得重复,上面实例中都是传递的订单Id,如果我们想投递多次相同订单Id,何如？
(1).Value中传递唯一Id,同订单Id组合的Json形式,例如
$data = [
    'order_id'=>1,
    'order_uniqid'=>uniqid()
];
$res =  DelayQueue::add(time() + 30, json_encode($data));
(2).Value中前X位存储订单Id,后X位存储唯一Id(推荐)
~~~

## <h5>【E】. Bug反馈 </h5>
~~~
请反馈至QQ392223903,感谢持续反馈!
~~~