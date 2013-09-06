<?php
/**
 * 请求类
 * 
 * @name FRequest
 * @package Framework
 * @copyright @2012
 * @version 0.2 (2013-8-22 2:10:43)
 * @author <feiker.hong@gmail.com>
 * @since 0.1
 */
class FRequest
{
    private $_baseUrl = null;
    private $_scriptUrl = null;
    private $_currentUrl = null;
    private $_requestUri = null;
    private $_pathInfo = null;
    
    /**
     * 获得 GET 数据
     *
     * 从 $_GET 中获得指定参数，如果参数不存在则返回指定的默认值。
     * 
     * 如果 $name 参数为 null，则返回整个 $_GET 的内容。
     * 
     * @param string $name 要GET的参数名
     * @param mixed $default GET参数不存在时要返回的默认值
     * @return mixed 参数值
     */
    public function getQuery($name = null, $default = null)
    {
        if (null === $name) 
        {
            return $_GET;
        }
        
        return isset($_GET[$name]) ? $_GET[$name] : $default;
    }
    
    /**
     * 获得 POST 数据
     *
     * 从 $_POST 中获得指定参数，如果参数不存在则返回指定的默认值。
     * 
     * 如果 $name 参数为 null，则返回整个 $_POST 的内容。
     *
     * @param string $name 要POST的参数名
     * @param mixed $default POST参数不存在时要返回的默认值
     * @return mixed 参数值
     */
    public function getPost($name = null, $default = null)
    {
        if (null === $name) 
        {
            return $_POST;
        }
        
        return isset($_POST[$name]) ? $_POST[$name] : $default;
    }
    
    /**
     * 获取 REQUEST 数据
     * 
     * 从 $_REQUEST 中获得指定参数，如果参数不存在则返回指定的默认值。
     * 
     * 如果 $name 参数为 null，则返回整个 $_REQUEST 的内容。
     * 
     * @param string $name 要REQUEST的参数名
     * @param mixed $default REQUEST参数不存在时要返回的默认值
     * @return mixed 参数值
     */
    public function getParam($name = null, $default = null)
    {
        if (null === $name)
        {
            return $_REQUEST;
        }
        
        return isset($_GET[$name]) ? $_GET[$name] : (isset($_POST[$name]) ? $_POST[$name] : $default);
    }
    
    /**
     * 获取 SERVER 数据
     *
     * 从 $_SERVER 中获得指定参数，如果参数不存在则返回指定的默认值。
     *
     * 如果 $name 参数为 null，则返回整个 $_SERVER 的内容。
     *
     * @param string $name 要查询的参数名
     * @param mixed $default 参数不存在时要返回的默认值
     * @return mixed 参数值
     */
    public function getServer($name = null, $default = null)
    {
        if (null === $name)
        {
            return $_SERVER;
        }
        
        return isset($_SERVER[$name]) ? $_SERVER[$name] : $default;
    }
    
    /**
     * 获取 ENV 数据
     * 
     * 从 $_ENV 中获得指定参数，如果参数不存在则返回指定的默认值。
     *
     * 如果 $name 参数为 null，则返回整个 $_SERVER 的内容。
     *
     * @param string $name 要查询的参数名
     * @param mixed $default 参数不存在时要返回的默认值
     * @return mixed 参数值
     */
    public function getEnv($name = null, $default = null)
    {
        if (null === $name)
        {
            return $_ENV;
        }
        
        return isset($_ENV[$name]) ? $_ENV[$name] : $default;
    }
    
    /**
     * 获取 COOKIE 客户端数据
     * 
     * 从 $_COOKIE 中获得指定参数，如果参数不存在则返回指定的默认值。
     * 
     * 如果 $name 参数为 null，则返回整个 $_COOKIE 的内容。
     * 
     * @param string $name 要查询的参数名
     * @param mixed $default 参数不存在时要返回的默认值
     * @return mixed 参数值
     * @since 0.2
     */
    public function getCookie($name = null, $default = null)
    {
        if (null === $name)
        {
            return $_COOKIE;
        }
        
        return isset($_COOKIE[$name]) ? $_COOKIE[$name] : $default;
    }
    
    /**
     * 获取 SESSION 服务端数据
     * 
     * 从 $_SESSION 中获得指定参数，如果参数不存在则返回指定的默认值。
     * 
     * 如果 $name 参数为 null，则返回整个 $_SESSION 的内容。
     * 
     * @param string $name 要查询的参数名
     * @param mixed $default 参数不存在时要返回的默认值
     * @return mixed 参数值
     * @since 0.2
     */
    public function getSession($name = null, $default = null)
    {
        isset($_SESSION) || session_start();
        
        if (null === $name)
        {
            return $_SESSION;
        }
        
        return isset($_SESSION[$name]) ? $_SESSION[$name] : $default;
    }
    
    /**
     * 获取 POST 的原始数据
     *
     * @return mixed (string|false)
     * @since 0.2
     */
    public function getRawBody()
    {
    	return file_get_contents('php://input');
    }
    
    /**
     * 获取 客户端IP地址
     * 
     * @return string
     * @since 0.2
     */
    public function getClientIp()
    {
        if (getenv('HTTP_CLIENT_IP'))
        {
            $ip = getenv('HTTP_CLIENT_IP');
        }
        elseif (getenv('HTTP_X_FORWARDED_FOR'))
        {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        }
        elseif (getenv('REMOTE_ADDR'))
        {
            $ip = getenv('REMOTE_ADDR');
        }
        elseif (isset($_SERVER['REMOTE_ADDR']))
        {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        else
        {
            $ip = 'unknown';
        }
        
        return ($ip);
    }
    
    /**
     * 获取当前URL的脚本文件地址
     * 
     * @return string
     */
    public function getScriptUrl()
    {
    	if (null === $this->_scriptUrl)
    	{
            $scriptName = basename($_SERVER['SCRIPT_FILENAME']);
            
            if ($scriptName === basename($_SERVER['SCRIPT_NAME']))
            {
                $this->_scriptUrl = $_SERVER['SCRIPT_NAME'];
            }
            elseif ($scriptName === basename($_SERVER['PHP_SELF']))
            {
                $this->_scriptUrl = $_SERVER['PHP_SELF'];
            }
            elseif (isset($_SERVER['ORIG_SCRIPT_NAME']) && $scriptName === basename($_SERVER['ORIG_SCRIPT_NAME']))
            {
                $this->_scriptUrl = $_SERVER['ORIG_SCRIPT_NAME'];
            }
            elseif (false !== ($pos = strpos($_SERVER['PHP_SELF'], '/' . $scriptName)))
            {
                $this->_scriptUrl = substr($_SERVER['SCRIPT_NAME'], 0, $pos) . '/' . $scriptName;
            }
            elseif (isset($_SERVER['DOCUMENT_ROOT']) && 0 === strpos($_SERVER['SCRIPT_FILENAME'],$_SERVER['DOCUMENT_ROOT']))
            {
                $this->_scriptUrl = str_replace('\\', '/', str_replace($_SERVER['DOCUMENT_ROOT'], '', $_SERVER['SCRIPT_FILENAME']));
            }
            else
            {
                $this->_scriptUrl = '';
            }
    	}
    	
    	return $this->_scriptUrl;
    }
    
    /**
     * 返回不包含任何查询参数的地址(但包含脚本名称)
     * 
     * @return string
     */
    public function getBaseUrl()
    {
        if (null === $this->_baseUrl)
        {
            $this->_baseUrl = rtrim(dirname($this->getScriptUrl()), '\\/');
        }
        
        return $this->_baseUrl;
    }
    
    /**
     * 获取当前请求的 PATHINFO
     * 
     * @return string
     */
    public function getPathInfo()
    {
    	if (null === $this->_pathInfo)
    	{
            if (!empty($_SERVER['PATH_INFO']))
            {
                return $this->_pathInfo = $_SERVER['PATH_INFO'];
            }
            
            $requestUri = $this->getRequestUri();
            
            if (empty($requestUri))
            {
                return $this->_pathInfo = '';
            }
            
            if (false !== ($pos = strpos($requestUri, '?')))
            {
                $requestUri = substr($requestUri, 0, $pos);
            }
            
            $baseUrl = $this->getBaseUrl();
            
            if ($baseUrl !== '')
            {
                $this->_pathInfo = substr($requestUri, strlen($baseUrl));
            }
            else
            {
                $this->_pathInfo = $requestUri;
            }
    	}
    	
    	return $this->_pathInfo;
    }
    
    /**
     * 获取当前请求的 URI
     *
     * @return string
     */
    public function getRequestUri()
    {
    	if (null === $this->_requestUri)
    	{
            // IIS
            if (isset($_SERVER['HTTP_X_REWRITE_URL']))
            {
            	$this->_requestUri = $_SERVER['HTTP_X_REWRITE_URL'];
            }
            elseif (isset($_SERVER['REQUEST_URI']))
            {
            	$this->_requestUri = $_SERVER['REQUEST_URI'];
            
            	if (!empty($_SERVER['HTTP_HOST']))
            	{
                    if (false !== strpos($this->_requestUri, $_SERVER['HTTP_HOST']))
                    {
                        $this->_requestUri = preg_replace('/^\w+:\/\/[^\/]+/', '', $this->_requestUri);
                    }
            	}
            	else
            	{
                    $this->_requestUri = preg_replace('/^(http|https):\/\/[^\/]+/i', '', $this->_requestUri);
            	}
            }
            // IIS 5.0 CGI
            elseif (isset($_SERVER['ORIG_PATH_INFO']))
            {
            	$this->_requestUri = $_SERVER['ORIG_PATH_INFO'];
            
            	if (!empty($_SERVER['QUERY_STRING']))
            	{
                    $this->_requestUri .= '?' . $_SERVER['QUERY_STRING'];
            	}
            }
            // 获取不到
            else
            {
            	$this->_requestUri = '';
            }
    	}
    
    	return $this->_requestUri;
    }
    
    /**
     * 获取当前的浏览器地址
     * 
     * @return string
     */
    public function getCurrentUrl()
    {
        if (null === $this->_currentUrl)
        {
            $url = 'http';
            
            if ('on' == $_SERVER['HTTPS']) 
            {
                $url .= 's';
            }
            
            $url .= '://' . $_SERVER['SERVER_NAME'];
            
            $port = $_SERVER['SERVER_PORT'];
            if (80 != $port)
            {
                $url .= ":{$port}";
            }
            
            $this->_currentUrl = $url . $_SERVER['REQUEST_URI'];
        }
        
        return $this->_currentUrl;
    }
    
    /**
     * 判断是否是 GET 请求
     * 
     * @return boolean
     */
    public function isGet()
    {
        return isset($_SERVER['REQUEST_METHOD']) && strcasecmp($_SERVER['REQUEST_METHOD'], 'GET') === 0;
    }
    
    /**
     * 判断是否是 POST 请求
     * 
     * @return boolean
     */
    public function isPost()
    {
        return isset($_SERVER['REQUEST_METHOD']) && strcasecmp($_SERVER['REQUEST_METHOD'], 'POST') === 0;
    }

    /**
     * 判断是否是 AJAX(XMLHttpRequest)请求
     * 
     * @return boolean
     */
    public function isAjax()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }
    
    /**
     * 判断 HTTP 请求是否是通过 Flash 发起的
     * 
     * @return boolean
     */
    public function isFlash()
    {
        return isset($_SERVER['HTTP_USER_AGENT']) && (stripos($_SERVER['HTTP_USER_AGENT'], 'Shockwave') !== false || stripos($_SERVER['HTTP_USER_AGENT'], 'Flash') !== false);
    }
    
    /**
     * 判断是否是 HTTPS 安全请求
     * 
     * @return boolean
     */
    public function isSecure()
    {
        return isset($_SERVER['HTTPS']) && strcasecmp($_SERVER['HTTPS'], 'on') === 0;
    }
    
}