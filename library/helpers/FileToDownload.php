<?php

/**
 * @author: Arnold Sikorski
 * @category Helpers
 * @name FileToDownload
 *
 * Generowanie adresu url do pobierania pliku
 */
class Global_View_Helper_FileToDownload extends Zend_View_Helper_Abstract {

    protected $settings;

    /**
     * Konstruktor korzysta z pliku konfiguracyjnego
     */
    public function __construct() {
        try {
            $options = new Zend_Config_Ini(APPLICATION_PATH . '/../../config/configs.ini', APPLICATION_ENV);

            $this->settings = $options->toArray();
            //new var_dump($this->settings);
            //die;
        } catch (Library_Exception $e) {
            return false;
        }
    }

    public function FileToDownload($file, $module, $lang) {
        $options = $this->settings;
        if (!empty($file) && !empty($module) && !empty($lang)) {
            $path = $module . '_' . $lang . '/' . $file;
            $realPath = $options['files']['files']['path'] . '/' . $path;
            if (file_exists($realPath)) {
                //return 'plik istnieje';
                $fileLink = base64_encode($path);
                $url = $this->url($fileLink);
                return ($url) ? $url : false;
            } else {

                return $this->url('404');
            }

            // return $fileLink;
        } else {
            return $this->url('404');
        }
    }

    private function url($file) {
        try {
            $options = $this->settings;
            if ((int) $file == 404) {
                return $options['http']['application'] . $file;
            } else {
                return $options['http']['application'] . $options['files']['files']['header'] . '/' . $file;
            }
        } catch (Library_Exception $e) {

            return false;
        }
    }

}
