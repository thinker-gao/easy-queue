<?php
namespace EasyQueue;

use Redis;

class Queue
{
    /**
     * Redis_Handler
     * @var Redis|null
     */
    protected $handler = null;

    /**
     * constructor.
     * @param $handler
     */
    public function __construct($handler)
    {
        $this->handler = $handler;
    }

    /**
     * add
     * @param string $name
     * @param string $value
     * @param int $time
     * @return int
     */
    public function add($name, $value, $time = 0)
    {
        if (!$time) $time = time();
        return $this->handler->zAdd($name, $time, $value);
    }

    /**
     * get
     * @param string $name
     * @param int $e_time
     * @param int $s_time
     * @param int $limit
     * @param bool $remove
     * @return array
     */
    public function get($name, $e_time = 0, $s_time = 0, $limit = 1, $remove = true)
    {
        if (!$e_time) $e_time = time();
        $list = $this->handler->zRangeByScore($name, $s_time, $e_time, ['limit' => [0, $limit]]);
        if ($remove)
        {
            foreach ($list as $key => $value)
            {
                if (!$this->handler->zRem($name, $value)) unset($list[$key]);
            }
        }
        return count($list) == 1 ? $list['0'] : $list;
    }

    /**
     * @return string
     */
    protected function valueIdentify()
    {
        static $i = 0;
        $i or $i = mt_rand(1, 0x7FFFFF);
        return sprintf("%08x%06x%04x%06x",
            time() & 0xFFFFFFFF,
            crc32(substr((string)gethostname(), 0, 256)) >> 8 & 0xFFFFFF,
            getmypid() & 0xFFFF,
            $i = $i > 0xFFFFFE ? 1 : $i + 1
        );
    }

    /**
     * push
     * @param string $name
     * @param string $value
     * @param boolean $left
     * @return bool|int
     */
    public function push($name, $value, $left = true)
    {
        $func = $left ? 'lPush' : 'rPush';
        return call_user_func([$this->handler, $func], $name, $value);
    }

    /**
     * pull
     * @param string $name
     * @param boolean $right
     * @return bool|mixed
     */
    public function pull($name, $right = true)
    {
        $func = $right ? 'rPop' : 'lPop';
        return call_user_func([$this->handler, $func], $name);
    }
}






