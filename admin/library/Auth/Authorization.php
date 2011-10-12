<?php

/*
 * @Author: Arnold Sikorski
 * @Desc: Plugin sprawdza czy user jest zautoryzowany
 */

class Auth_Authorization {

    protected $oAuth;
    protected $oAuthTableName = 'cm_admins';
    //tabela administratorów
    protected $oAuthAdapter;
    protected $db;
    public $oUser;

    /* kolumny przechowywujace dane */
    protected $_passwordColumn = 'password';
    protected $_nameColumn = 'name';
    protected $passMinLenght = 7;

    public function __construct() {
        
        try {
            $this->oAuth = Zend_Auth::getInstance();

            //inicjalizowanie obiektu autoryzacji
            $this->db = Zend_Registry::get('dbAdapter');
            //
            //$this->oAuthAdapter = new Zend_Auth_Adapter_DbTable($this->db, $this->oAuthTableName,'name','password');
            $this->oAuthAdapter = new Zend_Auth_Adapter_DbTable();
            $this->oAuth->setStorage(new Zend_Auth_Storage_Session('oAuth'));
            $this->oAuthAdapter
                    ->setTableName($this->oAuthTableName)
                    ->setIdentityColumn($this->_nameColumn)
                    ->setCredentialColumn($this->_passwordColumn);
        } catch (Library_Exception $e) {

            return false;
        }
    }

    /**
     *
     * @param <type> $name - nazwa usera do autoryzacji
     * @param <type> $password
     * @return <type>
     */
    public function Authorization($name, $password) {
        try {
            
                $this->oAuthAdapter
                        ->setIdentity($name)
                        ->setCredential(md5($password));

                $result = $this->oAuth->authenticate($this->oAuthAdapter);
                $storage = $this->oAuth->getStorage();
                //$storage = new Zend_Auth_Storage_Session('oAuth');
                if ($result->getCode() == Zend_Auth_Result::SUCCESS) {
                    $storage->write($this->oAuthAdapter->getResultRowObject());
                }
                Library_History::instans()->set(array('type' => 0,
                    'class' => __CLASS__,
                    'method' => __METHOD__,
                    'query' => array('status' => $result->getCode())));

                return $result;
            
        } catch (Library_Exception $e) {

            return false;
        }
    }

    /**
     *
     * @return <type> zwracam status autoryzacji
     */
    public function CheckStatus() {

        //echo "działamdf";
        // print_r($this->oAuthAdapter->getResultRowObject());

        try {
            //$auth = Zend_Auth::getInstance();
            $auth = Zend_Auth::getInstance();
            $status = $auth->hasIdentity();


            if ($status) {
                $stroge = $auth->getStorage();
                $oUser = $stroge->read();

                Zend_Registry::set('oUser', $oUser);
                //Dodajemy dane usera do rejestru
            }
            /* zapis do biblioteki */
            Library_History::instans()->set(array('type' => 0,
                'class' => __CLASS__,
                'method' => __METHOD__,
                'query' => array('status' => $status)));
            return $status;
        } catch (Library_Exception $e) {

            return false;
        }
    }

    /**
     *
     * @return <type> - wylogowuje usera
     */
    public function LogOut() {
        Library_History::instans()->set(array('type' => 0,
            'class' => __CLASS__,
            'method' => __METHOD__,
            'query' => array('status' => 'logout')));
        return Zend_Auth::getInstance()->clearIdentity();
    }

}

?>
