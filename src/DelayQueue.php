<?php
namespace EasyDelayQueue;

use Redis;

class DelayQueue
{
    /**
     * name
     * @var string|null
     */
    public static $name = null;

    /**
     * Redis_Handler
     * @var Redis|null
     */
    public static $handler = null;

    /**
     * add
     * @param int $score
     * @param string $value
     * @return int
     */
    public static function add($score, $value)
    {
        return static::$handler->zAdd(static::$name, $score, $value);
    }

    /**
     * get
     * @param int $e_score
     * @param int $s_score
     * @param int $limit
     * @param bool $remove
     * @return array
     */
    public static function get($e_score, $s_score = 0, $limit = 10, $remove = true)
    {
        $list = static::$handler->zRangeByScore(static::$name, $s_score, $e_score, ['limit' => [0, $limit]]);
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
    public static function del($value)
    {
        return static::$handler->zRem(static::$name, $value);
    }
}






