<?php

/**
 * @author Arnold Sikorski
 * @category Bootstrop
 *
 * Bootstrop aplikacji inicjalizatory opisane w kodzie
 */
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

    protected $_root;
    public $oSession;

    /**
     * Initialize Autoload
     *
     * @return Zend_Application_Module_Autoloader
     */
    protected function _initAutoload() {
        $autoloader = Zend_Loader_Autoloader::getInstance();
        //$autoloader->registerNamespace(array('Common_','Cms_','Site_'));
        $autoloader->setFallbackAutoloader(true);
        $module_autoloader = new Zend_Application_Module_Autoloader(array('namespace' => 'Default_', 'basePath' => dirname(__FILE__)));
        return $module_autoloader;
    }

    public function _initStart() {
        $this->_root = APPLICATION_PATH . '/../';
        date_default_timezone_set('Europe/Warsaw');
        $this->_config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
        Zend_Registry::set('configuration', $this->_config);

        Zend_Validate::setDefaultNamespaces('Zend_Validate');
        $this->_initDbDefaults();
    }

    protected function _initRoutes() {
        $front = Zend_Controller_Front::getInstance();
        $router = $front->getRouter();
        $options = new Zend_Config_Ini(APPLICATION_PATH . '/configs/routes.ini');
        $router->addConfig($options, 'routes');
    }

    /**
     * Initialize layout
     */
    protected function _initViewDefaults() {
       Zend_Controller_Action_HelperBroker::addPath(APPLICATION_PATH . '/view/helpers', 'Zend_Controller_Action_Helper');
    }

    /**
     * Initialize Cache
     */
    protected function _initCache() {
        // setup cache for other clases
        //$cacheManager = $this->getPluginResource('cachemanager')->getCacheManager();
        //Zend_Registry::set('Cache', $cacheManager->getCache('global'));
        //Zend_Date::setOptions(array('format_type' => 'php' , 'cache' => $cacheManager->getCache('global')));
        //Zend_Translate::setCache($cacheManager->getCache('global'));
        //Zend_Locale::setCache($cacheManager->getCache('global'));
        //Zend_Db_Table_Abstract::setDefaultMetadataCache($cacheManager->getCache('database'));
    }



    public function _initControllers() {
        $this->bootstrap('frontController');
        $this->frontController;
        
    }

    public function _initPublicVirables() {
        Zend_Registry::set('test', null);
    }

    protected function _initMultipleLanguage() {
//        $front = Zend_Controller_Front::getInstance();
//        $front->registerPlugin(new Plugins_Language());
//        return true;
    }

    protected function _initDbDefaults() {

        $resource = $this->getPluginResource('db');
        $oDb = $resource->getDbAdapter();
        $oDb->getProfiler()->setEnabled(true);
        $oDb->query("SET CHARSET utf8");
        Zend_Db_Table_Abstract::setDefaultAdapter($oDb);
        Zend_Registry::set('dbAdapter', $oDb);
    }
    /**
     * @author: Arnold Sikorski
     *
     * Wczytywanie globalnego pliku konfiguracji
     */
    protected function _initConfiguration() {
        $options = new Zend_Config_Ini(APPLICATION_PATH . '/../../config/configs.ini');
        Zend_Registry::set('options', $options);
        return true;
    }


}