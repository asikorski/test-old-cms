<?php

class Library_Files {
    public $module;
    public $lang;
    public $BaseUrl;

    public $typeFiles = array(
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

    /**
     *
     * @param <type> $string - nazwa pliku
     * @return <type> - przyjazna nazwa
     */
    public function ToPermaLink($string) {


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

    /**
     *
     * @param <type> $string - nazwa pliku
     */
    public function genHashName($string) {
        
    }

    public function getFileType($File) {
        $Extension = split("\.", $File);
        $suffix = $Extension[count($Extension) - 1];
        if (strlen($suffix) > 0) {
            /* przemianowanie */
            if (key_exists($suffix, $this->typeFiles))
                return $this->typeFiles[$suffix];
            else
                return $this->typeFiles['default'];
            /* koniec */
        } else {
            return false;
        }
    }
  
}