<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of init
 *
 * @author piotrek
 */
class widgets_ads_init {
    public $oView;

    public function __construct() {
        $ScriptPath = realpath(dirname(__FILE__));
        //Ustawiam zend view
        $this->oView = new Zend_View();
        //inicjalizujemy klase widoków
        $this->oView->setScriptPath($ScriptPath . '/view');
    }
    
    public function renderAdRightTop(){
        return $this->oView->render('adRightTop.phtml');
    }
}

?>
