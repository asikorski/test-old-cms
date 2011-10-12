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
class widgets_rightsidebartoparticles_init {
    
    public function __construct() {
        $ScriptPath = realpath(dirname(__FILE__));
        //Ustawiam zend view
        $this->oView = new Zend_View();
        //inicjalizujemy klase widoków
        $this->oView->setScriptPath($ScriptPath . '/view');
        $this->oDbModel = new CMS_Connection(array('module' => 'sites',
                    'lang' => 'pl',
                    'baseURL' => 'gazeta.localhost'));
    }
    
    public function render(){
        
         $oCacheVotes = new Library_CacheVote(array('lang' => 'pl'));
        //TODO trzeba to z kas indziej brac
        $queryElement_Id = array();
        $resultCacheVote = array();
        $resultCacheVote = $oCacheVotes->getTopArticle(null, 'category_id');
        
        foreach($resultCacheVote as $itemCacheVote){
            $queryElement_Id[] = $itemCacheVote['element_id']; 
        }
        $modelTop = array();
        $dataTop = array();
        
        if($queryElement_Id){
            $modelTop = $this->oDbModel->getItemsBy(array('id'=> $queryElement_Id), true, array());
            
            foreach ($modelTop as $itemId){
                $itemTop[$itemId['item']['row']['id']] =  $itemId;
            }
            foreach ($queryElement_Id as $itemId){
                $dataTop[] = $itemTop[$itemId];
            }
        }
        
        $modelCategory = $this->oDbModel->getRootChild(2);
        
        foreach ($modelCategory as $itemCategory){
            $categoryArray[$itemCategory['id']]['name'] = strtolower($itemCategory['name']);
            $categoryArray[$itemCategory['id']]['name_url'] = strtolower($itemCategory['name_url']);
        }
        
        $this->oView->data = $dataTop;
        $this->oView->category = $categoryArray;
        return $this->oView->render('bar.phtml');
    }
    
}

?>
