<?php
/**
 *
 */

class Util_Log_File extends Util_Log_Abstract
{
    protected function _handler($text)
    {
        $dir = dirname($this->_options['file']);
        is_dir($dir) || Util_Common_Fs::mkdir($dir, $this->_options['mode']);
        return file_put_contents($this->_options['file'], $text . "\n", FILE_APPEND | LOCK_EX);
    }
}