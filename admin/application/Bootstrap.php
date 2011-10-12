<?php

/**
 * @author Arnold Sikorski
 * @category Bootstrop
 *
 */
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {
    /* ------------------------------------------------------------------------ */

    /**
     * @author Arnold Sikorski
     */
    protected function _initAutoload() {
        try {
            $autoloader = Zend_Loader_Autoloader::getInstance();
            $autoloader->setFallbackAutoloader(true);

            return $autoloader;
        } catch (Library_Exception $e) {

            return false;
        }
    }

    /* ------------------------------------------------------------------------ */

    /**
     * @author Arnold Sikorski
     */
    protected function _initController() {
        try {
            $this->bootstrap('FrontController');
            $controller = $this->getResource('FrontController');
            $modules = $controller->getControllerDirectory();
            $controller->setParam('prefixDefaultModule', true);
            $controller->registerPlugin(new Modules_Loader($modules));
            return $controller;
        } catch (Library_Exception $e) {

            return false;
        }
    }

    /* ------------------------------------------------------------------------ */

    /**
     * @author Arnold Sikorski
     */
    protected function _initWidgets() {
        try {

            $WidgetAutoloader = new Zend_Application_Module_Autoloader(array(
                        'namespace' => '',
                        'basePath' => dirname(__FILE__),
                    ));
            $WidgetAutoloader->addResourceType('widgets', 'widgets', 'widgets_');
            //var_dump($WidgetAutoloader);
            return $WidgetAutoloader;
        } catch (Library_Exception $e) {

            return false;
        }
    }

    /* ------------------------------------------------------------------------ */

    /**
     * @author Arnold Sikorski
     */
    protected function _initSessionNamespace() {
        try {
            Zend_Session::start();
            $defaultNamespace = new Zend_Session_Namespace('UnicornCMS');
            /* ustawiam sesje */
            Zend_Registry::set('Session', $defaultNamespace);
            /* sesja w rejeestrze */
            return true;
        } catch (Library_Exception $e) {

            return false;
        }
    }

    /* ------------------------------------------------------------------------ */

    /**
     * @author Arnold Sikorski
     */
    protected function _initMultipleLanguage() {
        try {
            $front = Zend_Controller_Front::getInstance();
            $front->registerPlugin(new Plugins_Language());
            return true;
        } catch (Library_Exception $e) {

            return false;
        }
    }

    /* ------------------------------------------------------------------------ */

    /**
     * Inicjalizacja połączenia z baza danych
     * @return <type>
     */
    protected function _initDatabase() {
        try {
            $resource = $this->getPluginResource('db');
            $oDb = $resource->getDbAdapter();
            $oDb->getProfiler()->setEnabled(true);
            $oDb->query("SET CHARSET utf8");
            Zend_Db_Table_Abstract::setDefaultAdapter($oDb);
            Zend_Registry::set('dbAdapter', $oDb);
            return true;
        } catch (Library_Exception $e) {

            return false;
        }
    }

    /* ------------------------------------------------------------------------ */

    /**
     * @author Arnold Sikorski
     */
    protected function _initRoutes() {
        try {
            $front = Zend_Controller_Front::getInstance();
            $router = $front->getRouter();
            $options = new Zend_Config_Ini(APPLICATION_PATH . '/configs/routes.ini');

            $router->addConfig($options, 'routes');
            //$router->addRoute('default', new Zend_Controller_Router_Route(':language', array('language' => 'pl')));
            return true;
        } catch (Library_Exception $e) {

            return false;
        }
    }

    /* ------------------------------------------------------------------------ */

    /**
     * @author Arnold Sikorski
     */
    protected function _initErrorHandler() {
        try {
            $plugin = new Zend_Controller_Plugin_ErrorHandler();
            $plugin->setErrorHandlerModule('static')
                    ->setErrorHandlerController('error')
                    ->setErrorHandlerAction('error');
            $front = Zend_Controller_Front::getInstance();
            $front->registerPlugin($plugin);
            return true;
        } catch (Library_Exception $e) {

            return false;
        }
    }

    /* ------------------------------------------------------------------------ */

    /**
     * @author Arnold Sikorski
     */
    protected function _initDebbugerLogger() {
        try {
            $front = Zend_Controller_Front::getInstance();
            $front->registerPlugin(new Plugins_DebbugerLogger());
            return true;
        } catch (Library_Exception $e) {

            return false;
        }
    }

    /* ------------------------------------------------------------------------ */

    /**
     * @author Arnold Sikorski
     */
    protected function _initConfiguration() {
        try {
            $options = new Zend_Config_Ini(APPLICATION_PATH . '/../../config/configs.ini', APPLICATION_ENV);
            $gConfig = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
            /* Load files */
            Zend_Registry::set('configuration', $gConfig);
            Zend_Registry::set('options', $options);
            /* set into registry */
            return true;
        } catch (Library_Exception $e) {

            return false;
        }
    }
        /* ------------------------------------------------------------------------ */

    /**
     * Cachowanie aplikacji
     * @author Arnold Sikorski
     * @return <type> null
     */
    protected function _initCache() {

        try {
            /* cachujemy jedynie w wersji produkcyjnej */
            if (APPLICATION_ENV == 'production') {

                $cacheManager = $this->getPluginResource('cachemanager')->getCacheManager();
                Zend_Registry::set('Cache', $cacheManager->getCache('global'));
                Zend_Date::setOptions(array('format_type' => 'php', 'cache' => $cacheManager->getCache('global')));
                Zend_Translate::setCache($cacheManager->getCache('global'));
                Zend_Locale::setCache($cacheManager->getCache('global'));
                Zend_Db_Table_Abstract::setDefaultMetadataCache($cacheManager->getCache('database'));
                return true;
            }
        } catch (Library_Exception $e) {

            return false;
        }
    }


}
