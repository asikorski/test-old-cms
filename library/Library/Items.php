<?php

/**
 * Klasa dostepu do bazy danych dla systemu zarządzania trescia CMS 4.0
 * Obsługa elementów drzewa
 *
 * @access public
 * @author Arnold Sikorski <sikorski.arnold@gmail.com>
 * @license http://www.example.com/license/licencja
 * @version 1.2
 * @copyright Arnold Sikorski 2011
 * @internal
 * @param string $argument1 Opis argumentu
 * @todo Klasy niekompatybilne w stecz
 * @deprecated deprecated since version 1.0
 *
 */
class Library_Items extends Zend_Db_Table_Abstract {

    private $config;
    protected $_name;
    private $log = true;
    private $parrentColumn = 'category_id';
    /* kolumna rodzica */

    /**
     * Konstruktor klasy
     *
     * @param <type> $config - tablica konfiguracyjna klasy
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
     * Ddoaje nowy element do bazy
     *
     * @param <type> $idParentRoot - id korzenia rodzica
     * @param <type> $data - tablica elementów zapisy
     */
    public function addItem($idParentRoot, $data = array()) {
        try {
            if (is_array($data) && is_int($idParentRoot)) {
                $row[$this->parrentColumn] = $idParentRoot;
                $now = date("Y-m-d H:i:s");
                #Aktualna data
                $row['date_add'] = $now;
                $row['date_mod'] = $now;
                $row['active'] = 1;
                $maxRow = $this->getMaxOrd($idParentRoot);
                if ($maxRow) {
                    $row['ord'] = $maxRow['ord'] + 1;
                } else {
                    $row['ord'] = 1;
                }
                $row = array_merge($row, $data);
                $id = $this->insert($row);
                if ($this->log) {
                    Library_History::instans()->set(array('type' => 0,
                        'class' => __CLASS__,
                        'method' => __METHOD__,
                        'query' => $row));
                }
                return ($id) ? $id : false;
            } else {
                if ($this->log) {
                    Library_History::instans()->set(array('type' => 0,
                        'class' => __CLASS__,
                        'method' => __METHOD__,
                        'query' => 'bad data'));
                }
                return false;
            }
        } catch (Library_Exception $e) {

            return false;
        }
    }

    /**
     *
     * @param <type> $id
     * @return <type>
     */
    public function setItemPosUp($id) {
        try {
            if (is_int($id)) {
                /* category */
                $row = $this->getItemFromId($id);

                $newOrd = $row['ord'] + 1;
                $oldOrd = $row['ord'];

                $row['ord'] = $newOrd;

                $item = $this->getItemFromOrd($row['category_id'], $newOrd);

                if ($item) {
                    $item['ord'] = $oldOrd;

                    $res1 = $this->setItem($item['id'], $item);
                    $res2 = $this->setItem($row['id'], $row);
                }


                echo $newOrd;
            } else {
                return false;
            }
            return true;
        } catch (Library_Exception $e) {
            return false;
        }
    }

    /**
     *
     * @param <type> $id
     * @return <type>
     */
    public function setItemPosDown($id) {
        try {
            if (is_int($id)) {
                /* category */
                $row = $this->getItemFromId($id);

                $newOrd = $row['ord'] - 1;
                $oldOrd = $row['ord'];

                $row['ord'] = $newOrd;

                $item = $this->getItemFromOrd($row['category_id'], $newOrd);

                if ($item) {
                    $item['ord'] = $oldOrd;

                    $res1 = $this->setItem($item['id'], $item);
                    $res2 = $this->setItem($row['id'], $row);
                }


                echo $newOrd;
            } else {
                return false;
            }
            return true;
        } catch (Library_Exception $e) {
            return false;
        }
    }

    public function getMaxOrd($category) {
        try {
            $select = $this->select();
            $select->where('category_id = ?', $category);
            $select->order(array('ord DESC'));
            $select->limit(1);
            $response = $this->_db->fetchRow($select, array(), Zend_Db::FETCH_ASSOC);
            return $response;
        } catch (Library_Exception $e) {
            // echo "error here";
            return false;
        }
    }

    /**
     * Metoda edytuje wybrany element
     *
     * @param <type> $idItem - id korzenia rodzica
     * @param <type> $data - dane do zapisu
     */
    public function setItem($id, array $data = array()) {
        try {
            if (is_array($data)) {
                $now = date("Y-m-d H:i:s");
                $row = $this->getItemFromId((int) $id);
                $where = $this->getAdapter()->quoteInto('id = ?', (int) $id);
                //$row['id'] = $idItem;
                $pRow = $data;
                $bRow = $row;
                /* andpisujemy dane */
                $FinalRow = array_intersect_key(array_merge($bRow, $pRow), $bRow);
                //Margujemy obie tablice z uwzglednieniem nowych danych oraz pobranych z bazy
                if (!$row['date_add']) {
                    $FinalRow['date_add'] = $now;
                }
                $FinalRow['date_mod'] = $now;
                $this->update($FinalRow, $where);
                //$rowResult = array_merge($row,$data);
                //$row = array_merge($data, $row);


                if ($this->log) {
                    Library_History::instans()->set(array('type' => 0,
                        'class' => __CLASS__,
                        'method' => __METHOD__,
                        'query' => $FinalRow));
                }
                return $FinalRow;
                //return true;
            } else {
                if ($this->log) {
                    Library_History::instans()->set(array('type' => 0,
                        'class' => __CLASS__,
                        'method' => __METHOD__,
                        'query' => 'bad date'));
                }
                return false;
            }
        } catch (Library_Exception $e) {
            // echo "error here";
            return false;
        }
    }

    /**
     * Pobierz element na podstawie jego id
     * @param <type> $idItem - identyfikator elementu
     * @return <type> tablica z danymi
     */
    public function getItemFromId($idItem) {
        try {
            if (is_int($idItem)) {
                $select = $this->select();
                $select->where('id = ?', $idItem);
                $response = $this->_db->fetchRow($select, array(), Zend_Db::FETCH_ASSOC);
                if ($this->log) {
                    Library_History::instans()->set(array('type' => 0, 'class' => __CLASS__, 'method' => __METHOD__, 'query' => $select->__toString()));
                }
                return $response;
            } else {
                if ($this->log) {
                    Library_History::instans()->set(array('type' => 0,
                        'class' => __CLASS__,
                        'method' => __METHOD__,
                        'query' => 'bad date'));
                }
                return false;
            }
        } catch (Library_Exception $e) {

            return false;
        }
    }

    /**
     * Pobiera tablice wszystkich elementów wybranego korzenia
     *
     * @param <type> $idParentRoot - id korzenia rodzicad
     */
    public function getItemsFromRoot($idParentRoot, $order = array('name ASC')) {
        try {
            if (is_int($idParentRoot)) {
                $select = $this->select();
                $select->where($this->parrentColumn . ' = ?', $idParentRoot)
                        ->order($order);
                $Items = $this->_db->fetchAll($select, array(), Zend_Db::FETCH_ASSOC);
                if ($this->log) {
                    Library_History::instans()->set(array('type' => 0, 'class' => __CLASS__, 'method' => __METHOD__, 'query' => $select->__toString()));
                }
                return $Items;
            } else {
                if ($this->log) {
                    Library_History::instans()->set(array('type' => 0,
                        'class' => __CLASS__,
                        'method' => __METHOD__,
                        'query' => 'bad date'));
                }
                return false;
            }
        } catch (Library_Exception $e) {

            return false;
        }
    }

    /**
     * Pobierz elementy na podstawie zadanych parametrów
     *
     * @param array $query - tablica zapytań
     * @param array $order - sposob sortowania
     * @return <type> tablice elementów
     */
    public function getItemsByQuery(array $query = array(), array $order = array('name ASC')) {
        try {

        } catch (Library_Exception $e) {

            return false;
        }
    }

    /**
     * Wyszukiwanie elementów w bazie
     * @param <type> $category_id
     * @param <type> $string
     * @param <type> $colums
     * @param <type> $order
     * @param <type> $type
     * @return <type>
     */
    public function serach($category_id, $string = null, $colums = array(), $order = array(), $type = 'IN BOOLEAN MODE') {
        try {
            if (is_array($order)) {
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
                if (!is_null($category_id)) {
                    $select->where('category_id = ?', $category_id);
                }
                $SearchedItems = $this->_db->fetchAll($select, array(), Zend_Db::FETCH_ASSOC);

                return ($SearchedItems) ? $SearchedItems : false;
            } else {

                return false;
            }
        } catch (Library_Exception $e) {
            return false;
        }
    }

    /**
     * Usuwa wybrany element
     *
     * @param <type> $idItem - id usuwanego elementu
     */
    public function deleteItem($idItem) {
        try {
            $where = $this->getAdapter()->quoteInto('id = ?', $idItem);
            $this->delete($where);
            if ($this->log) {
                Library_History::instans()->set(array('type' => 0,
                    'class' => __CLASS__,
                    'method' => __METHOD__,
                    'query' => $where));
            }
            return true;
        } catch (Library_Exception $e) {

            return false;
        }
    }

    /**
     * Przenosi element do innego korzenia
     *
     * @param <type> $idItem - id przenoszonego elementu
     * @param <type> $idNewRoot - id nowego korzenia drzewa
     */
    public function moveItemRoot($idItem, $idNewRoot) {
        try {
            if (is_int($idItem) && is_int($idNewRoot)) {
                $row = $this->getItemFromId($idItem);
                $row[$this->parrentColumn] = $idNewRoot;
                $this->setItem($idItem, $row);
                return true;
            } else {
                if ($this->log) {
                    Library_History::instans()->set(array('type' => 0,
                        'class' => __CLASS__,
                        'method' => __METHOD__,
                        'query' => 'bad date'));
                }
            }
        } catch (Library_Exception $e) {

            return false;
        }
    }

    /**
     * Edytuje pozycje elementu na liscie
     *
     * @param <type> $idItem - id elementu
     * @param <type> $order  - nowa pozycja elementu
     */
    public function movePosItem($idItem, $order) {
        try {
            return true;
            /* funckja narazie niedostepna */
        } catch (Library_Exception $e) {

            return false;
        }
    }

    /**
     * Pobiera informacje o elementach
     */
    public function getItemsInfoFromRoot($idRoot) {
        try {
            $select = $this->select();
            $select->where($this->parrentColumn . ' = ?', $idRoot);
            $counter = $this->_db->fetchAll($select, array('id'), Zend_Db::FETCH_ASSOC);
            $numRow = count($counter);
            $response = array('_TableName' => $this->_name,
                '_Items' => $numRow);
            return $response;
        } catch (Library_Exception $e) {

            return false;
        }
    }

    /**
     *
     * @return <type> - zwraca infromacje o tabeli
     */
    public function getItemsInfoAll() {
        try {
            $select = $this->select();

            $counter = $this->_db->fetchAll($select, array('id'), Zend_Db::FETCH_ASSOC);
            $numRow = count($counter);
            $response = array('_TableName' => $this->_name,
                '_Items' => $numRow);
            return $response;
        } catch (Library_Exception $e) {

            return false;
        }
    }

    /**
     *  Wyszukiwanie elementów
     *
     * @param <type> $string - Wyszukiwany string , moze pozostać pusty jeśli korzystamy z wyszukiwania zawansowanego
     * @param <type> $colums - kolumn
     * @param <type> $idRoot
     * @param <type> $order
     * @param <type> $type
     */
    public function search($string = null, $colums = array(), $idRoot = null, $order = array(), $type = 'IN BOOLEAN MODE') {
        try {

        } catch (Library_Exception $e) {

            return false;
        }
    }

    public function getItemFromOrd($category, $ord) {
        try {

            $select = $this->select();
            $select->where('category_id = ?', $category);
            $select->where('ord = ?', $ord);
            $response = $this->_db->fetchRow($select, array(), Zend_Db::FETCH_ASSOC);
            if ($this->log) {
                Library_History::instans()->set(array('type' => 0, 'class' => __CLASS__, 'method' => __METHOD__, 'query' => $select->__toString()));
            }
            return $response;
        } catch (Library_Exception $e) {

            return false;
        }
    }

}