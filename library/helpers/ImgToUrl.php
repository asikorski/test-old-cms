<?php

/**
 * @author: Arnold Sikorski
 * @category Helpers
 * @name ImgToUrl
 *
 * Generowanie adresu url do obrazka - cropowanie, skalowanie, desaturacja, itp w locie, helper korzysta z skryptu
 * thumb.php.
 */
class Global_View_Helper_ImgToUrl extends Zend_View_Helper_Abstract {

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

    /**
     *
     * @param <type> $file - nazwa pliku /nazwa rzeczywista plik musi istnieć fizycznie na dysku/
     * @param <type> $module - nazwa modulu do ktorego zostal uploadowany plik1
     * @param <type> $lang - Wersja językowa
     * @param <type> $params - parametry , przyklad tablicy poniezej
     *        $cacheArray = array(
      'image_resize' => true,
      'image_x' => 100,
      'image_y' => 100,
      'image_ratio_y' => true,
      'image_ratio_x' => true,
      'image_convert' => 'jpg',
      'jpeg_quality' => 85,
      'jpeg_size' => 3072,
      'image_brightness' => 40,
      'image_contrast' => 50,
      'image_opacity ' => 50,
      'image_negative' => true,
      'image_greyscale' => true,
      'image_rotate' => 80,
      'image_crop' => array(50, 40, 30, 20)
     */
    public function ImgToUrl($file, $module, $lang, $params = array()) {
        $options = $this->settings;
        $cacheArray = array();
        if (!empty($file) && !empty($params)) {
            $params = array_merge($cacheArray, $params);
            $params['realfilename'] = $file;
            $md5FileName = md5(base64_encode(Zend_Json::encode($params)));
            $suffix = $this->extension($file);
            if ($suffix) {
                if (isset($params['image_convert'])) {
                    $suffix = $params['image_convert'];
                }
                if (isset($params['set_image_public_path'])) {
                    /* dynamiczna zmiana scierzki do pliku */
                    $realPath = APPLICATION_PATH . '/../public' . $params['set_image_public_path'] . '/' . $file;
                    //echo $realPath;
                    unset($params['set_image_public_path']);
                } else {
                    $realPath = $options['files']['gfx']['path'] . '/' . $module . '_' . $lang . '/' . $file;
                }

                $cachePath = $options['files']['gfx']['cache'] . '/' . $md5FileName . '.' . $suffix;

                if (file_exists($realPath)) {
                    if (file_exists($cachePath)) {
                        return $this->url($md5FileName . '.' . $suffix);
                    } else {
                        /* generowanie pliku */
                        require_once 'Upload/class.upload.php';
                        $upload = new Upload($realPath);
                        foreach ($params as $key => $value) {
                            $upload->$key = $value;
                        }
                        $upload->file_new_name_body = $md5FileName;
                        $upload->Process($options['files']['gfx']['cache']);
                        return $this->url($md5FileName . '.' . $suffix);
                    }
                } else {

                    return false;
                }
            }
        } else {
            return false;
        }
    }

    /**
     * Wyciągam z nazwy pliku samo rozszerzenie
     * @param <type> $string - Nazwa pliku
     * @return <type> - rozszerzenie
     */
    private function extension($string) {
        try {
            $Extension = explode(".", $string);
            return $Extension[1];
        } catch (Library_Exception $e) {

            return false;
        }
    }

    /**
     *
     * @param <type> $file - Nazwa pliku
     * @return <type> adres url
     *
     * Generuje adres url do obrazka
     */
    private function url($file) {
        try {
            $options = $this->settings;
            return $options['http']['application'] . $options['files']['gfx']['header'] . '/' . $file;
        } catch (Library_Exception $e) {

            return false;
        }
    }
/**
 *
 * @param <type> $word
 * @return <type>
 */
    function string_to_filename($word) {
        $tmp = preg_replace('/^\W+|\W+$/', '', $word); // remove all non-alphanumeric chars at begin & end of string
        $tmp = preg_replace('/\s+/', '_', $tmp); // compress internal whitespace and replace with _
        return strtolower(preg_replace('/\W-/', '', $tmp)); // remove all non-alphanumeric chars except _ and -
    }

}
