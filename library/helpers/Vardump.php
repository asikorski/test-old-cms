<?php

/**
 * @author Arnold Sikorski
 * Helper generujacy tag a
 */
class Global_View_Helper_Vardump extends Zend_View_Helper_Abstract {

    /**
     * Constructor
     */
    public function __construct() {

    }

    /**
     * Output the <img /> tag
     *
     * @param string $path
     * @param array $params
     * @return string
     */
    public function vardump($string) {
        $debuger = new var_dump($string);
        $debuger->isHtml = true;
?>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js" type="text/javascript"><!--mce:0--></script>
        <link type="text/css" href="/debug/css/smoothness/jquery-ui-1.8.16.custom.css" rel="stylesheet" />
        <div style ="display:none;"id="debugWindow">
<?php echo $debuger->html ?></div>
        <script type="text/javascript">
            $(document).ready(function() {
                $("#debugWindow").dialog({
                    title: 'Debug Mode',

                    buttons: [        
                        {
                            text: "Zamknij",
                            click: function() { $(this).dialog("close"); }
                        }
                    ]  })
            });
        </script>
<?php
    }

}