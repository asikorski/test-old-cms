<?php
class Global_View_Helper_GoogleAnalytics extends Zend_View_Helper_Abstract {

    function googleAnalytics($uid)
    {
        return '<script type="text/javascript">'."\n".
               'var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");'."\n".
               'document.write(unescape("%3Cscript src=\'" + gaJsHost + "google-analytics.com/ga.js\' type=\'text/javascript\'%3E%3C/script%3E"));'."\n".
               'var pageTracker = _gat._getTracker("'.$uid.'");'."\n".
               'pageTracker._initData();'."\n".
               'pageTracker._trackPageview();'."\n".
               '</script>';
    }
}
