<?php

/*
 * @Author: Arnold Sikorski
 *
 * Kontroler autoryzacji użytkownika, wykorzystujacy moduł Auth_Authorization
 * moduł znajduje sie w folderze library w katalgu głównym
 * Moduł korzysta też z widgetów, są to małe psudo helpery, widgetu znajdua sie w folderze /widget
 */

class auth_IndexController extends Controller_Action {

    public $oAuth;

    public function init() {
        // parent::init();
        $this->oAuth = new Auth_Authorization();
        /* Widget do obsługi Facebooka */
        $FacebookWidget = new Widgets_Facebook_Init();
        $this->view->oWidgetFacebook = $FacebookWidget->render();
        /* widget do wyswietlania zaisntalowanych modulów */
        $ModulesListWidget = new Widgets_ModuleList_Init();
        $this->view->oWidgetModulesList = $ModulesListWidget->render();
        /* Ustawienia */
        $options = Zend_Registry::get('options');
        $this->options = $options->toArray();
        $this->view->assign('options', $this->options);
    }

    public function indexAction() {

    }

    public function logoutAction() {
        /*
         * Wylogowanie usera
         */
        $this->_helper->layout->disableLayout();
        $this->oAuth->LogOut();
        $this->_redirect($this->_request->getBaseUrl());
        $this->_helper->viewRenderer->setNoRender();
    }

    public function authorizationAction() {
        //echo 'musisz sie zalogować by otrzymać dostep do cms';
        try {
            if ($this->_request->isPost()) {
                $userSpy = new Library_Spy();
                $formDataResponse = $this->_request->getPost();
                $AuthForm = new AuthForm($formDataResponse);
                if ($AuthForm->isValid($formDataResponse)) {
                    $username = (string) $formDataResponse['username'];
                    $password = (string) $formDataResponse['password'];
                    $result = $this->oAuth->Authorization($username, $password);
                    $this->view->ZendAuthResult = true;
                    switch ($result->getCode()) {

                        case Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND:
                            /** do stuff for nonexistent identity * */
                            $this->view->ZendAuthResult = false;
                            $this->view->ZendAuthResult = false;
                            /* logujemy błedne dane */
                            Library_History::instans()->set(array('type' => 0,
                                'class' => __CLASS__,
                                'method' => __METHOD__,
                                'query' => array('results' => 'FAILURE_IDENTITY_NOT_FOUND',
                                    'spy' => $userSpy->getUserInformation,
                                    'login' => $username,
                                    'password' => $password)));
                            break;

                        case Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID:
                            /** do stuff for invalid credential * */
                            $this->view->ZendAuthResult = false;
                            /* logujemy błedne dane */
                            Library_History::instans()->set(array('type' => 0,
                                'class' => __CLASS__,
                                'method' => __METHOD__,
                                'query' => array('results' => 'FAILURE_CREDENTIAL_INVALID',
                                    'spy' => $userSpy->getUserInformation,
                                    'login' => $username,
                                    'password' => $password)));
                            break;

                        case Zend_Auth_Result::SUCCESS:
                            $requesturi = Zend_Registry::get('requesturi');
                            $this->view->ZendAuthResult = true;
                            /* wyciagamy z rejestru adres uri do połaczenia */
                            if (empty($requesturi)) {
                                $this->_redirect($this->_request->getBaseUrl());
                                /* przekierowujem do głownego modułu */
                            } else {
                                $this->_redirect($this->_request->getBaseUrl() . $requesturi);
                                /* wykonujemy poprzednia akcje */
                            }
                            break;

                        default:
                            /** do stuff for other failure * */
                            $this->view->ZendAuthResult = false;
                            /* logujemy błedne dane */
                            Library_History::instans()->set(array('type' => 0,
                                'class' => __CLASS__,
                                'method' => __METHOD__,
                                'query' => array('results' => 'other',
                                    'spy' => $userSpy->getUserInformation,
                                    'login' => $username,
                                    'password' => $password)));
                            break;
                    }
                }
            } else {
                $this->view->ZendAuthResult = true;
                $AuthForm = new AuthForm();
            }
            $this->view->AuthForm = $AuthForm;

            //$this->_helper->viewRenderer->setNoRender();
        } catch (Library_Exception $e) {

            return false;
        }
    }

    /**
     * @author Arnold Sikorski
     *
     * Zmiana hasła
     */
    /*
      public function changepasswordAction() {
      // $this->_helper->layout->disableLayout();
      if ($this->oUser == 'roota') {
      $this->view->Change = false;
      } else {
      $this->view->Change = true;
      $ChangePasswordForm = new ChangePasswordForm();
      $this->view->ChangePasswordForm = $ChangePasswordForm;
      }
      }
      public function newadminAction(){
      $NewAdminForm = new NewAdminForm();
      $this->view->NewAdminForm = $NewAdminForm;
      }

     */

    /**
     * @author: Arnold Sikorski
     * Zmiana danych usera
     * 
     */
    public function userAction() {
        $oUser = Zend_Registry::get('oUser');
        $Array = new Library_Array();
        $UserArray = $Array->object_to_array($oUser);
        unset($UserArray['id']);
        unset($UserArray['password']);
        unset($UserArray['group']);

        $UserEdit = new UserForm($UserArray);
        $this->view->UserForm = $UserEdit;
    }

    /**
     * @author Arnold Sikorski
     *
     * Zmie
     */
    public function passwordAction() {
        $User = new Library_Users(array('_name' => 'cm_admins'));
        /* podłaczenia klasy użytkowników */
        $this->view->headScript()->appendFile($this->view->baseUrl() . '/js/GeneratePassword.js');
        if ($this->_request->isPost()) {
            $formDataResponse = $this->_request->getPost();
            $ChangePasswordForm = new ChangePasswordForm($formDataResponse);
            if ($ChangePasswordForm->isValid($formDataResponse)) {
                if ($formDataResponse['new_password'] == $formDataResponse['new_password_repeat']) {
                    /* sprawdzam zgodność starego hasła */
                    //$User->
                    $oUser = Zend_Registry::get('oUser');
                    //Moje dane
                    if (md5($formDataResponse['old_password']) == $oUser->password) {
                        $newPassword = md5($formDataResponse['new_password']);

                        $User->setUser((int) $oUser->id, array('password' => $newPassword));
                        $this->view->msg = 'Hasło zostało zmienione';
                        $this->view->result = true;
                        $this->view->change = true;
                    } else {
                        $this->view->msg = 'Podane hasło jest błędne';
                        $this->view->result = false;
                    }
                } else {
                    $this->view->msg = 'Hasła sa różne';
                    $this->view->result = false;
                }
            } else {
                $this->view->msg = 'Walidator zwrócił błąd';
                $this->view->result = false;
            }
        } else {
            $ChangePasswordForm = new ChangePasswordForm();
            $this->view->result = true;
        }


        $this->view->ChangePasswordForm = $ChangePasswordForm;
    }

}
