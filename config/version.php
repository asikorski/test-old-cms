<?php

/**
 * @author: Arnold Sikorski
 *
 * CMS 4.0 Wersja Alpha 0.1
 *
 * Kod pisany na szybko, z wielką iloscia błędów niedociagnieć jeśli macie problemy
 * ze zrozumieniem , to jest to spowodowane niechlujstwem i duzym stopniem komplikacji struktury
 * generalnie wina lezy po stronie terminów ;)
 *
 * Powodzenia :)
 *
 * @todo:
 *
 * Dodano obsługe wielu wersji, development natywnie jako lokalna
 *
 * AD. 02-06-2011 :)
 */

/**
 * Tryb aplikacji
 */
switch (APPLICATION_ENV) {
    case 'development':
        /*tryb developerski*/
        $zendPath = '../../../ZendLibrary';
            #konfiguracja lokalna
        break;
    case 'production':
        /*tryb produkcji*/
        $zendPath = '../../../../ZendLibrary';
            #Konfiguracja dla production
        break;
    case 'testing':
        $zendPath = '/';
            #nieskonfigurowano
        break;
    default:
        die('Bad APPLICATION_ENV');
        break;
}


/*
 * Sprawdzanie czy mamy podpiety zend
 */
if (!file_exists($zendPath) OR (file_exists($zendPath) && filetype($zendPath) !== 'dir')) {
    die("Zend Include path '{$zendPath}' not exists, check version.php file (on main directory)");
} else {
    set_include_path(get_include_path() . PATH_SEPARATOR . $zendPath);
}
/* Checking files, and directory extis */

//Config file
$configFile = '../../config/configs.ini';
if (!file_exists($configFile)) {
    die("Aplication config file  '{$configFile}' not exists, check version.php file (on main directory)");
}

#path to zend framework library
define("CMS_VERSION", "4.0");
define("CMS_DATE", "27-08-2011");
define("CMS_VERSION_INDEX", "0.6");
define("CMS_VERSION_STATUS", "Beta");
define("CMS_LANG", "pl");
define("CMS_LOG_MAIL", "cms_logs@milleniumstudio.pl");
define("CMS_CONTACT_MAIL", "cms_contact@milleniumstudio.pl");
define("MILLENIUMSTUDIO_URL", "http://www.milleniumstudio.pl/");

#dane wirtualizacji
define("CLIENT_URL", "http://www.test.pl/");
define("CLIENT_MAIL", "kontakt@test.pl");
define("CLIENT_NAME", "Firma testowa");
#hashCode
define("HASH_CODE_CMS", md5(CLIENT_NAME . CLIENT_MAIL . CMS_DATE . CMS_VERSION . CMS_VERSION_INDEX));
?>
