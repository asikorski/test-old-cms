<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class CMS_Relations extends Zend_Db_Table_Abstract {

    private $config;
    protected $_name;
    private $log = true;

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
     * @param <type> $parent_id - id rodzica
     * @return <type> - table elementów
     */
    public function getRelationsFromParent($parent_id, array $ord = array('ord ASC')) {

        try {
            if (is_int((int) $parent_id)) {
                $select = $this->select();
                $select->where('id_relations = ?', (int) $parent_id);
                $select->order($ord);
                $response = $this->_db->fetchAll($select, array(), Zend_Db::FETCH_ASSOC);

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
     * @param <type> $id - identyfikator pliku
     * @return <type> Tablica z informacjami o pliku
     *
     * Pobieram plik relacji na podstawie jego id
     *
     * @author Arnold Sikorski
     */
    public function getRelationById($id) {

        try {
            if (is_int((int) $id)) {
                $select = $this->select();
                $select->where('id = ?', (int) $id);
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
     * @param array $query - tablica zapytań
     * @param array $ord - tablica sortowań
     * @return <type>
     */
    public function getRelationBy(array $query = array(), array $ord = array()) {
        try {
            if (is_array($query) && is_array($ord)) {
                $select = $this->select();
                foreach ($query as $key => $value) {
                    $select->where($key . ' = ?', $value);
                }
                $select->order($ord);
                $Items = $this->_db->fetchAll($select, array(), Zend_Db::FETCH_ASSOC);
                return ($Items) ? $Items : false;
            } else {
                return false;
            }
        } catch (Library_Exception $e) {

            return false;
        }
    }

    /**
     * @author Arnold Sikorski
     * @param <type> $parrentID
     * @param <type> $string
     * @param <type> $colums
     * @param <type> $order
     * @param <type> $type
     *
     * Wyszukiwarka plików relacji
     */
    public function serach($parrentID, $string = null, $colums = array(), $order = array(), $type = 'IN BOOLEAN MODE') {
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
                    $select->where('id_relations = ?', $parrentID);
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

}

?>
