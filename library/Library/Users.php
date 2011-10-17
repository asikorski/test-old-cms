<?php

/**
 * @author Arnold Sikorski
 *
 * Klasa odpowiedzialna za obsługe użytkowników
 */
class Library_Users extends Zend_Db_Table_Abstract {

    protected $userTable;
    protected $_name = 'cm_users_elements_';
    protected $db;
    /* kolumny przechowywujace dane */
    protected $_passwordColumn = 'password';
    protected $_nameColumn = 'name';

    /* save user in registry */
    protected $_inRegistry = true;
    protected $oAuthAdapter;
    protected $oAuth;

    /* stale logowania */
    const USER_FAILURE_NO_ACTIVE = 1;
    const USER_FAILURE_INSERT_CODE = 2;
    const USER_FAILURE_BANNED = 3;
    const USER_ACTIVE = 4;

    const ERROR = false;

    /**
     *
     * @param <type> $config
     * @return <type>
     */
    public function __construct($config = array()) {
        try {
            if (isset($config['lang'])) {
                $this->_name = $this->_name . $config['lang'];
                unset($config['lang']);
                parent::__construct($config);
            } else {
                return false;
            }
        } catch (Library_Exception $e) {

            return false;
        }
    }

    /**
     * Pobiera dane użytkownika na podstawie jego identyfikatora
     * @param <type> $id - identyfikator użytkownika
     * @return <type>  - tablica z danymi o uzytkowniku
     */
    public function getUserById($id) {
        try {
            if (is_int($id)) {
                $select = $this->select();
                $select->where('id = ?', $id);
                $row = $this->_db->fetchRow($select, array(), Zend_Db::FETCH_ASSOC);
                return $row;
            } else {
                return false;
            }
        } catch (Library_Exception $e) {

            return false;
        }
    }

    /**
     * Pobieram liste uzytkownikóþw
     * @param array $query
     * @param array $ord 
     */
    public function getUsers(array $ord = array('status DESC')) {

        try {
            $select = $this->select();
            $select->order($ord);
            $users = $this->_db->fetchAll($select, array(), Zend_Db::FETCH_ASSOC);
            return ($users) ? $users : false;
        } catch (Library_Exception $e) {

            return false;
        }
    }

}
