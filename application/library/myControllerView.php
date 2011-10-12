<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of myController
 *
 * @author piotrek
 */
class myControllerView extends Zend_Controller_Action {
   
    protected $layout;
    public $title = 'Gazeta Publiczna';

    public function init() {
        //Menu        
        
        $menu = new widgets_menu_init();
        //Right Side Bar Top Widget
        $rightSideBarTop = new widgets_rightsidebartop_init();

        //Right Site Bar Top Articles Widget
        $rightSideBarTopArticles = new widgets_rightsidebartoparticles_init();

        //Top List
        $topList = new widgets_toplist_init();

        $this->layout = new Zend_Layout();
        $this->layout->menu = $menu;
        $this->layout->rightSideBarTop = $rightSideBarTop;
        $this->layout->rightSiteBarTopArticles = $rightSideBarTopArticles;
    }
    
    public function setZendView($path=null){
        $view = new Zend_View();
        if($path) $view->setScriptPath($path);
        $view->addHelperPath(APPLICATION_PATH . "/../../library/helpers", 'Global_View_Helper');
        $this->view->headTitle($this->title);
        return $view;
    }
    
    public function checkIsSideExist($side){
        if(!$side){
            $this->error404();
        }
    }
    
    private function error404(){
        echo '<div style="margin: 0 auto;width:843px;"><a onClick="history.back()"><img border=0 src="/img/404.jpg"></a></div>';
        die;
    }
    
}

?>
