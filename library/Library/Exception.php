<?php
/**
 * Rozbudowana obłśuga błedow wraz z zapisem do pliku
 *
 * @author: Arnold Sikorski
 */
class Library_Exception extends Zend_Exception{
    /**
     * Konstruktor obsługo błedow
     * 
     * @param <type> $msg
     * @param <type> $code
     * @param Exception $previous
     */
    public function  __construct($msg = '', $code = 0, Exception $previous = null) {
        parent::__construct($msg, $code, $previous);
        Library_History::instans()->set(array('type'=>1,
                                                  'class' => __CLASS__,
                                                  'method' => __METHOD__,
                                                  'query' => parent::__toString()));
        
    }
}