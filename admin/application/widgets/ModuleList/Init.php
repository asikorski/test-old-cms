<?php
/* @Author: Arnold Sikorski
 * @Date: 11-05-2011
 *
 * Widget wyświetla wszystkie aktywne moduły w psotaci Header Bar
 */

class Widgets_ModuleList_Init {

    public $oView;
    public $oConfig;
    /* Ustawienia do połączenia z baza danych */
    private $ModulesTree;
    public $ModuleTreeHtml;

    public function __construct() {

        $ScriptPath = realpath(dirname(__FILE__));
        //Ustawiam zend view
        $this->oView = new Zend_View();
        //inicjalizujemy klase widoków
        $this->oView->setScriptPath($ScriptPath . '/view');
        //ustawiamy sciezke do widoku
        $this->oView->id = substr(trim(md5(uniqid(rand(), true))), 0, 6);
        //losowy ciag znakowy
        $this->ModulesTree = new Library_Tree(array('name' => 'cm_modules_tree_pl'));
        if (Zend_Registry::isRegistered('oUser')) {
            $oUser = Zend_Registry::get('oUser');
        }else{
            $oUser = false;
        }
        $this->oView->user =  $oUser;
        
        //$this->view->userName =
        return true;
    }

    /*
     * Wyswietlanie Kontentu widgetu{
     */

    public function render() {
        /* buduje drzewo na podstawie listy modułów w bazie danych */
        $ModulesTree = $this->ModulesTree->_RootToArray(0);
        return $this->oView->render('bar.phtml');
    }

    private function display($parentID, $tab) {

        if (!isset($tab[$parentID]) || !is_array($tab[$parentID]))
            return true;
        $this->ModuleTreeHtml = $this->ModuleTreeHtml . ' <ul>';
        foreach ($tab[$parentID] as $element) { #iteracja podkategorii
            $this->ModuleTreeHtml = $this->ModuleTreeHtml . ' <li class="memu-root left">';
?> <li id="rhtml_<?php echo $element['id']; ?>" value ="<?php echo $element['id']; ?>">
                <a rel ="123" href="<?php echo $this->ParmsURL(array(), array('category_id' => $element['id'], 'page' => 1)); ?>">
        <?php echo $element['name']; ?></a>

    <?
            $this->display($element['id'], $tab); #wyswietlenie podkategorii
            $this->ModuleTreeHtml = $this->ModuleTreeHtml . ' </li>';
        }
        $this->ModuleTreeHtml = $this->ModuleTreeHtml . ' </ul>';
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
