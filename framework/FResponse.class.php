<?php
/**
 * 响应类
 * 
 * @name FResponse
 * @package framework
 * @copyright @2012
 * @version 0.2 (2013-8-22 2:10:52)
 * @author <feiker.hong@gmail.com>
 */
class FResponse
{
    /**
     * 状态码的返回内容
     * 
     * @return array
     */
    protected static $statusTexts = array(
        '100' => 'Continue',
        '101' => 'Switching Protocols',
        '200' => 'OK',
        '201' => 'Created',
        '202' => 'Accepted',
        '203' => 'Non-Authoritative Information',
        '204' => 'No Content',
        '205' => 'Reset Content',
        '206' => 'Partial Content',
        '300' => 'Multiple Choices',
        '301' => 'Moved Permanently',
        '302' => 'Found',
        '303' => 'See Other',
        '304' => 'Not Modified',
        '305' => 'Use Proxy',
        '306' => '(Unused)',
        '307' => 'Temporary Redirect',
        '400' => 'Bad Request',
        '401' => 'Unauthorized',
        '402' => 'Payment Required',
        '403' => 'Forbidden',
        '404' => 'Not Found',
        '405' => 'Method Not Allowed',
        '406' => 'Not Acceptable',
        '407' => 'Proxy Authentication Required',
        '408' => 'Request Timeout',
        '409' => 'Conflict',
        '410' => 'Gone',
        '411' => 'Length Required',
        '412' => 'Precondition Failed',
        '413' => 'Request Entity Too Large',
        '414' => 'Request-URI Too Long',
        '415' => 'Unsupported Media Type',
        '416' => 'Requested Range Not Satisfiable',
        '417' => 'Expectation Failed',
        '500' => 'Internal Server Error',
        '501' => 'Not Implemented',
        '502' => 'Bad Gateway',
        '503' => 'Service Unavailable',
        '504' => 'Gateway Timeout',
        '505' => 'HTTP Version Not Supported',
    );

    /**
    * 设置Status
    *
    * @param string $code HTTP status code
    * @param string $name HTTP status text
    * @return void
    */
    public static function setStatus($code, $text = null)
    {
        $protocol = isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.1';
        $text = (null === $text) ? self::$statusTexts[$code] : $text;
        header("$protocol $code $text");
    }

    /**
     * 设置Charset
     *
     * @param string $enc
     * @param string $type
     * @return void
     */
    public function setCharset($enc = 'UTF-8', $type = 'text/html')
    {
        header("Content-Type:$type;charset=$enc");
    }
    
    /**
     * 设置Expires
     *
     * @param int $seconds
     * @return void
     */
    public function setExpires($seconds = 1800)
    {
    	$time = date('D, d M Y H:i:s', time() + $seconds) . ' GMT';
    	
    	header("Expires: $time");
    }
    
    /**
     * 设置Cookie
     *
     * @param string $name
     * @param string $value
     * @param int $expire
     * @param string $path
     * @param string $domain
     * @param string $secure
     * @param boolean $httpOnly
     * @return boolean
     */
    public function setCookie($name, $value = null, $expire = null, $path = null, $domain = null, $secure = false, $httpOnly = false)
    {
    	return setcookie($name, $value, $expire, $path, $domain, $secure, $httpOnly);
    }

    /**
     * 设置无缓存
     *
     * @return void
     */
    public function setNoCache()
    {
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
    }

    /**
     * 重定向Redirect
     *
     * @param string $url 
     * @param integer $code
     * @return void
     */
    public function redirect($url, $code = 302)
    {
    	header("Location:$url", true, $code);
    	exit();
    }
    
}