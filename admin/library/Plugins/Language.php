<?php

/**
 * Plugin do obsługi wersji jezykowych
 */
class Plugins_Language extends Zend_Controller_Plugin_Abstract {

private $Language;
/**
 * Metoda uruchamia sie przed startem aplikacji po zainijalizowaniu tras , wyciaga wersje jezykowa z adresu
 * @author Arnold Sikorski
 * @param Zend_Controller_Request_Abstract $request
 */
    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request) {

    $params = $request->getParams();
    $lang = $params['language'];
    $session  = Zend_Registry::get('Session');

    if(isset($session->lang)){
        if($session->lang!=$lang){
            $session->lang = $lang;
        }else{

        }
    }else{
        $session->lang = $lang;
    }

    //file_exists($filename)

    $langFilePath = APPLICATION_PATH.'/lang/'.$lang.'.mo';
    //echo $langFilePath;
    if(file_exists($langFilePath)){
       // echo 'plik istnieje';
    }else{
        //echo 'plik nie istnieje';
        $request->setParam('language', 'pl');
        $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
        $redirector->gotoUrl('pl/test/index/');
    }
   // new var_dump($_SERVER["QUERY_STRING"]);
    //new var_dump($session->lang);
    }


}

?>
