<?php
/**
 * (F)框架类
 * 
 * @name F
 * @package Framework
 * @copyright @2012
 * @version 0.2 (2013-9-5 5:40:22)
 * @author <feiker.hong@gmail.com>
 */
defined('F_DIR') || define('F_DIR', dirname(__FILE__));

class F
{
    protected static $_instance = null;
    protected static $_config = array();
    protected static $_reg = array();
    
    protected $_pathInfo = null;
    protected $_dispatch = null;
    
    /**
     * 初始化
     * 
     * @access protected
     */
    protected function __construct()
    {
        self::$_config['_class'] = array(
            'FLoader' => F_DIR . '/FLoader.class.php',
            'FRouter' => F_DIR . '/FRouter.class.php',
            'FFactory' => F_DIR . '/FFactory.class.php',
            'FRequest' => F_DIR . '/FRequest.class.php',
            'FResponse' => F_DIR . '/FResponse.class.php',
            'FException' => F_DIR . '/FException.class.php',
        );
        
        self::registerAutoload();
    }
    
    /**
     * 引导启动
     *
     * @param array $config 自定义的配置
     * @return F
     */
    public function boot($config)
    {
        self::$_config += (array) $config;
        
    	return self::$_instance;
    }
    
    /**
     * 运行
     */
    public function run()
    {
        $this->dispatch();
        $this->process();
    }
    
    /**
     * 分派
     */
    public function dispatch()
    {
        $pathInfo = $this->getPathInfo();
        $router = FRouter::getInstance();
        
        if (isset(self::$_config['routes'])) 
        {
            $router->add(self::$_config['routes']);
        }
        
        $this->_dispatch = $router->match($pathInfo);
    }
    
    /**
     * 处理请求
     */
    protected function process()
    {
        extract($this->_dispatch);
        
        if (isset($namespace, $controller, $action))
        {
            $className = "Controller_{$namespace}_{$controller}";
            if (self::import($className))
            {
            	$class = new $className();
            	$func = array($class, $action);
            	if (is_callable($func, true))
            	{
            		call_user_func($func);
            		exit();
            	}
            }
        }
        
        $this->_onNotFound();
    }
    
    /**
     * 获取访问的路径信息
     * 
     * @return string
     */
    public function getPathInfo()
    {
        if (null === $this->_pathInfo)
        {
            $this->_pathInfo = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
        }
        
        return $this->_pathInfo;
    }
    
    /**
     * 错误处理
     */
    protected function _onNotFound()
    {
        $this->_dispatch = array(
            'namespace' => 'Default',
            'controller' => 'Error',
            'action' => 'indexAction',
        );
        
        $this->process();
        
        exit();
    }
    
    /**
     * 单例模式实例化
     *
     * @return F
     */
    public static function getInstance()
    {
    	if (null === self::$_instance)
    	{
    		self::$_instance = new self();
    	}
    
    	return self::$_instance;
    }
    
    /**
     * 获取配置
     * 
     * @return array
     */
    public static function getConfig()
    {
        return self::$_config;
    }
    
    /**
     * 注册变量
     *
     * @param string $name  变量名
     * @param mixed $value  变量值
     * @param mixed $default  默认值
     * @return void
     */
    public static function reg($name = null, $value = null, $default = null)
    {
        if (null === $name) 
        {
            return self::$_reg;
        }

        if (null === $value) 
        {
            return isset(self::$_reg[$name]) ? self::$_reg[$name] : $default;
        }

        self::$_reg[$name] = $value;
    }
    
    /**
     * 加载类文件
     * 
     * @param string $className  类的文件名
     * @param string $dir  类文件所在的目录
     * @param string $suffix  文件的后缀名
     * @return boolean
     */
    public static function import($className, $dir = '', $suffix = '.php')
    {
        // 若类或接口已定义, 则直接返回加载成功:true
        if (class_exists($className, false) || interface_exists($className, false)) 
        {
        	return true;
        }
        
        // 若是加载框架类, 则直接加载并返回加载成功:true
        if (isset(self::$_config['_class'][$className])) 
        {
        	include self::$_config['_class'][$className];
        	return true;
        }
        
        $dir = empty($dir) ? '' : rtrim($dir, '\\/') . DIRECTORY_SEPARATOR;
        $classFile = $dir . str_replace('_', DIRECTORY_SEPARATOR, $className) . $suffix;
        
        // 若是加载指定命名规则的类, 则先检查文件是否存在并返回加载成功:true
        if (file_exists($classFile)) 
        {
        	include $classFile;
        	return true;
        }
        
        return false;
    }
    
    /**
     * 注册自动加载
     * 
     * @param string $func 注册方法
     * @param boolean $enable 注册还是注稍
     * @return void
     */
    public static function registerAutoload($func = 'F::import', $enable = true)
    {
        $enable ? spl_autoload_register($func) : spl_autoload_unregister($func);
    }
    
}