<?php
/**
 * @author: Arnold Sikorski
 * 
 * Helper pomocny przy generowaniu przyjaznego adresu url
 */

class Global_Helpers_ParmsURL {

    public function ParmsURL(array $url = array(), array $parms = array()) {
       $request = Zend_Controller_Front::getInstance()->getRequest()->getQuery();
        $FinalParms = array_merge($request, $parms);
        if (!empty($FinalParms)) {
            foreach ($FinalParms as $key => $value) {
                $values[] = $key . '=' . $value;
            }
        }
        $url = $this->url($url);
        $values = implode('&', $values);
        //$values = urlencode(implode('&', $values));
        //Z niewiadomej przyczyny niedziała, moze sprawiać problemy w przyszłosci
        return $url.'?'.$values;
        
     
    }
        private function url(array $urlOptions = array(), $name = null, $reset = false, $encode = true)
    {
        $router = Zend_Controller_Front::getInstance()->getRouter();
        return $router->assemble($urlOptions, $name, $reset, $encode);
    }

}
