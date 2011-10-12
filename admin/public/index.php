<?php
/**
 * @author: Arnold Sikorski
 *
 * Plik inicjalizujacy system zarzadzania trescia.
 *
 * - Definiujemy sciezki dostepu do aplikacji
 * - Podpinamy plik wersji
 * - .. wszystkie potrzebne biblioteki
 * - Inicjalizacja autoloadera
 * - Uruchamienie aplikacji (prawdodpobienstwo (99% ;))
 */

defined('BASE_PATH')
        || define('BASE_PATH', realpath(dirname(__FILE__)));

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', BASE_PATH . '/../application');


// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

/*Podstawowa biblioteka*/
require_once(APPLICATION_PATH . '/../../config/version.php');

// Podpinam odpowiednie foldery z bibliotekami
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    realpath(APPLICATION_PATH . '/../../library'),
    get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);
$application->bootstrap()
            ->run();
