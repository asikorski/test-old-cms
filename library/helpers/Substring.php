<?php
class Global_View_Helper_Substring extends Zend_View_Helper_Abstract {

    function substring($sString, $iInt, $sAdd = "...")
    {
		if(mb_strlen($sString, 'UTF-8') <= $iInt) {
			return $sString;
		} else {
			$iLast = mb_strrpos(mb_substr($sString, 0, $iInt, 'UTF-8') , " ", 0, 'UTF-8');
			$iLast = ($iLast == 0)? $iInt : $iLast;
			return mb_substr($sString, 0, $iLast, 'UTF-8').$sAdd;
		}
    }
}
?>