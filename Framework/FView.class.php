<?php
/**
 * (F)视图类
 * 
 * @name FView
 * @package Framework
 * @copyright @2012
 * @version 0.2 (2013-9-6 16:40:28)
 * @author <feiker.hong@gmail.com>
 */
class FView
{
	/**
	 * 渲染模板
	 * 
	 * @param string $tpl
	 * @param boolean $return
	 * @return string
	 */
    public function render($tpl, $return = false)
    {
    	ob_start();
    	ob_implicit_flush(0);
    	include $tpl;
    	return ob_get_clean();
    }
    
    /**
     * 动态的设置变量
     * 
     * @param string $key
     * @param mixed $value
     */
    public function __set($key, $value) 
    {
    	$this->$key = $value;
    }
    
    /**
     * 动态的获取变量
     * 
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
    	switch ($key) {
    		case 'config':
    			return $this->config = F::getConfig();
    		default:
    			return null;
    	}
    }
}