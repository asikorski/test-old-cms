<?php

/**
 * @author: Arnold Sikorski
 * 
 * Helper pomocny przy generowaniu przyjaznego adresu url
 */
class Global_Helpers_CategoryTree {

    public function CategoryTree($oTreeStructure) {
        $this->display(0, $oTreeStructure);
    }

    private function display($parentID, $tab) {

        if (!isset($tab[$parentID]) || !is_array($tab[$parentID]))
            return true;
        echo '<ul>';
        foreach ($tab[$parentID] as $element) { #iteracja podkategorii
?> <li id="rhtml_<?php echo $element['id']; ?>" value ="<?php echo $element['id']; ?>">
    <a rel ="123" href="<?php echo $this->ParmsURL(array(), array('category_id' => $element['id'], 'page' => 1)); ?>">
    <?php echo $element['name']; ?></a>

    <?
            $this->display($element['id'], $tab); #wyswietlenie podkategorii
            echo '</li>';

        }
        echo '</ul>';
    }

    public function ParmsURL(array $url = array(), array $parms = array()) {
        $request = Zend_Controller_Front::getInstance()->getRequest()->getQuery();
        $FinalParms = array_merge($request, $parms);
        if (!empty($FinalParms)) {
            foreach ($FinalParms as $key => $value) {
                $values[] = $key . '=' . $value;
            }
        }
        $url = $this->url($url);
        $values = implode('&', $values);
        //$values = urlencode(implode('&', $values));
        //Z niewiadomej przyczyny niedziała, moze sprawiać problemy w przyszłosci
        return $url . '?' . $values;
    }

    private function url(array $urlOptions = array(), $name = null, $reset = false, $encode = true) {
        $router = Zend_Controller_Front::getInstance()->getRouter();
        return $router->assemble($urlOptions, $name, $reset, $encode);
    }

}
