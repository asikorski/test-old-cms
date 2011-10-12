<?php

/*
 * Uploader plików
 */

class Default_UploadController extends Controller_Action {

    public $oUpload;

    public function init() {
        /* inicjalizator kontrolera */
        parent::init();
        /* inicjalizuje rodzica */
        $this->oUpload = new Database_UploadElements;
        //$this->_helper->layout->disableLayout();
        /* wylaczamy layout */
    }

    public function indexAction() {
        //echo "Tu jestem";
        $this->_helper->viewRenderer->setNoRender();
        /* testowo wyłączam renderowanie */
    }

    /**
     * @author Arnold Sikorski
     *
     * Zobacz pliki powiazane z danym elementem
     */
    public function filesAction() {
        //Najpierw pobieramy relacje do danego elementu;
        /// $this->_helper->layout->disableLayout();
        $request = $this->request->getQuery();
        if (isset($request['id'])) {
            echo "jestem tu";
            $this->view->oRelationFiles = $this->oUpload->GetRelationsFromId($request['id']);
            //$this->view->oRelationFiles =$oFiles;
            //Pobieramy pliki
        }
    }

    /**
     * @author Arnold Sikorski
     * 
     * Uploaduje wybrany plik na serwer
     */
    public function uploadAction() {
        /**
         * @author Arnold Sikorski
         *
         * Zapis wybranego pliku na serwerze
         */
        $this->_helper->layout->disableLayout();


        echo '1';
        $this->_helper->viewRenderer->setNoRender();
    }
    /**
     * Usuwanie pliku
     */
    public function delateAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $request = $this->request->getQuery();
        if(isset($request['id'])){
            if($this->oUpload->RemoveRelationFromId($request['id'])){
                 $jResponse = array('response'=>true,'message'=>'true');
                 
            }else{
                $jResponse = array('response'=>false,'message'=>'false');
            }
        }else{
            $jResponse = array('response'=>false,'message'=>'bad request');
        }
        echo Zend_Json::encode($jResponse);
    }
    public function getfilesAction(){
        
    }

}
