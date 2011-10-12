<?php

/**
 * @author Arnold Sikorski
 * Helper generujacy ikone zaleznie od typu pliku
 */
class Global_View_Helper_FileIcon extends Zend_View_Helper_Abstract {

    public $typeFiles = array(
        'image' => 'image.png',
        'audio' => 'audio.png',
        'video' => 'file_flv.png',
        'txt'=>'file_txt.png',
        'php' => 'file_php.png',
        'pdf' => 'file_pdf.png',
        'html' => 'file_html.png',
        'flv' => 'file_flv.png',
        'doc' => 'file_doc.png',
        'default' => 'inne.png');

    public function FileIcon($fileType) {
        /**
         * Merguje tablice typów plików do klas
         */
        if ($fileType) {
            if (key_exists($fileType, $this->typeFiles)) {
                return $this->typeFiles[$fileType];
                //Zwracam odpowiednia klase
            } else {
                return $this->typeFiles['default'];
            }
        } else {
            return false;
        }
    }

}