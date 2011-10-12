<?php

/**
 * @author Arnold Sikorski
 */
class sites_DialogsController extends Controller_Action {

    public function Init() {
        parent::init();

        $this->oRequest = $this->request->getQuery();
        $this->_helper->layout->disableLayout();
    }

    public function serachitemsAction() {
        //$this->_helper->viewRenderer->setNoRender();
    }

    public function contentAction() {
        
    }

}
