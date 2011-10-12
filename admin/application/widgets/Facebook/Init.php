<?php
/* @Author: Arnold Sikorski
 * @Date: 11-05-2011
 *
 * Widget wyswietlajacy informacje, zasada działania jest bardzo prosta, widget to prosty mini moduł
 * który składa sie z prostego kontrolera oraz widoku,
 *
 */
class Widgets_Facebook_Init {
    public $oView;
    public $oConfig;
    public function   __construct() {
        $ScriptPath=realpath(dirname(__FILE__));
            //okreslamy scierzke dostepu do widoków
        //Ustawiam zend view
        $this->oView=new Zend_View();
            //inicjalizujemy klase widoków
        $this->oView->setScriptPath($ScriptPath.'/view');
            //ustawiamy scierzke do widoku
       //$test = new Zend_Config_Ini($ScriptPath.'/config.ini', 'df');
        //$this->oView->test = 'dupa';
        $this->oView->id = substr(trim(md5(uniqid(rand(), true))), 0, 6) ;
        return true;
    }
    /*
     * Wyswietlanie Kontentu widgetu
     */
    public function render(){
        return $this->oView->render('facebook.phtml');
    }
}
