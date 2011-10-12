<?php

class Library_CacheVote extends Zend_Db_Table_Abstract {

    private $config;
    protected $_name;
    private $log = true;
    const CATEGORY_COLUMN = 'category_id';

    const VOTE_PLUS = 1;
    /* kolumna rodzica */

    /**
     * Konstruktor klasy
     *
     * @param <type> $config - tablica konfiguracyjna klasy
     */
    public function __construct($config = array()) {
        try {

            $this->_name = 'cache_article_stat';
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

    public function getTopArticle($category_id = null, $groupby=null) {
        try {
            $select = $this->select();
            if ($category_id) {
                $select->where('category_id = ?', $category_id);
            }
            if($groupby){
                $select->group($groupby);
            }
            
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
            $query = 'SELECT SUM(vote) as vote, COUNT(vote) as count FROM ' . $this->_name . ' WHERE element_id = ' . (int) $article_id;

            $response = $this->getAdapter()->query($query);
            $response = $response->fetch();

            return ($response) ? $response : FALSE;
        } catch (Library_Exception $e) {
            // echo "error here";
            return false;
        }
    }

    public function getVoteStat() {

        try {
            $query = '  SELECT SUM(vote) as vote, COUNT(vote) as count, (100/COUNT(vote)*SUM(vote)) as pos,  element_id, se.category_id FROM ' . $this->_name . ' as sv 
                        LEFT JOIN cm_sites_elements_pl as se ON se.id=sv.element_id
                        GROUP BY element_id ORDER BY pos DESC';

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
