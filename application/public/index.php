<?php

/**
 * @author: Arnold Sikorski
 *
 * Plik inicjalizujacy aplikacje
 *
 * - Definiujemy sciezki dostepu do aplikacji
 * - Podpinamy plik wersji
 * - .. wszystkie potrzebne biblioteki
 * - Inicjalizacja autoloadera
 * - Uruchamienie aplikacji (prawdodpobienstwo (99% ;))
 */
error_reporting(E_ALL);
#raportowanie błędów
//echo getenv('my-host');die;
ini_set('display_error', 'On');
defined('APPLICATION_PATH')
        || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));
#sciezka aplikacji
#environment

defined('APPLICATION_ENV')
        || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));
require_once(APPLICATION_PATH . '/../../config/version.php');
#versja cms , wczytanie ustawień dołączenie bibliteki zend
#Definiowanie environmentu

set_include_path(implode(PATH_SEPARATOR, array(
            realpath(APPLICATION_PATH . '/../library'),
            realpath(APPLICATION_PATH . '/../../library'),
            realpath(APPLICATION_PATH . '/models'),
            realpath(APPLICATION_PATH . '/widgets'),
            get_include_path(),
        )));
#zdefiniowanie folderów modeli i bibliotek

require_once 'Zend/Loader/Autoloader.php';
$loader = Zend_Loader_Autoloader::getInstance();
$loader->setFallbackAutoloader(true);
$loader->suppressNotFoundWarnings(false);
#definicja i uruchomienie autoloadera

require_once 'Zend/Application.php';
#definicja aplikacji
try {

    $application = new Zend_Application(
                    APPLICATION_ENV,
                    APPLICATION_PATH . '/configs/application.ini'
    );
} catch (Exception $e) {
    die($e);
}
try {

    $application->bootstrap()
            ->run();
} catch (Expection_Loader $e) {
    die($e);
}
// Create application, bootstrap, and run
