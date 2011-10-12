<?php

/**
 * Plugin do obsługi wersji jezykowych
 */
class Plugins_Requests extends Zend_Controller_Plugin_Abstract {

private $Language;
/**
 * Plugin zapewnia kontrole wyswietlanych błędów
 * @author Arnold Sikorski
 * @param Zend_Controller_Request_Abstract $request
 */
    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request) {
        // $this->getResponse()->setHttpResponseCode(404);
       $errors = $request->getParam('error_handler');
        //print_r($errors);
       //die('kurwen');

    }


}

?>
