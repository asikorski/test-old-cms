<?php

/**
 * @author Arnold Sikorski
 */
class sites_IndexController extends Controller_Action {
    public function Init() {
        /* Widget do obsługi Facebooka */
        parent::init();
        //inicjalizuje rodzica
        $request = $this->request->getQuery();
        $this->view->request = $request;
        $urlGenerator = new Helpers_URL($request);
    }

    public function indexAction() {

    }
    public function treeAction(){
        
    }


}
