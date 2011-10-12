<?php

/*
 * @Author: Arnold Sikorski
 * @Version: 1.0
 * @Name: System autoryzacji użytkownika
 *
 * Plugin sprawdza czy user jest uwierzetelniony, jeśli jest zezwala na dostep do panelu
 * w przeciwnym razie wyświetla panel logowania
 */

class Auth_Login extends Zend_Controller_Plugin_Abstract {
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

    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        try {
            $hash = md5(date('Y-m-d'));
            $query = $request->getQuery();

            //hash generowany do uwierzytelnienia wywołań flashowych i ajaxowych
            $oAuthUser = new Auth_Authorization();
            //inicjalizuje klase autoryzacji
            if (!$oAuthUser->CheckStatus()) {
                // przekieruj na kontroler logowania
                // var_dump(APPLICATION_PATH);
                //$module = $request->getModuleName();
                if (isset($query['action'])) {
                    /* akcje nie potrzebujace uwierzytelnienia */
                    switch ($query['action']) {
                        case 'upload':
                            //Uploadujemy plik
                            $uploader = new Upload_Files($request);
                            die();
                            break;
                    }
                } else {
                    $request->setModuleName($this->_auth_module)
                            ->setControllerName('index')
                            ->setActionName('authorization');
                    $module = $this->_auth_module;
                    $request_uri = $_SERVER["REQUEST_URI"];
                    Zend_Registry::set('requesturi', $request_uri);
                    //Dodajemy dane usera do rejestru
                    //die();
                }
                /* ok */
            } else {
                $module = $request->getModuleName();
                //echo $module;
                Zend_Registry::set('modulename', $module);
                // die();
            }
            $bootstrapPath = $this->_modules[$module];

            $bootstrapFile = dirname($bootstrapPath) . '/Bootstrap.php';
            $class = ucfirst($module) . '_Bootstrap';
            $application = new Zend_Application(
                            APPLICATION_ENV,
                            APPLICATION_PATH . '/modules/' . $module . '/configs/module.ini'
            );

            if (Zend_Loader::loadFile('Bootstrap.php', dirname($bootstrapPath))
                    && class_exists($class)) {
                $bootstrap = new $class($application);

                $bootstrap->bootstrap();
            }
        } catch (Library_Exception $e) {

            return false;
        }
    }

}