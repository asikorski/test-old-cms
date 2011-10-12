<?php

/*
 * @Author: Arnold Sikorski
 * @Version: 1.0
 * @Name: System autoryzacji użytkownika
 *
 * Plugin sprawdza czy user jest uwierzetelniony, jeśli jest zezwala na dostep do panelu
 * w przeciwnym razie wyświetla panel logowania
 */

class Modules_Loader extends Zend_Controller_Plugin_Abstract {
    /*
     * System autoryzacji użytkownika
     */
    /*
      public $oAuth;
      public $oAuthTableName = 'cm_admins';
      public $oAuthAdapter;
     */

    protected $_modules;
    protected $_auth_module = 'auth';

    public function __construct(array $modulesList) {
        $this->_modules = $modulesList;
    }

    /**
     *
     * @param Zend_Controller_Request_Abstract $request - zwracam odpowiedni request
     */
    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request) {
        try {
            $hash = md5(date('Y-m-d'));
            $query = $request->getQuery();

            /* switcher akcji */

            if (isset($query['action'])) {
                /* wybór akcji */
                switch ($query['action']) {
                    case 'upload':
                        /* uploaduje plik */
                        //echo 'nazwa modulu: '.$request->getModuleName().' - end</br>';
                        $module = $request->getModuleName();
                        /* uploader */
                        $module = 'upload';
                        $request->setModuleName($module)
                                ->setControllerName('files')
                                ->setActionName('save');
                        Zend_Registry::set('modulename', $module);
                        break;
                    case 'status':
                        /*Sprawdzam status połączenia*/
                        $oAuthUser = new Auth_Authorization();
                        $jResponse['login'] = $oAuthUser->CheckStatus();
                        echo Zend_Json::encode($jResponse);
                        die();
                        break;
                }
                /* koniec wyboru */
            } else {
                $oAuthUser = new Auth_Authorization();
                if (!$oAuthUser->CheckStatus()) {
                    $request->setModuleName($this->_auth_module)
                            ->setControllerName('index')
                            ->setActionName('authorization');
                    $module = $this->_auth_module;
                    $request_uri = $_SERVER["REQUEST_URI"];
                    Zend_Registry::set('requesturi', $request_uri);
                } else {
                    $module = $request->getModuleName();
                    Zend_Registry::set('modulename', $module);
                }
            }

            /* swiecher akcji */


            $application = new Zend_Application(
                            APPLICATION_ENV,
                            APPLICATION_PATH . '/modules/' . $module . '/configs/module.ini'
            );
            set_include_path(implode(PATH_SEPARATOR, array(
                        realpath(APPLICATION_PATH . '/modules/' . $module . '/models'),
                        get_include_path(),
                    )));
            Zend_Layout::startMvc(array(
                        'layoutPath' => APPLICATION_PATH . "/views/layouts",
                        'layout' => 'default'
                    ));
            $view = Zend_Layout::getMvcInstance()->getView();
            $view->addHelperPath(APPLICATION_PATH . "/views/helpers", "Global_Helpers");
            $view->addHelperPath(APPLICATION_PATH . '/../../library/helpers', "Global_View_Helper_");
            //Dodaje globalne helpery
        } catch (Library_Exception $e) {

            return false;
        }
    }

}