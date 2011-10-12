<?php

/**
 * Klasa do pobierania stron dynamicznych z cms'a
 */
class CMS_Items extends Zend_Db_Table_Abstract {

    private $config;
    protected $_name;
    private $log = true;
    private $parentCollumn = 'category_id';

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
     * Pobiera dane z wybranego elementu
     *
     * @param <type> $idItems - id elementu
     */
    public function getItemFromId($idItem) {
        try {
            if (is_int($idItem)) {
                $select = $this->select();
                $select->where('id = ?', $idItem);
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
     *  Zwraca element na podstawie jego urla
     * @param <type> $url - adres url szukanego elementu
     * @return <type> - tablice elementu
     */
    public function getItemByUrl($url) {
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
     * @param <type> $query - jednoelementowa tabica zapytania
     * @return <type>
     */
    public function getItemByQuery($query) {
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
     * @param <type> $idParentRoot - id rodzica
     * @return <type> - pobieram elementy na podstawie id rodzica
     */
    public function getItemsByCategory($idParentRoot, $ord = array('ord DESC'), $limit = null) {
        try {
            if (is_int($idParentRoot)) {
                $select = $this->select();

                $select->where($this->parentCollumn . ' = ?', $idParentRoot);
                $select->order($ord);
                if($limit){
                    $select->limit($limit);
                }
                $Items = $this->_db->fetchAll($select, array(), Zend_Db::FETCH_ASSOC);
                return $Items;
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
 * @return <type> - tabkuce
 */
    public function getItemsBy(array $query = array(), array $ord = array(),$limit=null) {
        try {
            if (is_array($query) && is_array($ord)) {
                $select = $this->select();
                
                foreach ($query as $key => $value) {
                    if(is_array($value)){
                        foreach($value as $item){
                            $select->orWhere($key . ' = ?', $item);
                        }
                    }else{
                        $select->where($key . ' = ?', $value);
                    }
                }
                $select->order($ord);
                if($limit){
                    $select->limit($limit);
                }

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
     *
     * @param <type> $category_id
     * @param <type> $string
     * @param <type> $colums
     * @param <type> $order
     * @param <type> $type
     * @return <type> 
     */
        public function search($category_id,$string = null , $colums = array(), $order = array(), $type = 'IN BOOLEAN MODE') {
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

                return $SearchedItems;
            } else {

                return false;
            }
        } catch (Library_Exception $e) {
            return false;
        }
    }
    /**
     * Dodaje nowy element do wybranego korzenia rodzica
     * @author Piotr Feć:)
     * @param <type> $idParentRoot - id korzenia rodzica
     * @param <type> $data - dane do zapisu
     */
    public function addItem($idParentRoot, $data = array()) {
        try {
            if (is_array($data) && is_int($idParentRoot)) {
                $row[$this->parentCollumn] = $idParentRoot;
                
                $now = date("Y-m-d H:i:s");
                
                $row = array_merge($row, $data);
                
                $row['date_add'] = $now;
                $row['date_mod'] = $now;
                
                $id = $this->insert($row);
                if ($this->log) {
                    Library_History::instans()->set(array('type' => 0,
                        'class' => __CLASS__,
                        'method' => __METHOD__,
                        'query' => $row));
                }
                return $id;
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

}