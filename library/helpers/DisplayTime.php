<?php 
class Global_View_Helper_DisplayTime extends Zend_View_Helper_Abstract {

    /**
     * @param integer $time time in secounds
     */
    public function displayTime($time)
    {
        $mod = 60;
 
        $secounds = $time;
        
        $minutes  = floor($time/$mod);
        if($minutes>0) $secounds-=($minutes*$mod);
        
        $hours  = floor($minutes/$mod);
        if($hours>0) $minutes-=($hours*$mod);
/*        
        return (($hours>0)?$hours.'h ':null).
               (($minutes>0)?sprintf("%02d",$minutes).'m ':null).
               sprintf("%02d",$secounds).'s';
               */
        return (($hours>0)?$hours.':':null).
               sprintf("%02d",$minutes).':'.
               sprintf("%02d",$secounds);
               
    }
}
