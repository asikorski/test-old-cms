<?php

/**
 * @author Arnold Sikorski
 * @category CMS mainConnector
 * @example Poczytaj to zrozumiesz
 * @name CMS_Connection
 * 
 *
 * Klasa umożliwiajaca połączenie z bibliotekami systemu zarzadzania trescia
 *
 * 13-09-2011
 */
class CMS_Connection {

    static public $version = '1.0';
    private $oItems;
    private $oRelations;
    private $oTree;
    private $tablePrefix = 'cm';
    private $tables = array(
        'relations' => 'relations',
        'tree' => 'categories',
        'items' => 'elements');

    public function __construct(array $config = array()) {
        try {
            $conf = array('module' => null,
                'lang' => null,
                'baseURL' => null,
            );
            $FinalConf = array_merge($conf, $config);
            $this->module = $FinalConf['module'];
            $this->lang = $FinalConf['lang'];
            #marguje tablice
            $this->_relationTable = $this->tablePrefix . '_' . $FinalConf['module'] . '_' . $this->tables['relations'] . '_' . $FinalConf['lang'];
            $this->_sitesTable = $this->tablePrefix . '_' . $FinalConf['module'] . '_' . $this->tables['items'] . '_' . $FinalConf['lang'];
            $this->_treeTable = $this->tablePrefix . '_' . $FinalConf['module'] . '_' . $this->tables['tree'] . '_' . $FinalConf['lang'];
            #tworzymy tabele
            $this->baseURL = $FinalConf['baseURL'];

            /* podpinam odpowiednie moduły kontrolne */
            $this->oSites = new CMS_Items(array('name' => $this->_sitesTable));
            $this->oTree = new CMS_Tree(array('name' => $this->_treeTable));
            $this->oRelations = new CMS_Relations(array('name' => $this->_relationTable));
        } catch (Library_Exception $e) {
            /* przechwytujemy błędy */
            return false;
        }
    }

    /*
     * ------------------------------------------------------------------------
     * Items
     * ------------------------------------------------------------------------
     */

    /**
     * --------------------------------------------------------------------------
     * @param <type> $id - Identyfikator elementu
     * @param <type> $files - Czy maja być zawarte pliki
     */
    public function getItemById($id, array $files = array(true, 'ord ASC')) {
        if (is_int($id)) {
            $row['item'] = $this->oSites->getItemFromId((int) $id);
            if ($files[0]) {
                $filesRows = $this->oRelations->getRelationsFromParent($id, array($files[1]));
                $row = array_merge($row, array('files' => $filesRows));
            }
            return ($row) ? $row : false;
        } else {
            return false;
        }
    }

    /**
     * --------------------------------------------------------------------------
     * @param <type> $url - adres url
     * @param <type> $files -czy mamy pobierać pliki
     * @return <type> tablica
     */
    public function getItemByUrl($url, $files = array(true,array('ord ASC'))) {
        /**
         * Poczatkowo validujemy poprawność wpisanego adresu nastepnie pobieramy dane z bazy
         */
        if (preg_match("/^[a-zA-Z0-9 _-]*/", $url)) {
            $row['item'] = $this->oSites->getItemByUrl($url);
            if ($files[0] && $row['item']) {
                $filesRows['files'] = $this->oRelations->getRelationsFromParent((int) $row['item']['id'], $files[1]);
                $row = array_merge($row, $filesRows);
            }
            return ($row) ? $row : false;
        } else {
            return false;
        }
    }

    /**
     *
     * @param <type> $query - zapytanie
     * @param <type> $files - czy maja byc pliki
     * @return <type>
     */
    public function getItemBy($query, array $files = array(true)) {
        if ($query) {
            $row['item'] = $this->oSites->getItemByQuery($query);
            if ($files[0] && $row['item']) {
                $filesRows = $this->oRelations->getRelationsFromParent((int) $row['item']['int'], $files[1]);
                $row = array_merge($row, $filesRows);
            }
            return ($row) ? $row : false;
        } else {
            return false;
        }
    }

    /**
     *
     * @param <type> $category - kategoria po której pobieramy elementy
     * @param <type> $files - tablica , pierwszy element okresla czy pliki maja zostac podpiete drugi typ sortowani
     * @param array $order - sposob sortowania wyników
     * @return <type> - tablica elementow
     */
    public function getItemsByCategory($category, array $files = array(false,array('ord ASC')), array $order=array(), $limit=null) {

        if (is_int($category) && is_array($order)) {
            $rows = $this->oSites->getItemsByCategory($category, $order, $limit);
            if ($rows) {
                if ($files[0]) {
                    foreach ($rows as $key => $row) {
                        unset($filesRows);
                        $filesRows = $this->oRelations->getRelationsFromParent((int) $row['id'], $files[1]);
                        $items[]['item'] = array('row' => $row,
                            'files' => $filesRows);
                    }
                } else {
                    $items = $rows;
                }
                return $items;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     *
     * @param <type> $query
     * @param <type> $files
     * @param <type> $order
     * @return <type>
     */
    public function getItemsBy($query, $files, $order, $limit=null) {
        if (is_array($query) && is_array($order)) {
            $rows = $this->oSites->getItemsBy($query, $order,$limit);
            if ($rows) {
                if ($files) {
                    foreach ($rows as $key => $row) {
                        unset($filesRows);
                        $filesRows = $this->oRelations->getRelationsFromParent((int) $row['id'], array());
                        $items[]['item'] = array('row' => $row,
                            'files' => $filesRows);
                    }
                } else {
                    $items = $rows;
                }
                return ($items) ? $items : false;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function removeItem($id, $relation) {

    }

    public function addNewItem($row) {

    }

    public function setItem($id, $row) {

    }

    /**
     *
     * @param <type> $category_id
     * @param <type> $string
     * @param <type> $colums
     * @param <type> $order
     * @param <type> $files
     * @param <type> $type
     */
    public function serachItems($category_id, $string = null, $colums = array(), $order = array(), $files =array(), $type = 'IN BOOLEAN MODE') {
        $rows = $this->oSites->search($category_id, $string, $colums, $order, $type);
        if ($rows) {
            if ($files[0]) {
                foreach ($rows as $key => $row) {
                    unset($filesRows);
                    $filesRows = $this->oRelations->getRelationsFromParent((int) $row['id'], $files[1]);
                    $items[]['item'] = array('row' => $row,
                        'files' => $filesRows);
                }
            } else {
                $items = $rows;
            }
            return ($items) ? $items : false;
        } else {
            return false;
        }
    }

    /*
     * ------------------------------------------------------------------------
     * Categories
     * ------------------------------------------------------------------------
     */

    /**
     *
     * @param <type> $id - identyfikator elementu
     * @return <type> - tablice z danymi
     */
    public function getCategoryById($id) {
        if (is_int($id)) {
            $row = $this->oTree->getRoot($id);
            return ($row) ? $row : false;
        } else {
            return false;
        }
    }

    /**
     *
     * @param <type> $url - adres url
     * @return <type>
     */
    public function getCategoryByUrl($url) {
        if ($url) {
            $row = $this->oTree->getCategoryByUrl($url);
            return ($row) ? $row : false;
        } else {
            return false;
        }
    }

    /**
     *
     * @param array $query - zapytanie o którym mamy pobierac dane
     * @return <type> - tablica danych
     */
    public function getCategoryBy(array $query = array()) {
        if (is_array($query)) {
            $row = $this->oTree->getCategoryBy($query);
            return ($row) ? $row : false;
        } else {
            return false;
        }
    }

    /**
     *
     * @param <type> $parentId - identyfiaktor rodzica
     * @param array $order - typ sortowania elementów
     * @return <type>
     */
    public function getCategoriesArray($parentId, array $order = array('ord ASC')) {
        if (is_int($parentId) && is_array($order)) {
            $rows = $this->oTree->_RootToArrayTree(1);
            return ($rows) ? $rows : false;
        } else {
            return false;
        }
    }

    public function getCategoriesBy($ByQuery) {

    }

    public function addNewCategory($parent, $row) {

    }

    public function setCategory($id, $row) {

    }

    public function removeCategory($id, $byItems) {

    }

    public function searchCategory($query, $order) {
        
    }

    /* files and relation */

    /**
     *
     * @param <type> $id - iden
     * @return <type>
     */
    public function getRelationById($id) {
        if (is_int($id)) {
            $row = $this->oRelations->getRelationById($id);
            return ($row) ? $row : false;
        } else {
            return false;
        }
    }
    
    /**
     * Wyciaga elemenenty kategori po adresie URL
     * @author Fec Piotr:) 
     * @param <type> $id_parent
     * @return <type>
     */
    public function getItemsByParentUrl($url , $isFile = true, $limit = null) {
        if (is_string($url)) {
            $idRoot = $this->oTree->getIdRootByUrl($url);
            $rows = $this->getItemsByCategory((int)$idRoot, array(true,array('id ASC')), array(), $limit);

            if ($rows) {
                return $rows;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    /**
     * Wyciaga kategorie po id roota
     * @author Piotr Feć:)
     * @param type $idRoot id
     * @return type 
     */
    public function getRootChild($idRoot){
        return  $this->oTree->getRootChild($idRoot);
    }
    /**
     *
     * @param type $idParentRoot
     * @param type $data
     * @return type 
     */
    public function addItem($idParentRoot, $data=array()){
        return $this->oSites->addItem($idParentRoot, $data);
    }
}
