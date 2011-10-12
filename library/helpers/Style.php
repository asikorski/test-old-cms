<?php
/**
 * Helper generujacy style na podstawie tablicy
 */
class Global_View_Helper_Style extends Zend_View_Helper_Abstract
{

    public function __construct()
    {

    }

    public function style($params = array())
    {
        $plist = array();
        $paramstr = null;

        foreach ($params as $param => $value) {
            $plist[] = $param . ':' . $this->view->escape($value) . ';';
        }
        $paramstr = ' ' . join(' ', $plist);
        return $paramstr;
    }
}