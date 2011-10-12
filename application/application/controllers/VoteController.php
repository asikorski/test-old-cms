<?php

class VoteController extends Zend_Controller_Action {

    private $oVotes;
    const SKOK = -0.73;

    public function init() {
         parent::init();
        $this->_helper->layout->disableLayout();
        $params = $this->_request->getParams();
        $this->oVotes = new Library_Votes(array('lang' => 'pl'));
    }

    
    public function getvoteAction(){
        echo 'ss';die;
        $response = $this->oVotes->getSumAndItemVoteByArticle(187);
        print_r($response);
        die;
    }
    
    public function sumvoteAction(){
        $this->view->VotesHtml();
die;        
////($sumVote, $countVote){
        $sumVote = 73-41;//int($sumVote);
        $countVote = 114;//int($countVote);
        
        $lambda = 100/$countVote;
        $Pvote = $sumVote*$lambda;
        $votePx = ceil($Pvote*self::SKOK);
        echo $votePx;die;
    }
    

}