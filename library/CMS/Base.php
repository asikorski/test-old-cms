<?php

/**
 * Spina wszystkie struntury w jedna
 */
class CMS_Base {

    private $_relationTable;
    private $_sitesTable;
    private $_treeTable;
    private $tables = array(
        'relations' => 'relations',
        'tree' => 'categories',
        'items' => 'elements');
    #table
    private $baseURL;
    private $tablePrefix = 'cm';

    #moduły kontrolne;
    private $oRelations;
    private $oSites;
    private $oTree;
    private $module;
    private $lang;

    public function __construct(array $config = array()) {
        $conf = array('module' => null,
            'lang' => null,
            'baseURL' => null,
        );
        $FinalConf = array_merge($conf, $config);
        $this->module = $FinalConf['module'];
        $this->lang = $FinalConf['lang'];
        #marguje tablice
        $this->_relationTable = $this->tablePrefix . '_' . $FinalConf['module'] . '_' . $this->tables['relations'] . '_' . $FinalConf['lang'];
        $this->_sitesTable = $this->tablePrefix . '_' . $FinalConf['module'] . '_' . $this->tables['items'] . '_' . $FinalConf['lang'];
        $this->_treeTable = $this->tablePrefix . '_' . $FinalConf['module'] . '_' . $this->tables['tree'] . '_' . $FinalConf['lang'];
        #tworzymy tabele
        $this->baseURL = $FinalConf['baseURL'];

        /* podpinam odpowiednie moduły kontrolne */
        $this->oSites = new CMS_Items(array('name' => $this->_sitesTable));
        $this->oTree = new CMS_Tree(array('name' => $this->_treeTable));
        $this->oRelations = new CMS_Relations(array('name' => $this->_relationTable));
    }

    /**
     *
     * @param <type> $name - nazwa;
     * @param <type> $isFile - czy mam tez zwracać pliki
     * @return <type> - zwracam obiekt
     */
    public function getItemByName($name, $isFile) {
        if ($name) {
            $row['item'] = $this->oSites->getItemByUrl($name);
            if ($row['item']) {
                if ($isFile) {
                    $files = $this->getFilesByRelation((int) $row['item']['id']);
                    $row = array_merge($row, $files);
                }
                return $row;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     *
     * @param <type> $id
     * @param <type> $isFile
     * @return <type>
     */
    public function getItemById($id, $isFile) {
        if (is_int($id)) {
            $row['item'] = $this->oSites->getItemFromId((int) $id);
            if ($isFile) {
                $files = $this->getFilesByRelation($id);
                $row = array_merge($row, $files);
            }
            return $row;
        } else {
            return false;
        }
    }

    /**
     *
     * @param <type> $id_parent
     * @return <type>
     */
    public function getItemsByParent($id_parent, $isFile) {
        if (is_int($id_parent)) {
            $rows = $this->oSites->getItemsByCategory($id_parent);
            if ($rows) {
                if ($isFile) {
                    foreach ($rows as $key => $row) {
                        unset($files);
                        $files = $this->getFilesByRelation((int) $row['id']);

                        $items[]['item'] = array('row' => $row,
                            'files' => $files);
                    }
                } else {
                    $items = $rows;
                }
                return $items;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     *
     * @param <type> $id_parent
     * @return <type>
     */
    public function getCountItemsByParent($id_parent) {
        return $rows;
    }

    /**
     * @author: Arnold Sikorski
     * @param <type> $id - Id relacji
     * @return <type> - tablica elementów danej relacji
     */
    public function getFilesByRelation($id) {
        $files = $this->oRelations->getRelationsFromParent($id);
        if ($files) {
            $row = null;
            foreach ($files as $file => $key) {
                unset($cache);
                if ($key['extension'] == 'image') {

                    $cacheArray['_140px'] = array(
                        'height' => '240px',
                        'width' => 'auto',
                        'crop-top' => '0px',
                        'crop-right' => '0px',
                        'crop-right' => '0px',
                        'crop-left' => '0px',
                        'desaturation' => 'none',
                        'rotate' => 'none');
                     $cacheArray['_140px_black'] = array(
                        'height' => '240px',
                        'width' => 'auto',
                        'crop-top' => '0px',
                        'crop-right' => '0px',
                        'crop-right' => '0px',
                        'crop-left' => '0px',
                        'desaturation' => 'true',
                        'rotate' => 'none');
                    $cacheArray['_500px'] = array(
                        'height' => '500px',
                        'width' => 'auto',
                        'crop-top' => '0px',
                        'crop-right' => '0px',
                        'crop-right' => '0px',
                        'crop-left' => '0px',
                        'desaturation' => 'none',
                        'rotate' => 'none');
                    $cacheArray['_260px'] = array(
                        'height' => '260px',
                        'width' => 'auto',
                        'crop-top' => '0px',
                        'crop-right' => '0px',
                        'crop-right' => '0px',
                        'crop-left' => '0px',
                        'desaturation' => 'none',
                        'rotate' => 'none');
                    $cacheArray['_289px'] = array(
                        'height' => '289px',
                        'width' => 'auto',
                        'crop-top' => '0px',
                        'crop-right' => '0px',
                        'crop-right' => '0px',
                        'crop-left' => '0px',
                        'desaturation' => 'none',
                        'rotate' => 'none');
                    $cacheArray['_420px'] = array(
                        'height' => 'auto',
                        'width' => '420px',
                        'crop-top' => '0px',
                        'crop-right' => '0px',
                        'crop-right' => '0px',
                        'crop-left' => '0px',
                        'desaturation' => 'none',
                        'rotate' => 'none');
                    $img = $this->generateImgUrl($key['filename'], $cacheArray);

                    $download = $this->fileToURL($key['filename']);
                    //$row['files']['images'] = $files;
                    $cache['info'] = $key;
                    $cache['urls'] = $img;
                    $cache['download'] = $download;
                    $row['files']['images'][] = $cache;
                } else {
                    $cache['info'] = $key;
                    $cache['download'] = $this->fileToURL($key['filename']);
                    $row['files']['other'][] = $cache;

                    //$row['files']['other'] = $files;
                }
            }
            if ($row) {
                return $row;
            } else {
                return array();
            }
        } else {
            return array();
        }
    }

    /**
     *
     * @param <type> $root
     * @return <type>
     */
    public function getTreeByRoot($root) {
        if (is_int($root)) {
            $rowTree = $this->oTree->getRoot((int) $root);
            return $rowTree;
        } else {
            return false;
        }
    }

    /**
     *
     * @param <type> $file
     * @param <type> $width
     * @param <type> $height
     * @return string
     */
    public function imageToUrl($file, $width, $height) {
        $header = 'http://';
        $function = 'gfx';
        $static = 'static';

        $url = $header . $this->baseURL . '/' . $function . '/' . $width . '/' . $height . '/' . $this->module . '_' . $this->lang . '/' . $file;
        return $url;
    }

    /**
     *
     * @param <type> $file
     * @return string
     */
    public function fileToURL($file) {
        #RewriteRule ^files/(.*) getfile.php?file=_files/download/$1 [L]
        $header = 'http://';
        $function = 'files';
        $static = 'static';

        $url = $header . $static . '.' . $this->baseURL . '/' . $function . '/' . $this->module . '_' . $this->lang . '/' . $file;
        return $url;
    }

    /**
     *
     * @param <type> $name - nazwa grafiki
     * @param array $elements - tablica danych do wygenerowania
     */
    public function generateImgUrl($file, array $elements = array()) {
        $header = 'http://';
        $function = 'gfx';
        $static = 'static';
        /* zmienne nagłówków */
        $imagesArray = array();
        /* tablica obrazków */
//        $cacheArray[] = array('height' => null,
//            'width' => null,
//            'crop-top' => null,
//            'crop-right' => null,
//            'crop-right' => null,
//            'crop-left' => null,
//            'desaturation' => null,
//            'rotate' => null);
//        $finalArray = array_merge($elements, $cacheArray);

        foreach ($elements as$key => $value) {

            $resize = $value['height'] . ',' . $value['width'];
            $crop = $value['crop-top'] . ',' . $value['crop-right'] . $value['crop-right'] . ',' . $value['crop-left'];
            $destruct = $value['desaturation'];
            $rotate = $value['rotate'];
            $imagesArray[$key] = $header . $this->baseURL . '/' . $function .
                    '/resize/' . $resize . '/crop/' . $crop . '/destruct/' .
                    $destruct . '/rotate/' . $rotate . '/module/' . $this->module . '_' . $this->lang . '/file/' . $file;
        }
        return $imagesArray;
    }

}