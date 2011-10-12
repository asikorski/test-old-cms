<?php

/**
 * @author Arnold Sikorski
 */
class console_AjaxController extends Controller_Action {
    public $options;
    /*
     * Nazwa modułu i kontrolera to: $this->request->getModuleName(),$this->request->getControllerName()
     * $this->oUser nazwa usera
     */

    public function Init() {

        parent::init();

        $this->oRequest = $this->request->getQuery();
        $this->_helper->layout->disableLayout();
        //inicjalizuje rodzica
    }
    public function putAction(){
        echo "sdsds";
        $this->_helper->viewRenderer->setNoRender();
    }



}
