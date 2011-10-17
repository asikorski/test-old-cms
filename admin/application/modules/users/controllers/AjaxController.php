<?php

/**
 * @author Arnold Sikorski
 * @name Controllers
 *
 * Kontroler obsługi requestów ajaxoqych
 */
class users_AjaxController extends Controller_Action {

    protected $oUsers;

    public function Init() {
        /* Widget do obsługi Facebooka */
        parent::init();
        $this->oUser = new Library_Users(array('lang' => 'pl'));
            #inicjalizacja klasy uzytkowników
        $this->_helper->layout->disableLayout();
    }

    /**
     * Pobieram liste użytkowników
     * --------------------------------------------------------------------------
     */
    public function getusersAction() {
        $this->_helper->viewRenderer->setNoRender();
        $query=$this->request->getQuery();
        //print_r($query);
        //die;
        $users  = $this->oUser->getUsers();

        $data = array('page'=>1,
                      'total'=>1,
                       'rows'=>array());
        #przechodzenie po poszczegolnych elementach
        foreach ($users as $row) {
            $jUser[] = array('action'=>$this->view->partial('partials/user-action.phtml',
                            array('row' =>$row)),
                            'status'=>$this->view->partial('partials/user-status.phtml',
                            array('row' =>$row)),
                            'avatar'=>$this->view->partial('partials/user-status.phtml',
                            array('row' =>$row)),
                            'first_name'=>$this->view->partial('partials/user-first_name.phtml',
                            array('row' =>$row['first_name'])),
                            'last_name'=>$this->view->partial('partials/user-first_name.phtml',
                            array('row' =>$row['last_name'])));
        }
        $data['rows']= array_merge($jUser);
        echo Zend_Json::encode($data);
        //print_r($users);
    }

}
