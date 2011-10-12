<?php

class CronController extends Zend_Controller_Action {

    public $oDbModel;

    public function init() {
        parent::init();

        $this->oDbModel = new CMS_Connection(array('module' => 'sites',
                    'lang' => 'pl',
                    'baseURL' => 'gazeta.localhost'));
    }

    public function voteAction() {
        $this->oVotes = new Library_Votes(array('lang' => 'pl'));

        $requset = $this->oVotes->getVoteStat();
        //new var_dump($requset);
        $tab = array();
        foreach ($requset as $item) {
            if ($item['count'] >= 5 && $item['pos'] > 0) {
                if (isset($tab[$item['category_id']])) {
                    if (count($tab[$item['category_id']]) < 3) {
                        $tab[$item['category_id']][] = $item;
                    }
                } else {
                    if ($item['category_id']) {
                        $tab[$item['category_id']][] = $item;
                    }
                }
            }
        }

        new var_dump($tab);


        foreach ($tab as $itemCategory) {
            foreach ($itemCategory as $item) {
                $queryArray[] = ' (' . $item['vote'] . ', ' . $item['element_id'] . ', ' . $item['category_id'] . ') ';
            }
        }
        
        $query = 'TRUNCATE TABLE `cache_article_stat`';
        
        $requset = $this->oVotes->addVoteStat($query);
        
        $query = 'INSERT INTO cache_article_stat (`vote`, `element_id`, `category_id`) VALUES ' . implode(',', $queryArray);
        
        $requset = $this->oVotes->addVoteStat($query);
        echo 'test cron';
        die;
    }

    public function addAction() {
        $this->oVotes = new Library_Votes(array('lang' => 'pl'));
        for ($i = 0; $i < 6000; $i++) {
            $idArticle = rand(187, 221);
            $idUser = rand(1, 1000);
            $vote = rand(-1, 1);
            $this->oVotes->addVoteFromUser((int) $idUser, // bedzie brany z sesji
                    (int)$idArticle, (int) $vote);
        }
        die;
    }

}