<?php

/**
 * @author Arnold Sikorski
 * Helper generujacy tag a
 */
class Global_View_Helper_TimePassed extends Zend_View_Helper_Abstract {

    /**
     * Constructor
     */
    public function __construct() {

    }
/**
 *
 * @param <type> $timestamp
 * @return string 
 */
    public function TimePassed($timestamp) {
        if (empty($timestamp)) {
            return "NA";
        } else {
            $difference = time() - $timestamp;
            if ($difference < 120) {
                $rtnval = $difference . " sek";
            } elseif ($difference < 7200) {
                $rtnval = round(($difference / 60), 0) . " minut";
            } elseif ($difference < 172800) {
                $rtnval = round(($difference / 3600), 0) . " godzin";
            } elseif ($difference < 168739200) {
                $rtnval = round(($difference / 86400), 0) . " dni";
            } else {
                $rtnval = "NA";
            }
            return $rtnval;
        }
    }

}