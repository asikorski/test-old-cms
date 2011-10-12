<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Library_Relations extends Zend_Db_Table_Abstract {

    private $config;
    private $parent;
    private $log = false;

    /**
     *
     * @param <type> $config - tablica konfiguracyjna połaczenia z baza danych
     * @param <type> $parent - informacje o rodzicu
     */
    public function __construct(array $config = array()) {
        parent::__construct();
        $this->_name = $config['name'];
    }

    /**
     *
     * @param <type> $idParentRoot - id rodzica drzewa relacji
     * @param <type> $date - dane do zapisu
     */
    public function addRelationsRoot($idParentRoot, $date = array(), $relations = array()) {
        
    }

    /**
     *
     * @param <type> $idRoot - id korzenia relacji
     * @param <type> $date - dane do zapisu
     */
    public function setRelationsRoot($idRoot, $date = array()) {
        
    }

    /**
     *
     * @param <type> $idRoot - id korzenia
     * @param <type> $filter - filtr wyswietlanych danych
     * @param <type> $depth - głebia pobierania danych - 0 oznacza wszystko
     */
    public function getRelationsRoot($idRoot, $depth, $filter = array()) {
        
    }

    /**
     *
     * @param <type> $idRoot - id usuwanego korzenia
     */
    public function delateRelationsRoot($idRoot) {

    }

    public function getRelationsInfo() {
        
    }

    /**
     * Dodaje pojedyncza relacje pomiedzy elementem
     *
     * @param <type> $parent_id - id rodzica relacji
     * @return <type> - true jesli zapisano
     */
    public function addSingleParentRelation($parent_id, array $data = array()) {
        try {
            if (is_int($parent_id)) {
                $row = array(
                    'id_relations' => $parent_id,
                    'date_mod' => date('Y-m-d h:i:s'),
                    'date_add' => date('Y-m-d h:i:s'),
                );
                $row = array_merge($row, $data);
                $id = $this->insert($row);
                if ($this->log) {
                    Library_History::instans()->set(array('type' => 0,
                        'class' => __CLASS__,
                        'method' => __METHOD__,
                        'query' => $row));
                }
                return $id;
            } else {
                if ($this->log) {
                    Library_History::instans()->set(array('type' => 0,
                        'class' => __CLASS__,
                        'method' => __METHOD__,
                        'query' => 'bad data'));
                }
                return false;
            }
        } catch (Library_Exception $e) {

            return false;
        }
    }

    /**
     *
     * @param <type> $parent_id - id rodzica
     * @return <type> - table elementów
     */
    public function getRelationsFromParent($parent_id,array $ord = array('ord ASC')) {
        try {
            if (is_int($parent_id)) {
                $select = $this->select();
                $select->where('id_relations = ?', $parent_id);
                $select->order($ord);
                $response = $this->_db->fetchAll($select, array(), Zend_Db::FETCH_ASSOC);
                if ($this->log) {
                    Library_History::instans()->set(array('type' => 0, 'class' => __CLASS__, 'method' => __METHOD__, 'query' => $select->__toString()));
                }
                return $response;
            } else {
                if ($this->log) {
                    Library_History::instans()->set(array('type' => 0,
                        'class' => __CLASS__,
                        'method' => __METHOD__,
                        'query' => 'bad date'));
                }

                return false;
            }
        } catch (Library_Exception $e) {

            return false;
        }
    }

    public function getrelationFromId($id) {
        try {
            if (is_int($id)) {
                $select = $this->select();
                $select->where('id = ?', $id);
                $response = $this->_db->fetchRow($select, array(), Zend_Db::FETCH_ASSOC);
                if ($this->log) {
                    Library_History::instans()->set(array('type' => 0, 'class' => __CLASS__, 'method' => __METHOD__, 'query' => $select->__toString()));
                }
                return $response;
            } else {
                if ($this->log) {
                    Library_History::instans()->set(array('type' => 0,
                        'class' => __CLASS__,
                        'method' => __METHOD__,
                        'query' => 'bad date'));
                }

                return false;
            }
        } catch (Library_Exception $e) {

            return false;
        }
    }

    public function setRelation($id, array $data = array()) {
        try {
            if (is_array($data)) {

                $row = $this->getrelationFromId((int) $id);
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
                //return true;
            } else {
                if ($this->log) {
                    Library_History::instans()->set(array('type' => 0,
                        'class' => __CLASS__,
                        'method' => __METHOD__,
                        'query' => 'bad date'));
                }
                return false;
            }
        } catch (Library_Exception $e) {
            echo "error here";
            return false;
        }
    }
/**
 * Pobieram wszystkie relacje
 * @return <type> relacje
 */
    public function getAllRelations() {
        try {
            $select = $this->select();
            $response = $this->_db->fetchAll($select, array(), Zend_Db::FETCH_ASSOC);
            return $response;
        } catch (Library_Exception $e) {

            return false;
        }
    }
        public function deleteRelation($idItem) {
        try {
            $where = $this->getAdapter()->quoteInto('id = ?', $idItem);
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

}

?>
