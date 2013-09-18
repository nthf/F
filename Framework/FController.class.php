<?php
/**
 * 控制器类
 * 
 * @name FController
 * @package Framework
 * @copyright @2012
 * @version 0.2 (2013-9-6 4:56:17)
 * @author <feiker.hong@gmail.com>
 */
defined('DS') || define('DS', DIRECTORY_SEPARATOR);

abstract class FController
{
	/**
	 * 请求对象
	 * 
	 * @var object FRequest
	 */
	protected $_request = null;
	
	/**
	 * 响应对象
	 * 
	 * @var object FResponse
	 */
	protected $_response = null;
	
	/**
	 * 视图对象
	 * 
	 * @var object FView
	 */
	public $view = null;
	
	/**
	 * 视图文件的后缀名
	 * 
	 * @var string
	 */
	protected $_viewExt = '.php';
	
	/**
	 * 视图文件的存放目录
	 *
	 * @var object
	 */
	protected $_viewPath = 'views';
	
	/**
	 * 构造函数
	 */
    public function __construct()
    {
    	$this->_request = new FRequest();
    	$this->_response = new FResponse();
    }
    
    /**
     * 获取默认的模板路径
     * 
     * @return string
     */
    protected function getDefaultTemplate()
    {
    	$core = F::getInstance();
    	$config = $core::getConfig();
    	$dispatch = $core->getDispatch();
    	
    	$script = isset($config['_viewPath']) ? $config['_viewPath'] : $this->_viewPath;
    	
    	$tpl = $script . DS
    	     . $dispatch['namespace'] . DS 
    	     . $dispatch['controller'] . DS 
    	     . $dispatch['action'] 
    	     . $this->_viewExt;
    	
    	return $tpl;
    }
    
    /**
     * 获取格式化后完整的模板路径
     * 
     * @param string $tpl
     * @return string
     */
    protected function getFormattedTemplate($tpl)
    {
    	if ($tpl[0] === '\\') {
    		$tpl = ltrim($tpl, '\\');
    	} else {
    		$core = F::getInstance();
    		$config = $core::getConfig();
    		$dispatch = $core->getDispatch();
    		
    		$script = isset($config['_viewPath']) ? $config['_viewPath'] : $this->_viewPath;
    		
    		$tpl = trim($tpl, '/');
    		$tmp = explode('/', $tpl);
			switch (count($tmp)) {
				case 2: $tpl = $script . DS . $tpl; break;
				case 1: $tpl = $script . DS . $dispatch['namespace'] . DS . $tpl; break;
				case 0: $tpl = $script . DS . $dispatch['namespace'] . DS . $dispatch['controller'] . DS . $tpl; break;
			}
    	}
    	
    	return $tpl;
    }
    
    /**
     * 渲染模板
     *
     * @param string $tpl  模板名或模板路径
     * @param boolean $partial  是否是局部的
     * @return string
     */
    public function render($tpl = null, $return = false)
    {
    	if (!$tpl) {
    		$tpl = $this->getDefaultTemplate();
    	} else {
    		$tpl = $this->getFormattedTemplate($tpl);
    	}
    	
    	$output = $this->view->render($tpl);
    	if ($return) {
    		return $output;
    	} else {
    		echo $output;
    	}
    }
    
    /**
     * 重定向页面, 改变URL
     * 
     * @param string $url  浏览器地址
     * @return void
     */
    protected function _redirect($url)
    {
    	$this->_response->redirect($url);
    }
    
    /**
     * 转向页面, 不改变URL
     * 
     * @param string $namespace  目录命名
     * @param string $controller  控制器名
     * @param string $action  方法名
     * @param array $params  参数
     * @return void
     */
    final protected function _forward($namespace = null, $controller = null, $action = null, $params = array())
    {
    	$core = F::getInstance();
    	$dispatch = $core->getDispatch();
    	
    	if (null !== $namespace) {
    		$dispatch['namespace'] = $namespace;
    	}
    	
    	if (null === $controller) {
    		$dispatch['controller'] = $controller;
    	}
    	
    	$core->setDispatch($dispatch, $params);
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
    		case 'view':
    			return $this->view = new FView();
    		case '_request':
    			return $this->_request = new FRequest();
    		case '_response':
    			return $this->_response = new FResponse();
    		default:
    			return null;
    	}
    }
    
}