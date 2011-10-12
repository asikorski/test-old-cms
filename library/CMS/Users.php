<?php

/**
 * @author Arnold Sikorski
 * @category CMS UserConnector
 * @example Poczytaj to zrozumiesz
 * @name CMS_Users
 *
 *
 * Klasa umożliwiajaca połączenie z bibliotekami systemu zarzadzania trescia
 *
 * 13-09-2011
 */
class CMS_Users {

    protected $userTable;
    protected $userAdapter;
    protected $db;
    /* kolumny przechowywujace dane */
    protected $_passwordColumn = 'password';
    protected $_nameColumn = 'name';

    public function getUserById($id) {

    }

    public function getUsers(array $query = array()) {
        try {
            return $rows;
        } catch (Library_Exception $e) {

            return false;
        }
    }

    public function setUser($id, array $row = array()) {
        try {
            
        } catch (Library_Exception $e) {

            return false;
        }
    }

    public function checkUser() {
        try {
            
        } catch (Library_Exception $e) {

            return false;
        }
    }

    public function addNewUser(array $row = array()) {

        try {
            
        } catch (Library_Exception $e) {

            return false;
        }
    }

    public function checkActivateCode($code) {
        try {

        } catch (Library_Exception $e) {

            return false;
        }
    }

    public function searchUsers() {
        try {

        } catch (Library_Exception $e) {

            return false;
        }
    }

    public function setUser($id) {
        try {

        } catch (Library_Exception $e) {

            return false;
        }
    }

    /**
     * Zaloguj Użytkownika
     * @param <type> $login
     * @param <type> $password
     * @return <type>
     */
    public function authenticate($login, $password) {
        try {

        } catch (Library_Exception $e) {

            return false;
        }
    }

    public function checkStatus() {
        try {
            $oAuth = Zend_Auth::getInstance();
            #obiekt autoryzacji
            $status = $oAuth->hasIdentity();
            if ($status) {
                #sprawdzam status
                $stroge = $oAuth->getStorage();
                $oUser = $stroge->read();
                if ($oUser) {

                    Zend_Registry::set('oUser', $oUser);
                    return $status;
                }
            }
        } catch (Library_Exception $e) {

            return false;
        }
    }

    /**
     * @author Arnold Sikorski
     * @return <type> - potwierdzenie wylogowania
     */
    public function logout() {
        try {
            $oLogOut = Zend_Auth::getInstance()->clearIdentity();
            return ($oLogOut) ? $oLogOut : false;
        } catch (Library_Exception $e) {

            return false;
        }
    }

}
