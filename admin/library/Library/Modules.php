<?php

/**
 * Biblioteka do obsługi zainstalowanych modułów
 */
class Library_Modules extends Zend_Db_Table_Abstract {

    private $_path = '/modules';

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
     * @return <type> - zwracam tablice modułów
     */
    public function getModulesFromBase() {
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
 * @param <type> $stroge - scierzka do katalogu modulów
 * @return <type> - tablice danych
 */
    public function getModulesFromStroge($stroge) {
        try {
            if (is_dir($stroge)) {
                $ModuleDirectory = glob($stroge);
                return true;
            } else {
                return false;
            }
        } catch (Library_Exception $e) {

            return false;
        }
    }

    public function mergeExtis(array $Modules = array()) {
        return true;
    }

    /**
     *
     * @param array $date -
     * @return <type>
     */
    public function addModule(array $date = array()) {
        return true;
    }

    /**
     *
     * @param <type> $id - Usuwam wybrany moduł
     * @param <type> $deleteFromStroge - czy usunać z dysku
     * @return <type>
     */
    public function deleteModule($id, $deleteFromStroge = false) {
        return true;
    }

    /**
     *
     * @param array $date - dane modułu
     */
    public function installModule(array $date = array()) {
        try {
            $this->getAdapter()->query($query);
            return true;
        } catch (Library_Exception $e) {

            return false;
        }
    }

    /**
     *
     * @return <type> - Lista zainstalowanych modułów
     */
    public function getModules() {
        return $rows;
    }

    /**
     *
     * @param <type> $query - operacja zapisu
     * @return <type> true jesli wykonano
     *
     * Zapisuje do bazy strukture
     */
    public function queryStructureModule($query) {
        try {
            $this->getAdapter()->query($query);
            return true;
        } catch (Library_Exception $e) {

            return false;
        }
    }

}
