<?php
/**
 * 助手类
 * 
 * @name 
 * @package Framework
 * @copyright @2012
 * @version 0.2 (2013-9-5 4:34:34)
 * @author <feiker.hong@gmail.com>
 */
class FHelper
{
    /**
     * 动态的获取助手类
     * 
     * @param string $key
     * @throws Exception
     * @return object
     */
    public function __get($key)
    {
        $className = 'Helper_' . ucfirst($key);
        
        if (class_exists($className, true))
        {
            return $this->key = new $className();
        }
        else
        {
            throw new Exception("没有找到助手“$key”");
        }
    }
    
}