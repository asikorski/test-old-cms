<?php
/**
 * @author: Arnold Sikorski
 * 
 * Helper generuje ikone oraz wszystkie akcje przy relacjach do pliku
 */

class Global_Helpers_FileIcons {
    public $path = '/img/icons/files/';

    public $icons = array(

                        // Microsoft Office
                        'doc' => array('doc', 'Word Document'),
                        'xls' => array('xls', 'Excel Spreadsheet'),
                        'ppt' => array('ppt', 'PowerPoint Presentation'),
                        'pps' => array('ppt', 'PowerPoint Presentation'),
                        'pot' => array('ppt', 'PowerPoint Presentation'),

                        'mdb' => array('access', 'Access Database'),
                        'vsd' => array('visio', 'Visio Document'),
                    //    'xxxx' => array('project', 'Project Document'),     // dont remember type...
                        'rtf' => array('rtf', 'RTF File'),

                        // XML
                        'htm' => array('htm', 'HTML Document'),
                        'html' => array('htm', 'HTML Document'),
                        'xml' => array('xml', 'XML Document'),

                         // Images
                        'jpg' => array('image', 'JPEG Image'),
                        'jpe' => array('image', 'JPEG Image'),
                        'jpeg' => array('image', 'JPEG Image'),
                        'gif' => array('image', 'GIF Image'),
                        'bmp' => array('image', 'Windows Bitmap Image'),
                        'png' => array('image', 'PNG Image'),
                        'tif' => array('image', 'TIFF Image'),
                        'tiff' => array('image', 'TIFF Image'),

                        // Audio
                        'mp3' => array('audio', 'MP3 Audio'),
                        'wma' => array('audio', 'WMA Audio'),
                        'mid' => array('audio', 'MIDI Sequence'),
                        'midi' => array('audio', 'MIDI Sequence'),
                        'rmi' => array('audio', 'MIDI Sequence'),
                        'au' => array('audio', 'AU Sound'),
                        'snd' => array('audio', 'AU Sound'),

                        // Video
                        'mpeg' => array('video', 'MPEG Video'),
                        'mpg' => array('video', 'MPEG Video'),
                        'mpe' => array('video', 'MPEG Video'),
                        'wmv' => array('video', 'Windows Media File'),
                        'avi' => array('video', 'AVI Video'),

                        // Archives
                        'zip' => array('zip', 'ZIP Archive'),
                        'rar' => array('zip', 'RAR Archive'),
                        'cab' => array('zip', 'CAB Archive'),
                        'gz' => array('zip', 'GZIP Archive'),
                        'tar' => array('zip', 'TAR Archive'),
                        'zip' => array('zip', 'ZIP Archive'),

                        // OpenOffice
                        'sdw' => array('oo-write', 'OpenOffice Writer document'),
                        'sda' => array('oo-draw', 'OpenOffice Draw document'),
                        'sdc' => array('oo-calc', 'OpenOffice Calc spreadsheet'),
                        'sdd' => array('oo-impress', 'OpenOffice Impress presentation'),
                        'sdp' => array('oo-impress', 'OpenOffice Impress presentation'),

                        // Others
                        'txt' => array('txt', 'Text Document'),
                        'js' => array('js', 'Javascript Document'),
                        'dll' => array('binary', 'Binary File'),
                        'pdf' => array('pdf', 'Adobe Acrobat Document'),
                        'php' => array('php', 'PHP Script'),
                        'ps' => array('ps', 'Postscript File'),
                        'dvi' => array('dvi', 'DVI File'),
                        'swf' => array('swf', 'Flash'),
                        'chm' => array('chm', 'Compiled HTML Help'),

                        // Unkown
                        'default' => array('txt', 'Unkown Document'),
    );

    public function FileIcons(array $file = array()) {

        $FileType = $this->getIcon($this->getType($file['filename']));
        //return $file['filename'];
        $response = array(
            'filename' => $file['filename'],
            'imgurl' => $this->GetURLToIcon($FileType),
            'clearname'=>$this->getClearName($file['filename']),
            'type'=>$FileType[0]
        );
        return $response;


    }
    /**
     *
     * Pobieram ikone do wyswietlenia
     * @return <type> 
     */
    private function getIcon($extension) {

        if (key_exists($extension, $this -> icons)) return $this -> icons[$extension];
        else return $this -> icons['default'];
    }
    /**
     *  Metoda zwraca rozszerzenie pliku
     * @param <type> $File
     * @return <type>
     */
    private function getType($File){
        $Extension=split("\.",$File); 
        $suffix=$Extension[count($Extension)-1]; 
        if(strlen($suffix)>0){
            return $suffix;
        }else{
            return false;
        }

    }
    private function getClearName($File){
        $Clear=split("\_",$File);
        $suffix=$Clear[count($Clear)-1];
        if(strlen($suffix)>0){
            return $suffix;
        }else{
            return false;
        }
    }
    /**
     * Generuje Adres do obrazka który mam wyswietlić
     * @param array $icon
     */
    private function GetURLToIcon(array $icon = array()){
        return $this->path.$icon[0].'.png';
    }

}
