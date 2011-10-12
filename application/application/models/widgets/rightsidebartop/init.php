<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of init
 *
 * @author piotrek
 */
class widgets_rightsidebartop_init extends Widgets_Loader {

    public function __construct() {
        parent::__construct(realpath(dirname(__FILE__)));
    }

    public function render() {
        //laduje reklame
        $ad = new widgets_ads_init();
        $this->oView->ad = $ad->renderAdRightTop();

        return $this->oView->render('bar.phtml');
    }

}

?>
