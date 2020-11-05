<?php
namespace EasyDelayQueue;

use Redis;

class DelayQueue
{
    /**
     * name
     * @var string|null
     */
    protected $name = null;

    /**
     * Redis_Handler
     * @var Redis|null
     */
    protected $handler = null;

    /**
     * DelayQueue constructor.
     * @param $name
     * @param $handler
     */
    public function __construct($name, $handler)
    {
        $this->name = $name;
        $this->handler = $handler;
    }

    /**
     * add
     * @param int $score
     * @param string $value
     * @return int
     */
    public function add($score, $value)
    {
        return $this->handler->zAdd($this->name, $score, $value);
    }

    /**
     * get
     * @param int $e_score
     * @param int $s_score
     * @param int $limit
     * @param bool $remove
     * @return array
     */
    public function get($e_score, $s_score = 0, $limit = 10, $remove = true)
    {
        $list = $this->handler->zRangeByScore($this->name, $s_score, $e_score, ['limit' => [0, $limit]]);
        if ($remove)
        {
            foreach ($list as $key => $value)
            {
                if (!static::del($value)) unset($list[$key]);
            }
        }
        return $list;
    }

    /**
     * del
     * @param $value
     * @return int
     */
    public function del($value)
    {
        return $this->handler->zRem($this->name, $value);
    }
}






