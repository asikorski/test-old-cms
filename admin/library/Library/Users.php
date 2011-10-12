<?php

/**
 * @Author: Arnold Sikorski
 * Biblioteka do obsługi użytkowników
 *
 */
class Library_Users extends Zend_Db_Table_Abstract {

    private $config;
    protected $_name;
    private $log = true;

    /**
     *
     * @param <type> $config - tablica konfiguracyjna połaczenia z baza danych
     * @param <type> $parent - informacje o rodzicu
     */
    public function __construct($config = array()) {
        parent::__construct($config);
        $this->config = $config;
        $this->_name = $config['_name'];
    }

    /**
     *
     * @param array $date - nowe dane
     * @return <type>  - true jesli wykonano zmiane
     */
    public function addUser(array $date = array()) {
        if (is_array($date)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @param <type> $idUser identyfikator urzytkownika
     * @param array $date nowe dane
     * @return <type>  true jesli wykonano zmiane
     */
    public function setUser($id, array $data = array()) {
        try {
            $row = $this->getUser((int) $id);
            $where = $this->getAdapter()->quoteInto('id = ?', (int) $id);
            //$row['id'] = $idItem;
            $pRow = $data;
            $bRow = $row;
            /* andpisujemy dane */
            $FinalRow = array_intersect_key(array_merge($bRow, $pRow), $bRow);
            //Margujemy obie tablice z uwzglednieniem nowych danych oraz pobranych z bazy
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
        } catch (Library_Exception $e) {

            return false;
        }
    }

    /**
     *
     * @param <type> $idUser - id urzytkownika
     * @return <type> Tablice danych
     */
    public function getUser($idUser) {
        try {
            $select = $this->select();
            $select->where('id = ?', $idUser);
            $row = $this->_db->fetchRow($select, array(), Zend_Db::FETCH_ASSOC);
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
     *
     * @param <type> $idUser -id urzytkownika
     * @return <type> - true jesli usunieto
     */
    public function deleteUser($idUser) {
        try {
            $where = $this->getAdapter()->quoteInto('id = ?', $idUser);
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
     *  pobieram uzytkowników
     * 
     * @return <type> - wszyscy userzy
     */
    public function getUsers() {
        try {
            $select = $this->select();
            $rows = $this->_db->fetchAll($select, array(), Zend_Db::FETCH_ASSOC);
            if ($this->log) {
                Library_History::instans()->set(array('type' => 0,
                    'class' => __CLASS__,
                    'method' => __METHOD__,
                    'query' => $rows));
            }
            return $rows;
        } catch (Library_Exception $e) {

            return false;
        }
    }

    /**
     *  PObieramy uzytkowników na podstawie grupy
     * @param <type> $idGroup - id grupy
     * @return <type> - uzytkownicy
     */
    public function getUsersByGrups($idGroup) {
        try {
            $select = $this->select();
            $select->where('group = ?', $idGroup);
            $rows = $this->_db->fetchAll($select, array(), Zend_Db::FETCH_ASSOC);
            if ($this->log) {
                Library_History::instans()->set(array('type' => 0,
                    'class' => __CLASS__,
                    'method' => __METHOD__,
                    'query' => $rows));
            }
            return $rows;
        } catch (Library_Exception $e) {

            return false;
        }
    }

}
