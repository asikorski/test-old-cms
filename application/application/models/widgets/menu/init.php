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
class widgets_menu_init extends Widgets_Loader {

    public $oDbModel;
    
    public function __construct() {
        parent::__construct(realpath(dirname(__FILE__)));
        $this->oDbModel = new CMS_Connection(array('module' => 'sites',
                    'lang' => 'pl',
                    'baseURL' => 'gazeta.localhost'));
    }

    public function render() {

        $model = $this->oDbModel->getRootChild(2);
        foreach($model as $key => $items){
            $menu[$key]['name'] = $items['name'];
            $menu[$key]['url'] = $items['name_url'];
        }
        $this->oView->menu = $menu;

        return $this->oView->render('bar.phtml');
    }

}

?>
