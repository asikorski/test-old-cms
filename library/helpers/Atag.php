<?php
/**
 * @author Arnold Sikorski
 * Helper generujacy tag a
 */
class Global_View_Helper_Atag extends Zend_View_Helper_Abstract
{

    /**
     * Constructor
     */
    public function __construct()
    {

    }

    /**
     * Output the <img /> tag
     *
     * @param string $path
     * @param array $params
     * @return string
     */
    public function Atag($href, $string,$params = array())
    {
        $plist = array();
        $paramstr = null;

        foreach ($params as $param => $value) {
            $plist[] = $param . '="' . $this->view->escape($value) . '"';
        }
        $paramstr = ' ' . join(' ', $plist);
        
        return '<a href="' .$href.'"' . $paramstr . '>'.$string.'</a>';

    }
}