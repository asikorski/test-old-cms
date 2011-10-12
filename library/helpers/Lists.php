<?php

/**
 * @author Arnold Sikorski
 * Helper generujacy menu ul>li
 */
class Global_View_Helper_Lists extends Zend_View_Helper_Abstract {

    protected $tag = array('ul', 'li');

    /**
     * Constructor
     */
    public function __construct() {

    }

    /**
     * Output the <img /> tag
     *
     * @param string $path
     * @param array $params
     * @return string
     */
    public function Lists($params = array(), $childs = array()) {
        $listsItems = null;
        $ulHeader = null;

        /* generowanie nagłówka */

        $plist = array();
        $paramstr = null;

        foreach ($params as $param => $value) {
            $plist[] = $param . '="' . $this->view->escape($value) . '"';
        }
        $paramstr = ' ' . join(' ', $plist);

        return '<a href="' . $href . '"' . $paramstr . '>' . $string . '</a>';
    }

    protected function generateTag($tag,$params = array()) {
        
    }

}