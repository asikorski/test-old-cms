<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Library_History {

    private $instans;
    private $string;
    private $config;
    private $response;
    private $inter = 0;

    

    /**
     *  Singleton do zliczania akcji
     * @return <type> Zwracam istniejaca klase
     */
    static function &instans() {
        static $instant;
        
        if (isset($instant)) {
            return $instant;
        } else {
            $instant = new Library_History();
            return $instant;
        }
    }
/**
 *  Dodaje nowy element do struktury akcji;
 * 
 * @param <type> $param - Paramtry do zapisania
 */
    public function set($param) {
        $param['microtime'] = microtime();
        $param['time'] = time();
        //$param['int']=$this->inter++;
        $this->string[] = array_merge($param);
      // new var_dump($this->string);
    }
    /**
     *
     * @return <type> - zwracam strukture wszystkich akcji
     */
    public function get() {
        //new var_dump($this->string);
        return $this->string;
    }

    /**
     *
     * @param <type> $config - Dane konfiguracyjne 
     */
    public function setConfiguration($config = array()) {
        $this->config = $config;
    }

}

?>
