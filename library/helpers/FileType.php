<?php

class Global_View_Helper_FileType extends Zend_View_Helper_Abstract {

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

    public function FileType($File) {

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
