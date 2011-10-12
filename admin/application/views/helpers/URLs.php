<?php
/**
 * @author: Arnold Sikorski
 * 
 * Helper pomocny przy generowaniu przyjaznego adresu url
 */

class Global_Helpers_URLs {

    public function URLs($lang,array $url = array()) {
        $url = $this->url($url);

        return $lang.'/'.$url;
        
     
    }
        private function url(array $urlOptions = array(), $name = null, $reset = false, $encode = true)
    {
        $router = Zend_Controller_Front::getInstance()->getRouter();
        return $router->assemble($urlOptions, $name, $reset, $encode);
    }

}
