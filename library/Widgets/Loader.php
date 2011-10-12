<?php

/**
 * @author Arnold Sikorski
 */
class Widgets_Loader {

    /**
     *
     * @param type $ScriptPath Sciezka 
     */
    public function __construct($ScriptPath) {
        //Ustawiam zend view
        $this->oView = new Zend_View();
        //inicjalizujemy klase widoków
        $this->oView->setScriptPath($ScriptPath . '/view');
        $this->oView->addHelperPath(APPLICATION_PATH . "/../../library/Helpers", 'Global_View_Helper');
    }

}

?>
