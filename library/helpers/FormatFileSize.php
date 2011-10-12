<?php 
class Global_View_Helper_FormatFileSize extends Zend_View_Helper_Abstract {

    /**
     * @param integer $size size in bytes
     */
    public function formatFileSize($size)
    {
        $mod = 1024;
 
        $units = explode(' ','B KB MB GB TB PB');
        for ($i = 0; $size > $mod; $i++) {
            $size /= $mod;
        }
 
        return round($size, 2) . ' ' . $units[$i];
    }
}
