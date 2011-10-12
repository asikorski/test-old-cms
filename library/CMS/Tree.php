<?php

class CMS_Tree extends Zend_Db_Table_Abstract {

    private $config;
    protected $_name;
    private $log = true;
    public $cacheTree;

    /**
     *
     * @param <type> $config - tablica konfiguracyjna klasy
     */
    public function __construct($config = array()) {
        try {
            parent::__construct();
            $this->_name = $config['name'];
            $this->config = $config;

            return true;
        } catch (Library_Exception $e) {

            return false;
        }
    }

    /**
     *
     * @param <type> $idRoot
     * @return <type>
     */
    public function getRoot($idRoot) {
        try {
            $row = $this->IdToRow($idRoot);
            return $row;
        } catch (Library_Exception $e) {
            return false;
        }
    }

    private function IdToRow($idRoot) {

        try {
            $select = $this->select();
            $select->where('id = ?', $idRoot);

            $response = $this->_db->fetchRow($select, array(), Zend_Db::FETCH_ASSOC);

            return $response;
        } catch (Library_Exception $e) {
            return false;
        }
    }

    /**
     * Wyszukiwanie elementów w drzewie
     *
     * @param <type> $string - Wyszukiwany ciag , moze być pusty jezeli $column jest tablica asocajacyjna
     * @param <type> $colums - tablica kolumn po ktorych wyszukujemy jeśli jest asocjacyjna to kolumna jest klucz natomiast wyszukiwanym ciagiem element tablicy
     * @param <type> $idRoot - id rodzica po którym wyszukujemy - moze być puste
     * @param <type> $order - tablica elementów po których sortujemy
     * @param <type> $type - typ wyszukiwania standardowo 'IN BOOLEAN MODE'
     * @return <type> - tablica wyników wyszukiwania
     */
    public function search($string = null, $colums = array(), $idRoot = null, $order = array(), $type = 'IN BOOLEAN MODE') {
        try {
            if (is_int($idRoot) && is_array($order)) {
                //OR MATCH (`desc`) AGAINST('".$string."' IN BOOLEAN MODE)

                /* Gereuje zapytanie */
                $whereQuery = null;
                if (is_null($string)) {
                    foreach ($colums as $key => $column) {
                        if (is_null($whereQuery)) {
                            $or = null;
                        } else {
                            $or = ' OR ';
                        }
                        $whereQuery = $whereQuery . $or . " MATCH (`" . $key . "`) AGAINST ('" . $column . "'  " . $type . ")";
                    }
                } else {
                    foreach ($colums as $column) {
                        if (is_null($whereQuery)) {
                            $or = null;
                        } else {
                            $or = ' OR ';
                        }
                        $whereQuery = $whereQuery . $or . " MATCH (`" . $column . "`) AGAINST ('" . $string . "'  " . $type . ")";
                    }
                }
                /* zapytanie wygenerowano */
                /* generuje zapytanie sortujace wyniki */
                $select = $this->select();
                $select->where($whereQuery)
                        ->order($order);
                if (!is_null($idRoot)) {
                    $select->where('parentID = ?', $idRoot);
                }
                $SearchedItems = $this->_db->fetchAll($select, array(), Zend_Db::FETCH_ASSOC);

                return $SearchedItems;
            } else {

                return false;
            }
        } catch (Library_Exception $e) {
            return false;
        }
    }

    /**
     *
     * @param <type> $url - adres url
     * @return <type> - zwracam talibice danych
     */
    public function getCategoryByUrl($url) {
        try {
            if ($url) {

                $select = $this->select();
                $select->where('name_url = ?', $url);
                $response = $this->_db->fetchRow($select, array(), Zend_Db::FETCH_ASSOC);

                return $response;
            } else {
                return false;
            }
        } catch (Library_Exception $e) {

            return false;
        }
    }

    /**
     *
     * @param array $query zapytanie
     * @return <type>  - tablica elementów
     */
    public function getCategoryBy(array $query = array()) {
        try {
            if (is_array($query)) {
                if (count($query) == 1) {
                    $field = key($query);
                    $value = $query[$field];
                    //wartosci pol
                    $select = $this->select();
                    $select->where($field . ' = ?', $value);
                    $response = $this->_db->fetchRow($select, array(), Zend_Db::FETCH_ASSOC);

                    return $response;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } catch (Library_Exception $e) {

            return false;
        }
    }

    /**
     *
     * @param <type> $idRoot - identyfikator elementu
     * @return <type> - tablica elementów
     */
    public function _RootToArray($idRoot=0) {
        if ($idRoot >= 0) {
            $row = $this->IdToRow($idRoot);
            if (!$row) {
                $row = $this->IdToRow(0);
            }
            $select = $this->select();
            $select->where('ip like CONCAT("' . $row['ip'] . '.%")');
            $cat = $this->_db->fetchAll($select, array(), Zend_Db::FETCH_ASSOC);
            $cat[] = $row;
            foreach ($cat as $element) {
                $TempCat[$element['parentID']][$element['id']] = $element;
            }
            return $TempCat;
        } else {

            return false;
        }
    }

    /**
     *
     * @param <type> $idRoot -
     * @return <type> 
     */
    public function _RootToArrayTree($idRoot=1) {
        $issetArray = $this->IdToRow($idRoot);
        if ($issetArray) {
            $cTree = $this->_RootToArray($idRoot);

            $this->_GenerateRecursiveTree(1, $cTree);
            if (!$this->cacheTree) {
                $this->cacheTree = $issetArray;
            }

            return $this->cacheTree;
        } else {
            return false;
        }
    }

    public function test() {
        return false;
    }

    /**
     *
     * @param <type> $parentID
     * @param <type> $tab
     * @return <type> 
     */
    private function _GenerateRecursiveTree($parentID, $tab) {
        if (!isset($tab[$parentID]) || !is_array($tab[$parentID])) {
            return false;
        } else {
            // echo '<ul>';

            foreach ($tab[$parentID] as $element) { #iteracja podkategorii
                // echo "<li>";
                // print_r($element);
                $cache[$element['id']] = array_merge($element);
                //  echo "</li>";
                $response = $this->_GenerateRecursiveTree($element['id'], $tab); #wyswietlenie podkategorii
                if ($response) {
                    $cache[$element['id']]['items'] = $response;
                }
                // echo '</li>';
                //$this->cacheTree = array(1=>2);
            }

            // echo '</ul>';
            $this->cacheTree = array_merge($cache);
            return $cache;
        }
    }

    /**
     * @author  Piotr Feć
     * 
     */
    public function getIdRootByUrl($url) {
        try {
            if (is_string($url)) {
                $select = $this->select();
                $select->where('name_url = ?', $url);
                $select->limit(1);
                $Items = $this->_db->fetchOne($select, array(), Zend_Db::FETCH_ASSOC);
                return $Items;
            } else {

                return false;
            }
        } catch (Library_Exception $e) {

            return false;
        }
    }
    
    /**
     * @author Piotr Feć:)
     * @param type $idRoot
     * @param type $depth
     * @return type 
     */
    public function getRootChild($idRoot, $depth=null) {
        try {
            $select = $this->select();
            $select->where('ip like "%.' . $idRoot . '.%"');
            return  $this->_db->fetchAll($select, array(), Zend_Db::FETCH_ASSOC);
        } catch (Library_Exception $e) {
            return false;
        }
    }

}
