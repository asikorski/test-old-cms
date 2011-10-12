<?php
/**
 * Plugin do obsługi raportowania błedów
 *
 * @Author: Arnold Sikorski
 */
class Plugins_DebbugerLogger extends Zend_Controller_Plugin_Abstract {

    public function dispatchLoopShutdown(){
        /*
         * Akcja wykonywana zakonczeniu wszystkich procesów
         */
        $LogDebug = Library_History::instans()->get();
//        echo "początek</br>";
//        $stream = fopen(APPLICATION_PATH.'/../../_log/'.HASH_CODE_CMS.'_'.time().'.log', 'a+', false);
//        if (! $stream) {
//    echo "problem kurwa";
//    echo HASH_CODE_CMS.'_'.time().'.log';
//
//}
//       var_dump($LogDebug);
      //  echo "koniec</br>";
    }
    
}
