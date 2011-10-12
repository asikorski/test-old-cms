<?php

class Library_Votes extends Zend_Db_Table_Abstract {

    private $config;
    protected $_name;
    private $log = true;
    const CATEGORY_COLUMN = 'category_id';
    const USER_COLUMN = 'user_id';

    const VOTE_PLUS = 1;
    /* kolumna rodzica */

    /**
     * Konstruktor klasy
     *
     * @param <type> $config - tablica konfiguracyjna klasy
     */
    public function __construct($config = array()) {
        try {

            $this->_name = 'cm_sites_votes_' . $config['lang'];
            parent::__construct();
            if ($this->log) {
                Library_History::instans()->set(array('type' => 0,
                    'class' => __CLASS__,
                    'method' => __METHOD__,
                    'query' => $config));
            }
            return true;
        } catch (Library_Exception $e) {

            return false;
        }
    }

    public function getVotesByUserId($id, array $ord=array('date_add ASC')) {
        try {
            $select = $this->select();
            $select->where('user_id = ?', $id);
            $select->order($ord);

            $response = $this->_db->fetchAll($select, array(), Zend_Db::FETCH_ASSOC);
            return ($response) ? $response : false;
        } catch (Library_Exception $e) {
            // echo "error here";
            return false;
        }
    }

    public function getVotesByArticlesId($article_id) {
        try {
            $select = $this->select();
            $select->where('article_id = ?', $id);
            $select->order($ord);

            $response = $this->_db->fetchAll($select, array(), Zend_Db::FETCH_ASSOC);
            return ($response) ? $response : false;
        } catch (Library_Exception $e) {
            // echo "error here";
            return false;
        }
    }

    public function addVoteFromUser($user_id, $article_id, $vote) {
        try {
            $vote = (int) $vote;
            if (is_int($vote) && !$this->isUserVote($user_id, $article_id)) {
                $now = date("Y-m-d H:i:s");
                $row = array('element_id' => (int) $article_id,
                    'user_id' => (int) $user_id,
                    'vote' => $vote,
                    'date_add' => $now,
                    'date_mod' => $now);
                $id = $this->insert($row);
                return $id;
            } else {
                return false;
            }
        } catch (Library_Exception $e) {
            // echo "error here";
            return false;
        }
    }

    public function isUserVote($user_id, $article_id) {
        try {
            $select = $this->select();
            $select->where('element_id = ?', $article_id);
            $select->where('user_id = ?', $user_id);
            $select->limit(1);

            $response = $this->_db->fetchRow($select, array(), Zend_Db::FETCH_ASSOC);
            return ($response) ? true : false;
        } catch (Library_Exception $e) {
            // echo "error here";
            return false;
        }
    }

    public function getSumAndItemVoteByArticle($article_id) {

        try {
            //$query = 'SELECT SUM(vote) as vote, COUNT(vote) as count FROM ' . $this->_name . ' WHERE element_id = ' . (int) $article_id;
            
            $query = '  SELECT SUM(vote) as vote, COUNT(vote) as count, (100/COUNT(vote)*SUM(vote)) as pos,  element_id, se.category_id FROM ' . $this->_name . ' as sv 
                        LEFT JOIN cm_sites_elements_pl as se ON se.id=sv.element_id
                        WHERE element_id = ' . (int) $article_id.' 
                        GROUP BY element_id ORDER BY pos DESC';
            
            $response = $this->getAdapter()->query($query);
            $response = $response->fetch();

            return ($response) ? $response : FALSE;
        } catch (Library_Exception $e) {
            // echo "error here";
            return false;
        }
    }

    public function getVoteStat($category_id=null) {

        try {
            $query = '  SELECT SUM(vote) as vote, COUNT(vote) as count, (100/COUNT(vote)*SUM(vote)) as pos,  element_id, se.category_id FROM ' . $this->_name . ' as sv 
                        LEFT JOIN cm_sites_elements_pl as se ON se.id=sv.element_id';
            if($category_id){
                $query .= ' WHERE category_id = ' . (int) $category_id;
            }
            $query .= ' GROUP BY element_id ORDER BY pos DESC';

            $response = $this->getAdapter()->query($query);
            $response = $response->fetchAll();

            return ($response) ? $response : FALSE;
        } catch (Library_Exception $e) {
            // echo "error here";
            return false;
        }
    }

    public function addVoteStat($query) {

        try {
            $response = $this->getAdapter()->query($query);

            return ($response) ? $response : FALSE;
        } catch (Library_Exception $e) {
            // echo "error here";
            return false;
        }
    }

    public function getVotesByQuery($query) {
        try {
            if (is_array($query)) {
                if (count($query) == 1) {
                    $field = key($query);
                    $value = $query[$field];
                    //wartosci pol
                    $select = $this->select();
                    $select->where($field . ' = ?', $value);
                    $response = $this->_db->fetchRow($select, array(), Zend_Db::FETCH_ASSOC);

                    return $response;
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

}

?>
