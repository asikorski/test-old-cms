<?php

class Helpers_URL {

    public $request;
    /*
     * Klasa słuzy do generowania przyjazdnego adresu url
     */

    public function __construct($parms) {
        $this->request=$parms;
    }

    public function Address($NewParms) {
        //generuje adres na podstawie tablicy
        $FinalParms = array_merge($this->request, $NewParms);
        if (!empty($FinalParms)) {
            foreach ($FinalParms as $key => $value) {
                $values[] = $key . '=' . $value;
            }
        }
        $values = urlencode(implode('&', $values));
        return $values;
    }
    public function margeAddress($url,$NewParms){
        return $url.'?'.$this->Address($NewParms);
    }

}
