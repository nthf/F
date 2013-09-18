<?php
/**
 * 测试执行类
 */
class Util_Common_Benchmark
{
    protected $_time = array();

    public function __construct()
    {
        $this->start();
    }

    public function start($mark = 'start')
    {
        $this->_time = array();
        return $this->mark($mark);
    }

    public function end($mark = 'end')
    {
        return $this->mark($mark);
    }

    public function mark($name = null)
    {
        if (is_null($name)) {
            return $this->_time[] = microtime(true);
        } else {
            return $this->_time[$name] = microtime(true);
        }
    }

    public function cost($p1 = 'start', $p2 = 'end', $decimals = 4)
    {
        $t1 = (empty($this->_time[$p1])) ? $this->mark($p1) : $this->_time[$p1];
        $t2 = (empty($this->_time[$p2])) ? $this->mark($p2) : $this->_time[$p2];

        return abs(number_format($t2 - $t1, $decimals));
    }

    public function step($decimals = 4)
    {
        $t1 = end($this->_time);
        $t2 = $this->mark();
        return number_format($t2 - $t1, $decimals);
    }

    public function time()
    {
        return $this->_time;
    }

    /**
     * 获取使用的内存空间
     *
     * @param boolean $flag
     * @return int
     */
    public function memory($flag = false)
    {
        return memory_get_usage($flag);
    }
}