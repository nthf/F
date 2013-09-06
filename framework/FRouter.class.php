<?php
/**
 * 路由类
 * 
 * @name FRouter
 * @package Framework
 * @copyright @2012
 * @version 0.2 (2013-8-22 2:15:33)
 * @author <feiker.hong@gmail.com>
 */
class FRouter
{
    /**
     * @link getInstance()
     * @var FRouter
     */
    protected static $_instance = null;
    
    /**
     * 存放规则
     * 
     * @var array
     */
    protected $_rules = array();
    
    /**
     * 存放默认规则
     * 
     * @var array
     */
    protected $_defaultRule = array(
        'namespace' => 'Default',  // 默认目录命名
        'controller' => 'Index',   // 默认控制器名
        'action' => 'indexAction', // 默认方法命名
    );
    
    /**
     * @access protected
     */
    protected function __construct() {}
    
    /**
     * 单例模式实例化
     * 
     * @return FRouter
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
     * 添加规则
     * 
     * @param array $rules
     * @return object
     */
    public function addRules($rules)
    {
        $rules = (array) $rules;
        
        $this->_rules = $rules + $this->_rules;
        
        return $this;
    }
    
    /**
     * 匹配规则
     * 
     * @param string $pathInfo 当前访问路径信息
     * @return array
     */
    public function match($pathInfo)
    {
        $pathInfo = trim($pathInfo, '/');
        
        if ($pathInfo === '')
        {
            return $this->_defaultRule;
        }
        
        $matched = 0;
        $matches = array();
        
        foreach ($this->_rules as $regex => $rule)
        {
            $matched = preg_match($regex, $pathInfo, $matches);
            
            if ($matched === 1)
            {
                $params = array();
                
                foreach ($rule['maps'] as $offset => $name)
                {
                    if (isset($matches[$offset]) && $matches[$offset] !== '')
                    {
                        $params[$name] = urldecode($matches[$offset]);
                    }
                }
                
                if (isset($rule['defaults']))
                {
                    $params += $rule['defaults'];
                }
                
                F::reg('_params', $params);
                
                return $rule;
            }
        }
        
        return $this->_dynamicMatch($pathInfo);
    }
    
    /**
     * 动态的匹配
     * 
     * @param string $pathInfo 当前访问路径信息
     * @return array
     */
    protected function _dynamicMatch($pathInfo)
    {
        $matchInfo = array();
        
        $tmp = explode('/', $pathInfo);
        
        if (false !== ($namespace = current($tmp)))
        {
            $matchInfo['namespace'] = ucfirst($namespace);
        }
        else
        {
            $matchInfo['namespace'] = $this->_defaultRule['namespace'];
        }
        
        if (false !== ($controller = next($tmp)))
        {
        	$matchInfo['controller'] = ucfirst($controller);
        }
        else
        {
        	$matchInfo['controller'] = $this->_defaultRule['controller'];
        }
        
        if (false !== ($action = next($tmp)))
        {
        	$matchInfo['action'] = $action . 'Action';
        }
        else
        {
        	$matchInfo['action'] = $this->_defaultRule['action'];
        }
        
        $params = array();
        while (false !== ($next = next($tmp))) 
        {
        	$params[$next] = urldecode(next($tmp));
        }
        F::reg('_params', $params);
        
        return $matchInfo;
    }
    
}