<?php

/**
 * Controler odpowiedzialny za zapis plików
 */
class upload_FilesController extends Zend_Controller_Action {

    private $path;
    private $oRelations;
    public $params;

    public function init() {
        parent::init();
        $this->_helper->layout->disableLayout();

        $pRow = $this->_request->getParams();

        $bRow = array('item' => null,
            'category' => null,
            'language' => null,
            'module'=>null);

        /* margowanie tablicy zapobiega */
        $parmsRow = array_intersect_key(array_merge($bRow, $pRow), $bRow);

        $moduleFolder = $parmsRow['module'] . '_' . $parmsRow['language'];
        $path = realpath(APPLICATION_PATH . '/../../_files/');
        $fullPath = $path . '/' . $moduleFolder;
        if (!is_dir($fullPath)) {
            mkdir($fullPath, 0777);
        }
        $this->path = realpath($fullPath);

        /* baza danych */
        $_name = 'cm_' . $parmsRow['module'] . '_relations_' . $parmsRow['language'];
        $this->oRelations = new Library_Relations(array('name' => $_name));
    }

    public function indexAction() {

    }

    /**
     * Zapis plików na serwerze
     */
    public function saveAction() {
        $this->_helper->viewRenderer->setNoRender();
        $pRow = $this->_request->getParams();

        $bRow = array('item' => null,
            'category' => null,
            'language' => null,
            'module'=>null);

        /* margowanie tablicy zapobiega */
        $parmsRow = array_intersect_key(array_merge($bRow, $pRow), $bRow);
        print_r($parmsRow);
        $oUploader = new Zend_File_Transfer_Adapter_Http();
        $filesFunction = new Library_Files();
        #biblioteki
        //echo $this->path;

        $oFileTable = $oUploader->getFileInfo();
        if (empty($oFileTable)) {
            $jResponse = array('response' => false, 'message' => 'no file');
        } else {

            $FileName = $filesFunction->ToPermaLink(time() . '_' . $oFileTable['Filedata']['name']);
            #Nazwa pliku
            $oUploader->setDestination($this->path)
                    ->addFilter('Rename', array('target' => $FileName, 'overwrite' => true));
            #Ustawienie parametrów
            if ($oUploader->isValid()) {
                if ($oUploader->receive()) {
                    /* dodaje do bazy wpis */
                    $FileInfo = $filesFunction->getFileType($FileName);
                    $row = array(
                        'filename' => $FileName,
                        'name' => $oFileTable['Filedata']['name'],
                        'typ' => $FileInfo[1],
                        'extension' => $FileInfo[0],
                        'size' => $oFileTable['Filedata']['size']
                    );
                    $this->oRelations->addSingleParentRelation((int) $parmsRow['item'], $row);
                    $jResponse = array('response' => true, 'message' => true);
                } else {
                    $jResponse = array('response' => false, 'message' => 'save error');
                }
                #odbieram plik
            } else {
                $jResponse = array('response' => false, 'message' => 'valid file');
            }
            #Validator odebranych plików
        }
        echo Zend_Json::encode($jResponse);
    }

}
