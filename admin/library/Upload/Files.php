<?php

/**
 * @author Arnold Sikorski
 *
 * Klasa opowiedzialna za zapis plików
 */
class Upload_Files {

    public $request;
    public $uploader;
    private $uploadPath;


    public function __construct($request) {
        $this->request = $request;
        $this->uploader = new Zend_File_Transfer_Adapter_Http();
        $this->uploadPath = realpath($_SERVER['DOCUMENT_ROOT'] . '/_files/'.$request->getModuleName());
        //sciezka do katalogu
        //$this->uploadPath =
        //potrzebne biblioteki
        // var_dump($request->getParam());
        $this->SaveFile();
    }

    public function SaveFile() {
        /**
         * @author Arnold Sikorski
         *
         * Metoda zapisuje na dysku pliki , napierw generuje nazwe pliku do zapisu
         * - kazdy plik poprzedzony jest data zapisu kolejnym krokiem jest
         * ustalenie scierzki zapisu, dodanie filtrów
         *
         * Nastepnie wykonywany jest zapis i ew zwracana informacja zakodowana json
         * jesli nasapił problem
         */
        $FileTable =$this->uploader->getFileInfo();
        $FileName = $this->toPermalink(time().'_'.$FileTable['Filedata']['name']);
        
        $this->uploader->setDestination($this->uploadPath)
                        ->addFilter('Rename', array('target' => $FileName,'overwrite' => true));

        if($this->uploader->isValid()){
            if (!$this->uploader->receive()) {
                $messages = $adapter->getMessages();
                //echo implode("\n", $messages);
                echo  Zend_Json::encode(array('response'=>false,'message'=>implode("\n", $messages)));
            }else{
                $this->SaveToDatabase($FileName);
            }
        }else{
           //echo "błąd validacji";
           echo  Zend_Json::encode(array('response'=>false,'message'=>'Valid Err'));
        }

    }
    public function  SaveToDatabase($file){
        //Tworze relacje
        $db = Zend_Registry::get('dbAdapter');
        //cm_article_elements_relations_pl
        //$TableName = 'cm_relation_'.$this->request->getModuleName().'_pl';
            //$db->_name = 'cm_'.$this->request->getModuleName().'_pl';
            $db->_name = 'cm_article_elements_relations_pl';
            $db->_primary = 'id';
            //var_dump($db);
           // $select = $db->select();
            //var_dump($this->request->getQuery());
            $Query = $this->request->getQuery();
            $row = array(
                    'id_relations'=>1,
                    'filename'=>$file,
                    'date_mod'=>date('Y-m-d h:i:s'),
                    'date_add'=>date('Y-m-d h:i:s'),
                    'name'=> isset($Query['name'])?$Query['name']:$file
                    );
            //$row['date_mod']=date('Y-m-d h:i:s');
       // $row['date_add']=date('Y-m-d h:i:s');
            $db->insert( $db->_name ,$row);

        echo  Zend_Json::encode(array('response'=>true,'message'=>'Saved'));
    }
    private function toPermalink($string) {


    $unPretty = array('/ä/', '/ö/', '/ü/', '/Ä/', '/Ö/', '/Ü/', '/ß/',
        '/ą/', '/Ą/', '/ć/', '/Ć/', '/ę/', '/Ę/', '/ł/', '/Ł/', '/ń/', '/Ń/', '/ó/', '/Ó/', '/ś/', '/Ś/', '/ź/', '/Ź/', '/ż/', '/Ż/',
        '/Š/', '/Ž/', '/š/', '/ž/', '/Ÿ/', '/Ŕ/', '/Á/', '/Â/', '/Ă/', '/Ä/', '/Ĺ/', '/Ç/', '/Č/', '/É/', '/Ę/', '/Ë/', '/Ě/', '/Í/', '/Î/', '/Ď/', '/Ń/',
        '/Ň/', '/Ó/', '/Ô/', '/Ő/', '/Ö/', '/Ř/', '/Ů/', '/Ú/', '/Ű/', '/Ü/', '/Ý/', '/ŕ/', '/á/', '/â/', '/ă/', '/ä/', '/ĺ/', '/ç/', '/č/', '/é/', '/ę/',
        '/ë/', '/ě/', '/í/', '/î/', '/ď/', '/ń/', '/ň/', '/ó/', '/ô/', '/ő/', '/ö/', '/ř/', '/ů/', '/ú/', '/ű/', '/ü/', '/ý/', '/˙/',
        '/Ţ/', '/ţ/', '/Đ/', '/đ/', '/ß/', '/Œ/', '/œ/', '/Ć/', '/ć/', '/ľ/');

    $pretty = array('ae', 'oe', 'ue', 'Ae', 'Oe', 'Ue', 'ss',
        'a', 'A', 'c', 'C', 'e', 'E', 'l', 'L', 'n', 'N', 'o', 'O', 's', 'S', 'z', 'Z', 'z', 'Z',
        'S', 'Z', 's', 'z', 'Y', 'A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'N',
        'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 'a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e',
        'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y',
        'TH', 'th', 'DH', 'dh', 'ss', 'OE', 'oe', 'AE', 'ae', 'u');

    $permalink = strtolower(preg_replace($unPretty, $pretty, $string));
    return str_replace(" ", "-", $permalink);
}

}
