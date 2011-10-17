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
class CMS_Users extends Zend_Db_Table_Abstract {

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

    protected $maxPasswordSize = 10;

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
                /* adapter autoryzacji */
                $this->oAuth = Zend_Auth::getInstance();
                $this->oAuthAdapter = new Zend_Auth_Adapter_DbTable();
                $this->oAuth->setStorage(new Zend_Auth_Storage_Session('oUserAuth'));
                $this->oAuthAdapter
                        ->setTableName($this->_name)
                        ->setIdentityColumn($this->_nameColumn)
                        ->setCredentialColumn($this->_passwordColumn);
                return true;
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
        #testowa zmianasd
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
     * Pobieram dane o użytkowniku na podstawie zadanych danych
     * @param <type> $query - tablica z danymi na podstawie której pobiera
     * @return <type> - tablica zwrotna z danymi
     */
    public function getUserByQuery(array $query = array()) {
        try {
            return $row;
        } catch (Library_Exception $e) {

            return false;
        }
    }

    /**
     * Usuwa wybranego użytkownika
     * @param <type> $id - id użytkownika
     * @return <type> wartosc
     */
    public function removeUser($id) {
        try {
            if (is_integer($id)) {
                $where = $this->getAdapter()->quoteInto('id = ?', $id);
                $this->delete($where);
                return true;
            } else {
                return false;
            }
        } catch (Library_Exception $e) {

            return false;
        }
    }

    /**
     * Modyfikuje dane uzytkownika na podstawie tablicy danych
     * @param <type> $id - id użytkownika
     * @param array $pRow - tablica danych z użytkownikiem
     * @return <type> - zwracany dane zmodyfikowane
     */
    public function setUser($id, array $pRow = array()) {
        try {
            if (is_integer($id) && is_array($pRow)) {
                $now = date("Y-m-d H:i:s");

                $pRow['date_mod'] = $now;
                //Nowa tablica
                $bRow = $this->getUserById((int) $id);
                //Stare dane;
                $FinalRow = array_intersect_key(array_merge($bRow, $pRow), $bRow);
                //Mergowanie danych
                $where = $this->getAdapter()->quoteInto('id = ?', (int) $id);
                $this->update($FinalRow, $where);
                return true;
            } else {
                return false;
            }
        } catch (Library_Exception $e) {

            return false;
        }
    }

    /**
     * Aktywuje użytkownika na podstawie zadanych danych
     * @param <type> $login - login
     * @param <type> $password - hasło (zaszyfrowane md5)
     * @param <type> $hashCode - kod aktywacyjny
     * @return <type>
     */
    public function activateUser($login, $password, $hashCode) {
        try {
            
        } catch (Library_Exception $e) {

            return false;
        }
    }

    /**
     *  Sprawdza dostepność danych
     * @param <type> $query - dane do sprawdzenia
     * @return <type> false jeśli błąd , tablice z błednymi danymi jestli nie niedopstepny, true jesli dane poprawne
     */
    public function checkUserAvilable($query) {
        try {
            foreach ($query as $key => $value) {
                $select->where($key . ' = ?', $value);
            }
            $select->order($ord);
            $select->limit(1);
            $row = $this->_db->fetchRow($select, array(), Zend_Db::FETCH_ASSOC);
            return ($row) ? $row : false;
        } catch (Library_Exception $e) {

            return false;
        }
    }

    /**
     * Dodaje nowego użytkownika do bazy, użytkoninik nominalnie nie jest zaktualizowanuy
     * @param array $row - tabica danych użytkownika
     * @return <type>- dane badz false
     */
    public function addNewUser(array $row = array()) {

        try {
            if (is_array($row)) {
                $now = date("Y-m-d H:i:s");
                $code = $this->genHashCode();
                if ($code) {
                    $pRow = array('date_add' => $now,
                        'date_mod' => $now,
                        'status' => self::USER_FAILURE_NO_ACTIVE,
                        'password' => $this->generatePassword($row['password'], $row['name']),
                        'activate_code' => $code);
                    $finalRow = array_merge($row, $pRow);
                    #Mergujemy dwie tablice
                    $id = $this->insert($finalRow);

                    return ($id) ? true : false;
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

    public function searchUsers($string = null, $colums = array(), $order = array(), $type = 'IN BOOLEAN MODE') {
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
     * Uwierzytelnij
     * @param <type> $login
     * @param <type> $password
     * @return <type>
     */
    public function authenticate($login, $password) {
        try {
            if ($login && $password) {
                $this->oAuthAdapter
                        ->setIdentity($login)
                        //->setCredential($this->generatePassword($password,$login));
                        ->setCredential(md5($password));
                $result = $this->oAuth->authenticate($this->oAuthAdapter);
                $storage = $this->oAuth->getStorage();
                if ($result->getCode() == Zend_Auth_Result::SUCCESS) {
                    $storage->write($this->oAuthAdapter->getResultRowObject());
                }
                $resultCode = $result->getCode();
                switch ($resultCode) {

                    case Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND:
                        /** do stuff for nonexistent identity * */
                        break;

                    case Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID:
                        /** do stuff for invalid credential * */
                        break;

                    case Zend_Auth_Result::SUCCESS:
                        /** do stuff for successful authentication * */
                        break;

                    default:
                        /** do stuff for other failure * */
                        break;
                }
                return $resultCode;
            } else {
                
            }
        } catch (Library_Exception $e) {

            return false;
        }
    }

    /**
     * Sprawdza status użytkownika, jezeli użytownik jest aktywny loguje go, dane logowania zapisuje w sesji
     * @return <type>
     */
    public static function checkStatus() {
        try {
            $oAuth = Zend_Auth::getInstance();
            #obiekt autoryzacji
            $status = $oAuth->hasIdentity();
            if ($status) {
                #sprawdzam status
                $stroge = $oAuth->getStorage();
                $oUser = $stroge->read();
                //print_r($oUser);
                if ($oUser) {
                    unset($oUser->password);
                    #hasło usuwam na wszelki wypadek
                    Zend_Registry::set('oUserAuth', $oUser);

                    return $oUser->status;
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
     * Generowanie hashcode dla danego usera
     * @param <type> $name
     * @param <type> $password
     * @return <type>
     */
    public function genHashCode($email, $password) {
        try {
            if ($email && $password)
                $nomd5Code = $password . $email;
            $md5Code = md5($nomd5Code);
            if (count($md5Code) > 7) {
                $code = $md5Code;
            } else {
                $code = substr($md5Code, 0, 7);
            }
            return $code;
        } catch (Library_Exception $e) {

            return false;
        }
    }

    /**
     * Uruchamiamy walidatory które sprawdzaja poprawnosc wprowadzonych danych
     * @param <type> $row - tablica z danymi do walidacji
     * @return <type>
     */
    public static function ValidateUser($row) {
        // zmiana
        try {
            $passwordValidator = new Zend_Validate_StringLength(array('min' => 7, 'max' => 16));
            $hashCodeValidator = new Zend_Validate_StringLength(8);
            $mailValidator = new Zend_Validate_EmailAddress();

            return true;
        } catch (Library_Exception $e) {

            return false;
        }
    }

    /**
     *
     * @param <type> $password - hasło
     * @param <type> $user - user
     * @return <type> - wygenerowany ciag
     */
    public function generatePassword($password, $user) {
        try {
            if ($password && $user) {
                $password = sha1(md5($password) . $user);
                return ($password) ? $password : false;
            } else {
                return false;
            }
        } catch (Library_Exception $e) {
            return false;
        }
    }

    /**
     * Generowanie losowego hasła
     * @return <type> Hasło losowe
     */
    public function generateRandomPassword() {
        /**
         * Generowanie losowego hasła
         */
        try {
            $chars = "abcdefghijkmnopqrstuvwxyz023456789$%#^@^*$(;'[]";
            srand((double) microtime() * 1000000);
            $i = 0;
            $pass = '';

            while ($i <= $this->maxPasswordSize) {
                $num = rand() % 33;
                $tmp = substr($chars, $num, 1);
                $pass = $pass . $tmp;
                $i++;
            }
            $md5password = md5($pass);
            if (count($md5password) > $this->maxPasswordSize) {
                $code = $md5password;
            } else {
                $code = substr($md5password, 0, $this->maxPasswordSize);
            }
            return ($code) ? $code : false;
        } catch (Library_Exception $e) {
            return false;
        }
    }

    /**
     * Wylogowanie usera
     * @return <type> true
     */
    public static function Logout() {
        try {
            $oLogOut = Zend_Auth::getInstance()->clearIdentity();
            return ($oLogOut) ? $oLogOut : false;
        } catch (Library_Exception $e) {

            return false;
        }
    }

}
