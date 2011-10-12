<?php

/**
 * @author Arnold Sikorski
 */
class users_EditController extends Controller_Action {
    public $options;
    /*
     * Nazwa modułu i kontrolera to: $this->request->getModuleName(),$this->request->getControllerName()
     * $this->oUser nazwa usera
     */

    public function Init() {

        $this->view->controller =
                /* Widget do obsługi Facebooka */
                $FacebookWidget = new Widgets_Facebook_Init();
        $this->view->oWidgetFacebook = $FacebookWidget->render();
        /* widget do wyswietlania zaisntalowanych modulów */
        $ModulesListWidget = new Widgets_ModuleList_Init();
        $this->view->oWidgetModulesList = $ModulesListWidget->render();
                /* Ustawienia */
        $options = Zend_Registry::get('options');
        $this->options = $options->toArray();
        $this->view->assign('options', $this->options);
        parent::init();
        //inicjalizuje rodzica
    }
    public function aboutAction(){
        
    }
    public function adduserAction(){
        
    }
    public function groupsAction(){
        
    }
    public function listAction(){

    }


}
