<?php

/* @Author: Arnold Sikorski
 * @Date: 11-05-2011
 *
 * Widget wyświetla wszystkie aktywne moduły w psotaci Header Bar
 */

class Widgets_ModuleTree_Init extends Tree_Controller {

    public $oView;
    public $oConfig;
    /* Ustawienia do połączenia z baza danych */
    protected $_name = 'cm_modules_pl';
    protected $_primary = 'id';

    public function __construct() {

        $ScriptPath = realpath(dirname(__FILE__));
        //Ustawiam zend view
        $this->oView = new Zend_View();
        //inicjalizujemy klase widoków
        $this->oView->setScriptPath($ScriptPath . '/view');
        //ustawiamy sciezke do widoku
        $this->oView->id = substr(trim(md5(uniqid(rand(), true))), 0, 6);
        //losowy ciag znakowy
        return true;
    }

    /*
     * Wyswietlanie Kontentu widgetu{
     */

    public function render() {
        /*buduje drzewo na podstawie listy modułów w bazie danych*/
        return $this->oView->render('bar.phtml');
    }

    public function renderArrayModules(obj $ModulesList) {
        /* Renderuje wybrane przez użytownika moduły -> moduły w postaci tablicy asocjacyjnej nazwa_modułu => */
        if (is_object($ModulesList)) {
            $this->oView->oModuleList = $ModulesList;
            return $this->oView->render('bar.phtml');
        } else {
            return false;
        }
    }

    public function getModulesFromBase($access) {
        /* metoda pobiera liste zaisntalowanych modułow z bazy danych */
    }

}
