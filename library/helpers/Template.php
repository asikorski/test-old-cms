<?php

/**
 * @author Arnold Sikorski
 * Helper generujacy tag a
 */
class Global_View_Helper_Template extends Zend_View_Helper_Abstract {

    private $OPEN_BRACKET = "{";
    private $CLOSE_BRACKET = "}";

    /**
     * Constructor
     */
    public function __construct() {

    }

    /**
     *
     * @param <type> $template - ciag tekstowy ktory chcemy zamienić
     * @param <type> $source - zródło
     * @return <type>
     *
     *  $array = array("NAME" => "John Doe",
       "DOB"    => "12/21/1986",
      "ACL" => "Super Administrator");

      //template using '{' and '}' to signify variables
      $template = "This is your template, {NAME}. You were born on {DOB} and you are a {ACL} on this system.";
     */
    public function Template($template, $source = array()) {
        $ob_size = strlen($this->OPEN_BRACKET);
        $cb_size = strlen($this->CLOSE_BRACKET);

        $pos = 0;
        $end = strlen($template);

        while ($pos <= $end) {
            if ($pos_1 = strpos($template, $this->OPEN_BRACKET, $pos)) {
                if ($pos_1) {
                    $pos_2 = strpos($template, $this->CLOSE_BRACKET, $pos_1);

                    if ($pos_2) {
                        $return_length = ($pos_2 - $cb_size) - $pos_1;

                        $var = substr($template, $pos_1 + $ob_size, $return_length);

                        $template = str_replace($this->OPEN_BRACKET . $var . $this->CLOSE_BRACKET, $source[$var], $template);

                        $pos = $pos_2 + $cb_size;
                    } else {
                        return false;
                    }
                }
            } else {
                //exit the loop
                break;
            }
        }

        return $template;
    }

}