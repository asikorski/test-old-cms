<?php

/**
 * @author Arnold Sikorski
 * @category Controllers
 * @name IndexController
 *
 * Główny kontroler dostepu
 *
 * Do dumpowania danych korzystamy z new var_dump($cos);!!
 */
class IndexController extends myControllerView {
    
    public $oDbModel;

    public function init() {
        parent::init();
        
        $this->oDbModel = new CMS_Connection(array('module' => 'sites',
                    'lang' => 'pl',
                    'baseURL' => 'gazeta.localhost'));
         $this->oUserConnector = new CMS_Users(array('lang' => 'pl'));
        $this->layout->leftContent = $this->leftContentView();
    }

    public function indexAction() {
    }

    
    private function leftContentView(){
        
        $path = realpath(dirname(__FILE__)). '/../views/scripts/elements';
        $this->oView = $this->setZendView($path);
        
        $query = array('');
        $ord = array('date_add DESC');
        
        $model = $this->oDbModel->getItemsBy($query, true ,$ord, 10);
        
        $modelCategory = $this->oDbModel->getRootChild(2);
        //new var_dump($modelCategory);die;
        foreach ($modelCategory as $itemCategory){
            $category[$itemCategory['id']]['name'] = strtolower($itemCategory['name']);
            $category[$itemCategory['id']]['name_url'] = strtolower($itemCategory['name_url']);
        }
        
        $this->oView->h2title = 'Wylęgarnia';
        $this->oView->data = $model;
        $this->oView->category = $category;
        //Top List
        $topList = new widgets_toplist_init();
        
        return $topList->render().$this->oView->render('leftContentList.phtml');
    }
}
