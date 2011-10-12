<?php

/**
 * @author Arnold Sikorski
 *
 * Klasa odpowiedzialna za obsługe drzew przy urzyciu kontrolera baz danych Zend_Db_Table_Abstract
 */
class Library_Tree extends Zend_Db_Table_Abstract {

    protected $_name;
    protected $_order = array('id DESC');
    protected $_primary = 'id';
    private $config;
    private $cacheTree;
    private $cacheTreeInteger;
    private $log = true;
    private $cacheJsTree;

    /**
     *
     * @param <type> $config - Zmina przechowywujaca tablice konfiguracji clasy Zend_Db_Table_Abstract
     */
    public function __construct($config = array()) {


        try {
            parent::__construct();
            $this->_name = $config['name'];

            $this->config = $config;
            if ($this->log) {
                Library_History::instans()->set(array('type' => 0,
                    'class' => __CLASS__,
                    'method' => __METHOD__,
                    'query' => $config));
            }
            return true;
        } catch (Library_Exception $e) {

            return false;
        }
    }

    /**
     *
     * @param <type> $parentRoot - id elementu do którego dodajemy korzeń
     * @param <type> $row - dane które wystepuja w strukturze które chcemy zapisać
     */
    public function addRoot($parentRoot, $row = array()) {
        try {
            if ($parentRoot >= 1) {
                $this->getAdapter()->query('LOCK TABLES ' . $this->_name . ' WRITE');

                $parentRow = $this->IdToRow($parentRoot);
                $cacheRow = array(
                    'parentID' => $parentRow['id'],
                    'depth' => $parentRow['depth'] + 1
                );
                $rRow = array_merge($cacheRow, $row);
                #margujemy tablice
                $id = $this->insert($rRow);

                $where = $this->getAdapter()->quoteInto('id = ?', $id);
                $tIp = $parentRow['ip'] . "." . $id;
                $uRow = array(
                    'ip' => $tIp
                );
                $this->update($uRow, $where);

                $this->getAdapter()->query('UNLOCK TABLES');
                if ($this->log) {
                    Library_History::instans()->set(array('type' => 0,
                        'class' => __CLASS__,
                        'method' => __METHOD__,
                        'query' => $row));
                }
                return true;
            } else {
                if ($this->log) {
                    Library_History::instans()->set(array('type' => 0,
                        'class' => __CLASS__,
                        'method' => __METHOD__,
                        'query' => array('type' => 'error', 'query' => array('parentRoot' => $parentRoot, 'row' => $row), 'msg' => 'bad parent number')));
                }
                return false;
            }
        } catch (Library_Exception $e) {

            return false;
        }
    }

    /**
     *  Metoda edytuje wybrany korzeń drzewa
     *
     * @param <type> $idRoot - identyfikator edytowanego korzenia
     * @param <type> $data - nowe dane do zapisu
     */
    public function setRoot($idRoot, $data = array()) {
        try {
            $row = $this->IdToRow($idRoot);
            $row = array_merge($row, $data);

            $where = $this->getAdapter()->quoteInto('id = ?', $row['id']);
            $this->update($row, $where);
            if ($this->log) {
                Library_History::instans()->set(array('type' => 0,
                    'class' => __CLASS__,
                    'method' => __METHOD__,
                    'query' => $row));
            }
            return true;
        } catch (Library_Exception $e) {
            return false;
        }
    }

    /**
     * Zwraca strukutre wraz z danymi danego korzenia
     *
     * @param <type> $idRoot - id korzenia
     */
    public function getRoot($idRoot) {
        try {
            $row = $this->IdToRow($idRoot);
            if ($this->log) {
                Library_History::instans()->set(array('type' => 0,
                    'class' => __CLASS__,
                    'method' => __METHOD__,
                    'query' => $row));
            }
            return $row;
        } catch (Library_Exception $e) {
            return false;
        }
    }

    /**
     * Przenosze wybrany korzeń do nowej lokalizacji - przenosze wraz z zawartoscia
     *
     * @param <type> $idRoot - identyfiaktor przenoszonego korzenia
     * @param <type> $idNewRoot - doecelowe miejsce przeniesienia
     */
    public function moveRoot($idRoot, $idNewRoot) {
        try {
            $newparent = $this->IdToRow($idNewRoot);
            $cat = $this->IdToRow($idRoot);

            if ($cat['depth'] > $newparent['depth']) {
                $set_depth = 'depth-' . ($cat['depth'] - $newparent['depth'] - 1); # o tyle ma sie zmieniac depth
            } elseif ($cat['depth'] == $newparent['depth']) {
                $set_depth = 'depth+1';
            } else {
                $set_depth = 'depth+' . ($newparent['depth'] - $cat['depth'] + 1); # o tyle ma sie zmieniac depth
            }
            $this->getAdapter()->query('LOCK TABLES ' . $this->_name . ' WRITE');
#blokujemy by nie namieszło nam w bazie
            $this->getAdapter()->query('UPDATE ' . $this->_name . ' SET ip="' . $newparent['ip'] . '.' . $cat['id'] . '", depth=' . $set_depth . ', parentID="' . $newparent['id'] . '" WHERE id="' . $cat['id'] . '"');
            $this->getAdapter()->query('UPDATE ' . $this->_name . ' SET ip=REPLACE(ip, "' . $cat['ip'] . '.", "' . $newparent['ip'] . '.' . $cat['id'] . '."), depth=' . $set_depth . ' WHERE ip LIKE("' . $cat['ip'] . '.%")');
            $this->getAdapter()->query('UNLOCK TABLES');
            if ($this->log) {
                Library_History::instans()->set(array('type' => 0,
                    'class' => __CLASS__,
                    'method' => __METHOD__,
                    'query' => array('Root' => $cat, 'NewRoot' => $newparent)));
            }
            return true;
        } catch (Library_Exception $e) {
            return false;
        }
    }

    /**
     *  Usuwam wybrany korzeń
     * @param <type> $idRoot - id usuwanego korzenia
     */
    public function delateRoot($idRoot) {
        try {
            $row = $this->IdToRow($idRoot);
            $this->getAdapter()->query('DELETE FROM ' . $this->_name . ' WHERE ip REGEXP "^' . $row['ip'] . '(\.(.+))?$"');
            if ($this->log) {
                Library_History::instans()->set(array('type' => 0,
                    'class' => __CLASS__,
                    'method' => __METHOD__,
                    'query' => $row));
            }
            return true;
        } catch (Library_Exception $e) {
            return false;
        }
    }

    /**
     *
     * @param <type> $idRoot - id czyszczonego roota
     */
    public function clearRoot($idRoot) {
        try {

        } catch (Library_Exception $e) {
            return false;
        }
    }

    /**
     *
     * @param <type> $idRoot - id korzenia
     * @param <type> $type - typ zwracanych danych, "obiect" lub "array"
     * @param <type> $depth - głebia zwracanych elementów , 0 zwroc wszystko wraz z dziecmi, 1 zwroc tylko korzenie
     */
    public function getRootChild($idRoot, $type = array(), $depth) {
        try {

        } catch (Library_Exception $e) {
            return false;
        }
    }

    /**
     *
     * @return <type> - Tablice z informacjami o drzewie
     */
    public function getTreeInfo() {
        try {
            $select = $this->select();
            $counter = $this->_db->fetchRow($select, array('id'), Zend_Db::FETCH_ASSOC);
            $numRow = count($counter);
            $response = array('_TableName' => $this->_name,
                '_Items' => $numRow);
            return $response;
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
                if ($this->log) {
                    Library_History::instans()->set(array('type' => 0, 'class' => __CLASS__, 'method' => __METHOD__, 'query' => $select->__toString()));
                }
                return $SearchedItems;
            } else {
                if ($this->log) {
                    Library_History::instans()->set(array('type' => 0, 'class' => __CLASS__, 'method' => __METHOD__, 'query' => 'error danych'));
                }
                return false;
            }
        } catch (Library_Exception $e) {
            return false;
        }
    }

    /**
     *  Zmieniam identyfikator na IP
     *
     * @param <type> $idRoot - id korzenia
     * @return <type> Row
     */
    private function IdToRow($idRoot) {

        try {
            $select = $this->select();
            $select->where('id = ?', $idRoot);

            $response = $this->_db->fetchRow($select, array(), Zend_Db::FETCH_ASSOC);
            if ($this->log) {
                Library_History::instans()->set(array('type' => 0, 'class' => __CLASS__, 'method' => __METHOD__, 'query' => $select->__toString()));
            }
            return $response;
        } catch (Library_Exception $e) {
            return false;
        }
    }

    /**
     *  Metoda pobiera wszystkie elementy i generuje z nich tablice struktury drzewiastej
     *
     * @param <type> $idRoot - Identyfiaktor drzewa
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
            // var_dump($row);die;
            //$cat = arr($cat,$row);
            $cat[] = $row;
            //print_r($cat);die;
            //new var_dump($cat);die;
            //var_dump($row);die;
            //print_r($cat);die;
            foreach ($cat as $element) {
                $TempCat[$element['parentID']][$element['id']] = $element;
            }

            //new var_dump($TempCat);die;
            if ($this->log) {
                Library_History::instans()->set(array('type' => 0, 'class' => __CLASS__, 'method' => __METHOD__, 'query' => $TempCat));
            }
            return $TempCat;
        } else {
            if ($this->log) {
                Library_History::instans()->set(array('type' => 0,
                    'class' => __CLASS__,
                    'method' => __METHOD__,
                    'query' => array('type' => 'error', 'query' => array('root' => $idRoot), 'msg' => 'bad id number')));
            }

            return false;
        }
    }

    /**
     *
     * @param <type> $idRoot - id korzenia którego chcemy pobrać drzewo
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

    /**
     *
     * @param <type> $idRoot - id korzenia którego chcemy pobrać drzewo
     * @return <type>
     */
    public function _RootToArrayTreeEasyUi($idRoot=1) {
        $issetArray = $this->IdToRow($idRoot);
        if ($issetArray) {
            $cTree = $this->_RootToArray($idRoot);

            $this->_GenerateRecursiveTreeEasyUi(1, $cTree);
            if (!$this->cacheTree) {
                $this->cacheTree = $issetArray;
            }

            return $this->cacheTree;
        } else {
            return false;
        }
    }

    /**
     *
     * @param <type> $idRoot - identyfikator korzenia
     * @return <type> Html struktury drzewiastej
     */
    public function _RootToJsTree($idRoot=1) {
        return $this->cacheJsTree;
    }

    /**
     *
     * @param <type> $parentID - id rodzica
     * @param <type> $tab - tabela do przekonwertowania
     * @return <type>  - null
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
     *
     * @param <type> $parentID - id rodzica
     * @param <type> $tab - tabela do przekonwertowania
     * @return <type>  - null
     */
    private function _GenerateRecursiveTreeEasyUi($parentID, $tab) {
        if (!isset($tab[$parentID]) || !is_array($tab[$parentID])) {
            return false;
        } else {
            // echo '<ul>';

            foreach ($tab[$parentID] as $element) { #iteracja podkategorii
                // echo "<li>";
                // print_r($element);
                unset($element['ip'], $element['parentID'], $element['depth'], $element['desc'], $element['date_mod'], $element['date_add'], $element['ord'], $element['active'], $element['name_url'], $element['short_desc']);
                $element['iconCls']='sd';

                $cache[$element['id']] = array_merge($element);
                //  echo "</li>";
                $response = $this->_GenerateRecursiveTreeEasyUi($element['id'], $tab); #wyswietlenie podkategorii
                if ($response) {
                    
                    $cache[$element['id']]['children'] = array_merge($response);
                    // $cache[$element['id']]['iconCls']='icon-ok';
                }
                // echo '</li>';
                //$this->cacheTree = array(1=>2);
            }

            // echo '</ul>';
            $this->cacheTree = array_merge($cache);
            return $cache;
        }
    }

    public function GenerateStructure($structure = array()) {
        $TreeStructure = array();
        if (!empty($structure)) {
            //$
        } else {

        }
    }

    /**
     *
     * @param <type> $idRoot - id korzenia którego chcemy pobrać drzewo
     * @return <type>
     */
    public function _RootToArraySimpleTreeEasyUi($idRoot=1) {
        $issetArray = $this->IdToRow($idRoot);
        if ($issetArray) {
            $cTree = $this->_RootToArray($idRoot);

            $this->_GenerateRecursiveSimpleTreeEasyUi(1, $cTree);
            if (!$this->cacheTree) {
                $this->cacheTree = $issetArray;
            }

            return $this->cacheTree;
        } else {
            return false;
        }
    }

    /**
     *
     * @param <type> $parentID - id rodzica
     * @param <type> $tab - tabela do przekonwertowania
     * @return <type>  - null
     */
    private function _GenerateRecursiveSimpleTreeEasyUi($parentID, $tab) {
        if (!isset($tab[$parentID]) || !is_array($tab[$parentID])) {
            return false;
        } else {
            // echo '<ul>';

            foreach ($tab[$parentID] as $element) { #iteracja podkategorii
                // echo "<li>";
                // print_r($element);
                unset($element['ip'], $element['parentID'], $element['depth']);
                   $cacheItem = array('text'=>$element['name'],'checked'=>false,'attributes'=>array_merge($element));

                $cache[$element['id']] = array_merge($cacheItem);
                //  echo "</li>";
                $response = $this->_GenerateRecursiveSimpleTreeEasyUi($element['id'], $tab); #wyswietlenie podkategorii
                if ($response) {
                    $cache[$element['id']]['children'] = array_merge($response);
                }
                // echo '</li>';
                //$this->cacheTree = array(1=>2);
            }

            // echo '</ul>';
            $this->cacheTree = array_merge($cache);
            return $cache;
        }
    }

}
