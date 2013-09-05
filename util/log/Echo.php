<?php
/**
 *
 */

class Util_Log_Echo extends Util_Log_Abstract
{
    protected function _handler($text)
    {
        echo $text;
    }
}