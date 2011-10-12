<?php
/**
 * @author: Arnold Sikorski
 * 
 * Helper linki do obrazków
 */

class Global_Helpers_ImgThumb {

    public function ImgThumb(array $img = array()){

       //var_dump($FinalImg);
       //$router = Zend_Controller_Front::getInstance()->getRouter();
        //return $router->assemble($urlOptions = array(), $FinalImg, $reset= array(), $encode= array());
       return '/gfx/'.$img['height'].'/'.$img['width'].'/'.$img['module'].'/'.$img['file'];
    }
}
