<?php
/**
 * 文件系统
 */
class Util_Common_Fs
{
    public static function mkdir($dir, $mode = 0755)
    {
        if (is_dir($dir)) {
            return true;
        }
        is_dir(dirname($dir)) || self::mkdir(dirname($dir), $mode);
        if (is_writable(dirname($dir))) {
            return mkdir($dir, $mode);
        } else {
            throw new Exception(dirname($dir) . " 不可以写入");
        }
    }
}