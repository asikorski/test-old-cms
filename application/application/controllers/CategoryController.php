<?php

/**
 * @author Arnold Sikorski
 * @category Controllers
 * @name IndexController
 *
 * Kontroler artykulów
 *
 * Do dumpowania danych korzystamy z new var_dump($cos);!!
 */
class CategoryController extends myControllerView {
    
    public $oDbModel;
    
    public function init() {

        parent::init();
        $this->oDbModel = new CMS_Connection(array('module' => 'sites',
                    'lang' => 'pl',
                    'baseURL' => 'gazeta.localhost'));
        $this->votes = new Library_Votes(array('lang' => 'pl'));
        $this->oUserConnector = new CMS_Users(array('lang' => 'pl'));
    }

    public function indexAction() {
        $this->_helper->viewRenderer->setNoRender();
        $category = $this->getRequest()->getParam('category');
        
        //title
        $this->title .= ' - '.$category;
        
        //pobieram z bazy artykul
        $model = $this->oDbModel->getItemsByParentUrl($category, true, 10);
        
        //pobieram czolowke dzialu
        $oCacheVotes = new Library_CacheVote(array('lang' => 'pl'));
        //TODO trzeba to z kas indziej brac
        
        $resultCacheVote = array();
        $queryElement_Id = array();
        
        $resultCacheVote = $oCacheVotes->getTopArticle($model[0]['item']['row']['category_id']);
        if(!$resultCacheVote){
            $resultCacheVote = array();
        }
        foreach($resultCacheVote as $itemCacheVote){
            $queryElement_Id[] = $itemCacheVote['element_id']; 
        }
       
        $modelTop = array();
        if($queryElement_Id){
            $modelTop = $this->oDbModel->getItemsBy(array('id'=> $queryElement_Id), true, array());
            
            foreach ($modelTop as $itemId){
                $itemTop[$itemId['item']['row']['id']] =  $itemId;
            }
            foreach ($queryElement_Id as $itemId){
                $dataTop[] = $itemTop[$itemId];
            }
        }
        
        //pobieram statystyki 
        $votes = $this->votes->getVoteStat($model[0]['item']['row']['category_id']);
        $i=1;
        
        foreach($votes as $itemVotes){
            $voteStat[$itemVotes['element_id']] = $itemVotes;
            $voteStat[$itemVotes['element_id']]['position'] = $i; 
            $voteStat[$itemVotes['element_id']]['img']= $this->view->VotesHtml(null,$itemVotes['vote'], $itemVotes['count']);
            $i++;
        }
       
        
        
        $modelCategory = $this->oDbModel->getRootChild(2);
        
        foreach ($modelCategory as $itemCategory){
            $categoryArray[$itemCategory['id']]['name'] = strtolower($itemCategory['name']);
            $categoryArray[$itemCategory['id']]['name_url'] = strtolower($itemCategory['name_url']);
        }
        
        //sprawdzam czy strona istnieje
        $this->checkIsSideExist($model);
        
        $path = realpath(dirname(__FILE__)) . '/../views/scripts/elements';
        $this->oView = $this->setZendView($path);
        $this->oView->h2title = ucfirst($category);
        $this->oView->data = $model;
        $this->oView->category = $categoryArray;
        $this->oView->topArticle = $dataTop;
        $this->oView->voteStat = $voteStat;
        
        $this->layout->leftContent = $this->oView->render('leftContentListArticle.phtml');
    }

    public function articleAction() {
        $this->_helper->viewRenderer->setNoRender();

        $category = $this->getRequest()->getParam('category');
        $articleUrl = $this->getRequest()->getParam('article');

        //title
        $this->title .= ' - '.$articleUrl;
        //pobieram z bazy artykul
        $model = $this->oDbModel->getItemByUrl((string)$articleUrl, array(true, array('id ASC')));
        //sprawdzam czy strona istnieje
        
        $this->checkIsSideExist($model);
        
        $path = realpath(dirname(__FILE__)) . '/../views/scripts/articles';
        $this->oView = $this->setZendView($path);
        $this->oView->data = $model;
        $this->oView->category = $category;
        $this->oView->vote = $this->view->VotesHtml($model['item']['id']);
       
        
        $conf = array(  'data' => $model,
                        'category' => $category);

        $this->layout->leftContent = $this->oView->render('article.phtml');
    }

}