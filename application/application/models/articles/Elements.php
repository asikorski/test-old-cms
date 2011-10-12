<?php

/**
 * @author Arnold Sikorski
 *
 * Model pobierajacy artykuły z Bazy danych
 */
class articles_Elements extends Zend_Db_Table_Abstract {

    protected $_name = 'cm_article_elements_';
    protected $_primary = 'id';
    public function  __construct() {
        

        $oLang = 'pl';
        $this->_name= $this->_name.$oLang;
            #pobieramy odpowiednia wersje jezykowa
        parent::__construct();
            #konstrujemy rodzica
    }
    public function getRowsByCategory($category_id) {
        /* metoda pobiera dane z bazy danych na podstawie wybranych kryteriów */

        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->where('category_id = ?', $category_id);
        return $this->_db->fetchAll($select, array(), Zend_Db::FETCH_ASSOC);
    }

    /*
     * Element na podstawie jego urla
     */

    public function getRowByUrl($url) {
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->where('name_url = ?', $url);
        $row = $this->_db->fetchRow($select, array(), Zend_Db::FETCH_ASSOC);
        if (!empty($row)) {
            return $row;
        } else {
            return false;
        }
    }

}