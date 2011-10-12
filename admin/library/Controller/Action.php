<?php
/*
 * Klasa dziedziczy po kontrolerze akacji
 */
class Controller_Action extends Zend_Controller_Action{
    public $oUser;
    public $ModuleName;
    public $request;
    public $oView;
    public $registerModule;
        //Tu znajduje sie nazwa aktualnie zalogowanego usera
    public function  init() {
        /*W klasie przechowywane sa wszystkie dane dotyczace aktualnej akcji*/
        $this->oUser = Zend_Registry::get('oUser');
        $this->request  = Zend_Controller_Front::getInstance()->getRequest();
        //$this->oView = new Zend_View;
        $this->ModuleName = $this->request->getModuleName() ;
        $this->view->controller = $this->request->getControllerName() ;
        $rModule = Zend_Registry::get('modulename');
        $this->registerModule = $rModule;
        $this->view->registerModule =$rModule;

        /*Ustawienie nazw Tabel*/

    }
}