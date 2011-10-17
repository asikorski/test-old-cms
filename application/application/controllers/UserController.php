<?php

/**
 * @author: Arnold Sikorski
 *
 * Rejestracja usera
 */
class UserController extends myControllerView {

    protected $oDbModel;
    public $oLayoutView;

    public function init() {
        //$this->_helper->layout->disableLayout();
        #Wyłączam renderowanie szablonu

        parent::init();

        $this->oDbModel = new CMS_Connection(array('module' => 'sites',
                    'lang' => 'pl'));
        #Uruchamianie odpowiedniego widoku
        $path = realpath(dirname(__FILE__)) . '/../views/scripts/user';
        $this->oLayoutView = $this->setZendView($path);
        /* user inijazlier */
        $this->oUserConnector = new CMS_Users(array('lang' => 'pl'));
    }

    /**
     * rejestracja nowego usera
     */
    public function registerAction() {
        if(CMS_Users::checkStatus()!=CMS_Users::USER_ACTIVE){
             $oFormRegister = new user_Forms_Register();
            $this->oLayoutView->form = $oFormRegister;
        }else{
            $this->oLayoutView->result = CMS_Users::USER_ACTIVE;
        }
       


        $this->renderView();
    }

    /**
     * logowanie usera
     */
    public function loginAction() {
        //$this->oUserConnector->authenticate('root', 'rsa21bc651');
        $redirector = new Zend_Controller_Action_Helper_Redirector();

        /**/
        $status = CMS_Users::checkStatus();


        switch ($status) {
            case CMS_Users::USER_ACTIVE;
                $redirector->gotoRouteAndExit(array(null), 'usermyaccount', true);
                //die('zalogowany');
                break;
            case CMS_Users::USER_FAILURE_NO_ACTIVE:
                /** do stuff for nonexistent identity * */
                $redirector->gotoRouteAndExit(array(null), 'userchceckcode', true);
                break;

            case CMS_Users::USER_FAILURE_BANNED:
                /** do stuff for invalid credential * */
                $redirector->gotoRouteAndExit(array(null), 'usermessage', true);
                break;

            default:
                /* inne */
                if ($this->_request->isPost()) {
                    $response = $this->_request->getPost();
                    $oLogin = new user_Forms_Login($response);
                    if ($oLogin->isValid($response)) {
                        /* DAne wprowadzone poprawnie */
                        // [username] => dfdf [password]
                        $callback = $this->oUserConnector->authenticate($response['username'], $response['password']);
                        switch ($callback) {

                            case Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND:
                                /** do stuff for nonexistent identity * */
                                $this->oLayoutView->result = $callback;
                                break;

                            case Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID:
                                /** do stuff for invalid credential * */
                                $this->oLayoutView->result = $callback;
                                break;

                            case Zend_Auth_Result::SUCCESS:
                                /** do stuff for successful authentication * */
                                $redirector->gotoRouteAndExit(array(null), 'userlogin', true);
                                break;

                            default:
                                /** do stuff for other failure * */
                                $redirector->gotoRouteAndExit(array(null), 'userlogin', true);
                                break;
                        }

                        /* przekierowanie */
                    } else {

                    }
                } else {
                    $oLogin = new user_Forms_Login();
                    /* niezalogowany i bez posta */
                }
                /* end */
                break;
        }

        /* renderowanie formularza logowania */


        $this->oLayoutView->form = $oLogin;
        $this->renderView();
    }

    /**
     * wyswietlenie mojego konta
     */
    public function myaccountAction() {

        if(CMS_Users::checkStatus()!=CMS_Users::USER_ACTIVE){
            $redirector = new Zend_Controller_Action_Helper_Redirector();
            $redirector->gotoRouteAndExit(array(null), 'userlogin', true);
        }else{
            $oUser = Zend_Registry::get('oUserAuth');

            $this->oLayoutView->row = $oUser;

        }
        /*sprawdzenie statusu*/
        //CMS_Users::Logout();
        $this->renderView();
    }

    /**
     * sprawdzanie kodu do aktywacji usera
     */
    public function checkcodeAction() {
        if(CMS_Users::checkStatus()==CMS_Users::USER_ACTIVE){
            $this->oLayoutView->result = CMS_Users::USER_ACTIVE;
        }else{
           $oCheckCode = new user_Forms_Checkcode();
            $this->oLayoutView->form = $oCheckCode;
        }
        
        $this->renderView();
    }

    public function messageAction() {
        die('banned');
    }

    protected function renderView() {

        $request = $this->getRequest();
        $action = $request->getActionName();
        $this->layout->leftContent = $this->oLayoutView->render($action . '.phtml');
    }

    public function logoutAction() {

            CMS_Users::Logout();
            $redirector = new Zend_Controller_Action_Helper_Redirector();
            $redirector->gotoRouteAndExit(array(null), 'userlogin', true);
        
        /*sprawdzenie statusu*/
        
        
    }

}