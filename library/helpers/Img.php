<?php
/**
 * Helper generujacy
 */
class Global_View_Helper_Img extends Zend_View_Helper_Abstract
{
    protected $_baseurl = null;
    protected $_exists = array();
    private $imgDate ='data:image/gif;base64,R0lGODlhFAAUAIAAAAAAAP///yH5BAAAAAAALAAAAAAUABQAAAI5jI+pywv4DJiMyovTi1srHnTQd1BRSaKh6rHT2cTyHJqnVcPcDWZgJ0oBV7sb5jc6KldHUytHi0oLADs=';

    /**
     * Constructor
     */
    public function __construct()
    {
        $url = Zend_Controller_Front::getInstance()->getRequest()->getBaseUrl();
        $root = '/' . trim($url, '/');
        if ('/' == $root) {
            $root = '';
        }
        $this->_baseurl = $root . '/';
    }

    /**
     * Output the <img /> tag
     *
     * @param string $path
     * @param array $params
     * @return string
     */
    public function img($path,$base=false, $params = array())
    {
        $plist = array();
        $paramstr = null;
        $imagepath = $this->_baseurl . ltrim($path, '/');
        if (!isset($this->_exists[$path])) {
            $this->_exists[$path] = file_exists(realpath($_SERVER['DOCUMENT_ROOT'] . '/' . $imagepath));
        }
        if (!isset($params['alt'])) {
            $params['alt'] = '';
        }
        foreach ($params as $param => $value) {
            $plist[] = $param . '="' . $this->view->escape($value) . '"';
        }
        $paramstr = ' ' . join(' ', $plist);
        if(!$base){
        return '<img src="' .
                (($this->_exists[$path])
                    ? $this->_baseurl . ltrim($path, '/')
                    : $this->imgDate) .
                '"' . $paramstr . ' />';
        }else{
            return '<img src="' .
                (($path)
                    ? $path
                    : $this->imgDate) .
                '"' . $paramstr . ' />';
        }
    }
}