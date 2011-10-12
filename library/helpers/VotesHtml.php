<?php

/**
 * @author Arnold Sikorski
 * Helper generujacy tag a
 */
class Global_View_Helper_VotesHtml extends Zend_View_Helper_Abstract {
    
    
    const SKOK = -0.73;
    const SKOKLIST = 0.43;

    /**
     * Constructor
     */
    public function __construct() {
        
    }

    public function VotesHtml($element_id = null, $sumVote=null, $countVote=null) {
        if($sumVote && $countVote){
            return $this->VotesHtmlList($sumVote, $countVote);
        }
        $oVotes = new Library_Votes(array('lang' => 'pl'));
        $response = $oVotes->getSumAndItemVoteByArticle($element_id);

        $sumVote = $response['vote'];
        $countVote = $response['count'];
        

        $lambda = 100 / $countVote;
        $Pvote = $sumVote * $lambda;
        $votePx = ceil($Pvote * self::SKOK);
        
        if($votePx > 73){
            $votePx = 73;
        }
        if($votePx < -73){
            $votePx = -73;
        }
        return '<img style="position: relative; left: '.$votePx.'px" src="/img/1_44.png" />';
    }
    
    public function VotesHtmlList($sumVote, $countVote) {

        $lambda = 100 / $countVote;
        $Pvote = $sumVote * $lambda;
        $votePx = ceil($Pvote * self::SKOKLIST);
        
        if($votePx > 43){
            $votePx = 43;
        }
        if($votePx < -43){
            $votePx = -43;
        }
        
        if($votePx > 0){
            $votePx = 43-$votePx;
        }
        if($votePx < 0){
            $votePx = 86 + $votePx; 
        }
        
        
        
        $s = '<img style="position: relative; left: '.$votePx.'px" src="/img/1_37.png" />';
        return $s;
        echo htmlspecialchars($s);die;
    }
    
    public function VotesHtmlList1($sumVote, $countVote) {

        
        $votePx = self::SKOKLIST + ceil(100*($countVote/-$sumVote));
        if($votePx < 0){
            $votePx = 0;
        }
        if($votePx > 86){
            $votePx = 86;
        }
        
        return '<img style="position: relative; left: '.$votePx.'px" src="/img/1_37.png" />';
    }

}