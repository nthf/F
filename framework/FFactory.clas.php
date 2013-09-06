<?php
/**
 * 工厂类
 * 
 * @name FFactory
 * @package Framework
 * @copyright @2012
 * @version 0.2 (2013-8-22 2:17:29)
 * @author <feiker.hong@gmail.com>
 */
class FFactory
{
    /**
     * 日志工厂
     * 
     * @param array $config
     * @return object
     */
    public static function log($config)
    {
        $config = array('adapter' => 'File', 'params' => array(),) + $config;
        
        $class = 'Util_Log_' . ucfirst($config['adapter']);
        
        return new $class($config['params']);
    }
    
    /**
     * 数据库工厂
     * 
     * @param array $config
     * @return object
     */
    public static function db($config)
    {
    	$config = array('masterslave' => false, 'adapter' => 'Mysqli', 'params' => array(),) + $config;
    
    	if (isset($config['masterslave']) && $config['masterslave']) {
    		return new Util_Db_Masterslave($config);
    	} else {
    		$class = 'Util_Db_' . ucfirst($config['adapter']);
    		return new $class($config['params']);
    	}
    }
    
    /**
     * 缓存工厂
     * 
     * @param array $config
     * @return object
     */
    public static function cache($config)
    {
    	$config = array('adapter' => 'Apc', 'params' => array(),) + $config;
    	 
    	$class = 'Util_Cache_' . ucfirst($config['adapter']);
    
    	return new $class($config['params']);
    }
    
    /**
     * 队列工厂
     * 
     * @param array $config
     * @return object
     */
    public static function queue($config)
    {
        $config = array('adapter' => 'Redis', 'params' => array(),) + $config;
        
        $class = 'Util_Queue_' . ucfirst($config['adapter']);
        
        return new $class($config['params']);
    }
    
}