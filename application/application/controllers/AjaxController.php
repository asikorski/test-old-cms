<?php

/**
 * @author: Arnold Sikorski
 *
 * Tu kierowane są wszystkie zapytania AJAXOWE, gdy zapytanie nie posiada nagłówka
 * okreslajacego go jako xhtmlrequest wyzwalamy w standardowy sposob
 */
class AjaxController extends Zend_Controller_Action {

    private $oDbModel;
    public $lang;
    public $Layout;

    /**
     * @author Arnold Sikorski
     *
     * Inicjaliacja Kontrolera , sprawdzmy rodzaj zapytania, jesli nie ajaxowe wyjeb
     */
    public function init() {
        if ($this->_request->isXmlHttpRequest()) {
            $this->_helper->layout->disableLayout();
        };
        
        $this->oVotes = new Library_Votes(array('lang' => 'pl'));
        
    }

    public function addvoteAction() {
        $formDataResponse = $this->_request->getPost();
        
        $this->_helper->viewRenderer->setNoRender();

        $this->oVotes->addVoteFromUser( (int)$formDataResponse['user'],   // bedzie brany z sesji
                                        (int)$formDataResponse['id'], 
                                        (int)$formDataResponse['vote']);
        echo $this->view->VotesHtml($formDataResponse['id']);
    }

   

}