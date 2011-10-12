<?php

class Library_Array {
/**
 *
 * @param <type> $object - obiekt do zmiany
 * @return <type> - tablica
 */
    public function object_to_array($object) {
        if (is_array($object) || is_object($object)) {
            $array = array();
            foreach ($object as $key => $value) {
                $array[$key] = $this->object_to_array($value);
            }
            return $array;
        }
        return $object;
    }

// Funcion de Array a Objeto
    public function array_to_object($array = array()) {
        return (object) $array;
    }
    public function test(){
        return 'test';
    }

}