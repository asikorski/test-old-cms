<?php

/**
 * @author Arnold Sikorski
 * @name Controllers
 *
 * Kontroler zarządzajacy lista użytkowników
 */
class users_IndexController extends Controller_Action {
    public function Init() {
    /* Widget do obsługi Facebooka */
        parent::init();
        //inicjalizuje rodzica
        /* Widget do obsługi Facebooka */
        $FacebookWidget = new Widgets_Facebook_Init();
        $this->view->oWidgetFacebook = $FacebookWidget->render();
        /* widget do wyswietlania zaisntalowanych modulów */
        $ModulesListWidget = new Widgets_ModuleList_Init();
        $this->view->oWidgetModulesList = $ModulesListWidget->render();
    }

    public function indexAction() {

    }
    public function edituserAction(){
        
    }


}
