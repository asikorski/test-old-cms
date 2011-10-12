<?php

/**
 * Plugin do obsługi wersji jezykowych
 */
class Plugins_Language extends Zend_Controller_Plugin_Abstract {

    /**
     * Metoda uruchamia sie przed startem aplikacji po zainijalizowaniu tras , wyciaga wersje jezykowa z adresu
     * @author Arnold Sikorski
     * @param Zend_Controller_Request_Abstract $request
     */
    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request) {
        $LangPath = APPLICATION_PATH . '/lang/';

        $locale = new Zend_Locale();
        $lang = $locale->getLanguage();
        $realPath = $LangPath . $lang . '.mo';

        if (file_exists($realPath)) {
            //echo "plik istnieje";
            $translate = new Zend_Translate('gettext', $realPath, $lang);
            $translate->setLocale($lang);
            Zend_Registry::set('Zend_Translate', $translate);
        } else {
            //echo "plik nie istnieje";
        }
        //echo $realPath;
       // die;

//        $params = $request->getParams();
//        if (empty($params['lang'])) {
//
//            $locale = new Zend_Locale();
//            $lang = $locale->getLanguage();
//            Zend_Registry::set('lang', $lang);
//            $sLang = $lang;
//            if($lang!='pl'){
//                $lang = 'en';
//            }
//           $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
//        $redirector->gotoUrl($lang.'/');
//        } else {
//            Zend_Registry::set('lang', $params['lang']);
//            $sLang = $params['lang'];
//        }
        //echo $sLang;die;

        /* wczytuje wersje jezykowa */
        // $translate = new Zend_Translate('gettext', APPLICATION_PATH . '/lang/' . $sLang . '.mo', $sLang);
        //$translate->setLocale($sLang);
        //Zend_Registry::set('Zend_Translate', $translate);
    }

}

?>