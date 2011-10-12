<?php

/**
 * @author Arnold Sikorski
 *
 * Klasa dziedziczy po modelu dostepu do bazy danych
 */
class Upload_Relations extends Zend_Db_Table_Abstract{



    public function GetRelationsFromId($category_id){
        /*
         * Pobieram relacje wzgledem pliku
         */
        $select = $this->select();
        $select->setIntegrityCheck( false );
        $select->where('id_relations = ?',$category_id);
       return $this->_db->fetchAll($select, array(), Zend_Db::FETCH_ASSOC);
    }
    public function UpdateRelationFromId($row, $id){
        
    }
    /**
     *  Usuwam z bazy wybrany element
     * @param <type> $id
     */
    public function RemoveRelationFromId($id){
        $where = $this->getAdapter()->quoteInto('id = ?',$id);
        $this->delete($where);
        return true;
    }
}