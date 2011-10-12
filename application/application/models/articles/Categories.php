<?php

/**
 * @author Arnold Sikorski
 *
 * Model pobierajacy artykuły z Bazy danych
 */
class articles_Categories extends Zend_Db_Table_Abstract {

    protected $_name = 'cm_article_categories_pl';
    protected $_primary = 'id';

    public function getRowsByCategory($category_id) {
        /* metoda pobiera dane z bazy danych na podstawie wybranych kryteriów */

        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->where('category_id = ?', $category_id);
        return $this->_db->fetchAll($select, array(), Zend_Db::FETCH_OBJ);
    }
    /*
     * Id kategorii na podstawie jej adresu url, jeśli brak zwraca pusty element
     */
    public function getIdByUrl($url){
        
    }

}