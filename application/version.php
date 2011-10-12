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
 * AD. 02-06-2011 :)
 */
set_include_path(get_include_path().PATH_SEPARATOR.'/home/piotrek/ZendLibrary');
	#path to zend framework library
define("CMS_VERSION", "4.0");
define("CMS_DATE", "02-06-2011");
define("CMS_VERSION_INDEX", "0.1");
define("CMS_VERSION_STATUS", "Alpha");
define("CMS_LOG_MAIL", "cms_logs@milleniumstudio.pl");
define("CMS_CONTACT_MAIL", "cms_contact@milleniumstudio.pl");
define("MILLENIUMSTUDIO_URL", "http://www.milleniumstudio.pl/");
        #dane wirtualizacji
define("CLIENT_URL", "http://www.test.pl/");
define("CLIENT_MAIL", "kontakt@test.pl");
define("CLIENT_NAME", "Firma testowa");
       #hashCode
define("HASH_CODE_CMS", md5(CLIENT_NAME.CLIENT_MAIL.CMS_VERSION.CMS_VERSION_INDEX));

?>
