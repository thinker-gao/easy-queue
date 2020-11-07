<p><h4>EasyQueue简单易用PHP-Redis队列,支持延迟队列</h4></p>

## <h4 style="text-align:left">  队列介绍 </h4>
<p>基于Redis有序集合实现的Redis延迟队列，添加/删除/查找的复杂度都是 O(1)，每个队列中可存储40多亿元素。</p>

## <h4 style="text-align:left">  环境依赖 </h4>
~~~
"php": ">=5.4"
"ext-redis": ">=2.0"
~~~

## <h4>  Composer安装 </h4>

~~~
  composer require easy-queue/easy-queue
~~~

## <h5>【A】. 快速入门->生产者 </h5>
~~~
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
~~~

## <h5>【B】. 快速入门->消费者 </h5>
~~~
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