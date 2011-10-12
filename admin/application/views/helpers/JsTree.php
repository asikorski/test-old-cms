<?php

class Global_Helpers_JsTree {

    private $configuration;

    /**
     *
     * @param <type> $tree
     * @param <type> $config
     * @return <type>
     */
    public function JsTree($tree,array $config = array()) {
        $this->configuration = $config;
        $this->display(1, $tree);
        return true;
    }

    /**
     *
     * @param <type> $parentID - id rodzica
     * @param <type> $tab - tabela z danymi
     * @return <type> -
     */
    private function display($parentID, $tab) {

        if (!isset($tab[$parentID]) || !is_array($tab[$parentID]))
            return true;
        echo '<ul>';
        foreach ($tab[$parentID] as $element) {
            #iteracja podkategorii
            $url = array_merge($this->configuration,array('category'=>$element['id']));
            #adres url w postaci tablicy

?> <li>
                <span >
                    <span id ="cattreeid-<?php echo $element['id'];?>"><?php echo $element['name']; ?></span>
        </span>
    <?
            $this->display($element['id'], $tab); #wyswietlenie podkategorii
            echo '</li>';
        }
        echo '</ul>';
    }

    /**
     * Buduje drzewo adresu url
     * @param array $urlOptions
     * @param <type> $name
     * @param <type> $reset
     * @param <type> $encode
     * @return <type>
     */
    private function url(array $urlOptions = array(), $name = null, $reset = false, $encode = true) {
        $router = Zend_Controller_Front::getInstance()->getRouter();
        return $router->assemble($urlOptions, $name, $reset, $encode);
    }

}