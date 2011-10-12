<?php
/**
 * Struktura obsługi wersji jezykowych
 */
class Library_Lang  extends Zend_Db_Table_Abstract{

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
     * @return <type> tablice jezyków
     */
    public function getLangs() {
        try {
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
     *
     * @param <type> $id - id jezyka
     * @param array $date - dane do zmiany
     * @return <type>
     */
    public function setLang($id, array $date = array()) {
        try {
            if ($this->log) {
                Library_History::instans()->set(array('type' => 0,
                    'class' => __CLASS__,
                    'method' => __METHOD__,
                    'query' => true));
            }
            return true;
        } catch (Library_Exception $e) {

            return false;
        }
    }

    /**
     *
     * @param <type> $name nazwa
     * @return <type> tablica elementów
     */
    public function getLangByName($name) {
        try {
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
     * @param <type> $id - id
     * @return <type>  tablica elementów
     */
    public function getLangById($id) {
        try {
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
     * @param <type> $id -id
     * @return <type> true jesli usnieto
     */
    public function deleteLang($id) {
        try {
            if ($this->log) {
                Library_History::instans()->set(array('type' => 0,
                    'class' => __CLASS__,
                    'method' => __METHOD__,
                    'query' => true));
            }
            return true;
        } catch (Library_Exception $e) {

            return false;
        }
    }
    /**
     *
     * @param array $date - dane do zapisu
     */
    public function addNew(array $date = array()){
         try {
            if ($this->log) {
                Library_History::instans()->set(array('type' => 0,
                    'class' => __CLASS__,
                    'method' => __METHOD__,
                    'query' => $date));
            }
            return true;
        } catch (Library_Exception $e) {

            return false;
        }
    }

}