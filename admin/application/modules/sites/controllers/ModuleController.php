<?php

/**
 * @author Arnold Sikorski
 *
 * Instalowanie deinstalowanie modułu oraz usuwanie
 */
class sites_ModuleController extends Controller_Action {

    public function Init() {
        parent::init();
        $this->_helper->layout->disableLayout();
    }

    public function installAction() {
        //registerModule
        $module = $this->ModuleName;
        $file = APPLICATION_PATH . '/modules/' . $module . '/_db/structure.sql';
        if (file_exists($file)) {

            $sqlQuery = fread(fopen($file, "r"), filesize($file));
            if (!empty($sqlQuery)) {
                $oModules = new Library_Modules(array('_name'=>'cm_modules_pl'));
                if($oModules->queryStructureModule($sqlQuery)){
                    $jResponse = array('response' => true,
                        'message' => 'saved');
                }else{
                    $jResponse = array('response' => false,
                        'message' => 'save error');
                }
                
            } else {
                $jResponse = array('response' => false,
                    'message' => 'file is empty');
            }
        } else {
            $jResponse = array('response' => false,
                'message' => 'no file');
        }
        //$fileQuery =
        //$sqlQuery = fread(fopen("nazwa_pliku", "r"), filesize("nazwa_pliku"));
        echo Zend_Json::encode($jResponse);
        $this->_helper->viewRenderer->setNoRender();
    }

    public function uinstallAction() {
        
    }

    public function removeAction() {
        
    }

}
