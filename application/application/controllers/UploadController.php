<?php

/**
 * @author: Arnold Sikorski
 *
 * Tu kierowane są wszystkie zapytania AJAXOWE, gdy zapytanie nie posiada nagłówka
 * okreslajacego go jako xhtmlrequest wyzwalamy w standardowy sposob
 */
class UploadController extends Zend_Controller_Action {

    /**
     * @author Arnold Sikorski
     *
     * Inicjaliacja Kontrolera , sprawdzmy rodzaj zapytania, jesli nie ajaxowe wyjeb
     */
    public function init() {
        $this->_helper->layout->disableLayout();
        $params = $this->_request->getParams();

        $this->params = $params;
        //$lang = 'pl';

        $folder = 'sites';
        $lang = '_pl';
        $path = realpath(APPLICATION_PATH . '/../../_files/');
        $fullPath = $path . '/' . $folder . $lang;
        if (!is_dir($fullPath)) {
            mkdir($fullPath, 0777);
        }
        $this->path = realpath($fullPath);

        /* baza danych */
        $_name = 'cm_' . $folder . '_relations' . $lang;
        $this->oRelations = new Library_Relations(array('name' => $_name));
    }

    public function uploadAction() {


        $this->_helper->viewRenderer->setNoRender();
        $params = $this->_request->getParams();

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
                    $this->oRelations->addSingleParentRelation((int) $params['id'], $row);
                    $jResponse = array('response' => true, 'message' => 'save');
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