<?php

/**
 * @author: Arnold Sikorski
 * Ajaxowy kontroler edcji kategorii i elementów
 */
class sites_AjaxController extends Controller_Action {

    private $cacheTree;
    public $oRequest;
    public $oCategoriesTreeModel;
    //public $_tables = array('tree' => 'sd', 'items' => 'cm_article_elements_pl');
    public $oItems;
    public $oRelations;

    /**
     * -------------------------------------------------------------------------
     * @author Arnold Sikorski
     * Inicjalizator klasy
     * -------------------------------------------------------------------------
     */
    public function Init() {
        parent::init();

        $this->oRequest = $this->request->getQuery();
        $this->_helper->layout->disableLayout();
        /* nazwy tabel */
        $lang = 'pl';
        $module = $this->ModuleName;

        /* konstruktory */
        $itemsTableName = 'cm_' . $module . '_elements_' . $lang;
        $relationsTableName = 'cm_' . $module . '_relations_' . $lang;
        $treeTableName = 'cm_' . $module . '_categories_' . $lang;


        /* modele */
        $this->oItems = $this->oItems = new Library_Items(array('name' => $itemsTableName));
        $this->oRelations = new Library_Relations(array('name' => $relationsTableName));
        $this->oTree = new Library_Tree(array('name' => $treeTableName));
        /* ustawienia */
        $options = Zend_Registry::get('options');
        $this->options = $options->toArray();
        $this->view->assign('options', $this->options);
    }

    /**
     * -------------------------------------------------------------------------
     * @author Arnold Sikorski
     * Dodaje element
     * -------------------------------------------------------------------------
     */
    public function addcategoryAction() {
        $this->oCategoriesTreeModel->AddCategory($this->oRequest['parentID'], $this->oRequest['name']);

        $this->_helper->viewRenderer->setNoRender();
    }

    /**
     * -------------------------------------------------------------------------
     * @author Arnold Sikorski
     * Usuwanie wybranego elementu z bazy
     * -------------------------------------------------------------------------
     */
    public function removeAction() {
        if (isset($this->oRequest['id'])) {
            //$this->oCategoriesTreeModel->AddElement();
            if ($this->oCategoriesTreeModel->removeRoot($this->oRequest['id'])) {
                $jResponse = array('response' => true, 'message' => 'save true');
            } else {
                $jResponse = array('response' => false, 'message' => 'save false');
            }
        } else {
            $jResponse = array('response' => false, 'message' => 'bad request');
        }
        echo Zend_Json::encode($jResponse);
        $this->_helper->viewRenderer->setNoRender();
    }

    /**
     * -------------------------------------------------------------------------
     * @author Arnold Sikorski
     * Edytuje wybrany element
     * -------------------------------------------------------------------------
     */
    public function renameAction() {
        if (isset($this->oRequest['id']) && isset($this->oRequest['name'])) {
            /* mamy requesta */

            if ($this->oCategoriesTreeModel->renameName($this->oRequest['id'], $this->oRequest['name'])) {
                $jResponse = array('response' => true, 'message' => 'save true');
            } else {
                $jResponse = array('response' => false, 'message' => 'save false');
            }
        } else {
            $jResponse = array('response' => false, 'message' => 'bad request');
        }
        echo Zend_Json::encode($jResponse);
        $this->_helper->viewRenderer->setNoRender();
    }

    /**
     * -------------------------------------------------------------------------
     * @author Arnold Sikorski
     * Przenosze element w drzewie
     * -------------------------------------------------------------------------
     */
    public function moveAction() {

        if (isset($this->oRequest['id']) && isset($this->oRequest['to'])) {
            /* mamy requesta */
            (int) $id = $this->oRequest['id'];
            (int) $to = $this->oRequest['to'];
            if ($this->oCategoriesTreeModel->move($id, $to)) {
                $jResponse = array('response' => true, 'message' => 'save true');
            } else {
                $jResponse = array('response' => false, 'message' => 'save false');
            }
        } else {
            $jResponse = array('response' => false, 'message' => 'bad request');
        }
        echo Zend_Json::encode($jResponse);
        $this->_helper->viewRenderer->setNoRender();
    }

    /**
     * -------------------------------------------------------------------------
     * @author Arnold Sikorski
     * Pobieram elementy wybranej kategorii
     * -------------------------------------------------------------------------
     */
    public function itemsAction() {
        $oMask = array('category_id', 'page');
        $jResponse = array();
        $this->_helper->viewRenderer->setNoRender();
        if (isset($this->oRequest['category_id'])) {
            $items = $this->oItems->getItemsInfoFromRoot($this->oRequest['category_id']);
            $jResponse = $items;
        } else {
            //$jResponse = array('response'=>false,'message'=>'bad request');
            $items = $this->oItems->getItemsInfoFromRoot(1);
            $data = array();
            $data['page'] = 1;
            $data['total'] = 1;
            $data['rows'] = array();

            foreach ($items as $value) {
                $data['rows'][] = array(
                    'id' => 5,
                    'cell' => $value
                );
            }
        }
        echo Zend_Json::encode($data);
    }

    /**
     * -------------------------------------------------------------------------
     * @author Arnold Sikorski
     * Pobieram elementy wybranej kategorii
     * -------------------------------------------------------------------------
     */
    public function getelementsAction() {

        $this->_helper->viewRenderer->setNoRender();

        if (isset($this->oRequest['category_id'])) {
            $items = $this->oItems->getItemsFromRoot((int) $this->oRequest['category_id'],array('ord ASC'));
            if ($items) {
                foreach ($items as $row) {
                    $name = $this->view->substring($row['name'], 64);
                    $itemsJson[] = array(
                        'position' => $this->view->partial('partial/positiontoolbar.phtml',
                                array('row' => $row)),
                        'action' => $this->view->partial('partial/itemtoolbar.phtml',
                                array('row' => $row)),
                        'id' => $row['id'],
                        'category_id' => $row['category_id'],
                        'active' => 'checked',
                        'icon'=>$this->view->partial('partial/itemicon.phtml',
                            array('row' => $this->oRelations->getRelationsFromParent((int) $row['id'],array('ord ASC')))),
                        'name' => $name,
                        'date_add' => $this->view->partial('partial/itemdisplaytime.phtml',
                            array('row' => $row)),
                        'url' => $row['name_url'],
                        'short_desc' => $row['short_desc']);
                }
            } else {
                $itemsJson[] = array('active' => 'checked', 'name' => '', 'url' => '', 'short_desc' => '');
            }
            echo Zend_Json::encode($itemsJson);
        }
    }

    /**
     * -------------------------------------------------------------------------
     * @author Arnold Sikorski
     * Pobieram relacje
     * -------------------------------------------------------------------------
     */
    public function getrelationsaAction() {
        $this->_helper->viewRenderer->setNoRender();
        if (isset($this->oRequest['category_id'])) {
            $items = $this->oItems->getItemsFromRoot((int) $this->oRequest['category_id']);
            if ($items) {
                foreach ($items as $row) {
                    $itemsJson[] = array('category_id' => 1, 'active' => 'checked', 'name' => $row['name'], 'url' => $row['name_url'], 'short_desc' => $row['short_desc']);
                }
            } else {
                $itemsJson[] = array('active' => 'checked', 'name' => '', 'url' => '', 'short_desc' => '');
            }

            echo Zend_Json::encode($itemsJson);
        }
    }

    /**
     * -------------------------------------------------------------------------
     * @author Arnold Sikorski
     * Pobieram wybrany element
     * -------------------------------------------------------------------------
     */
    public function getitemAction() {

        $this->_helper->viewRenderer->setNoRender();
        if (isset($this->oRequest['id'])) {
            $itemJson = $this->oItems->getItemFromId((int) $this->oRequest['id']);
            if (!$itemJson) {
                $itemJson = array('success' => false);
            }
        } else {
            $itemJson = array('success' => false);
        }
        echo Zend_Json::encode($itemJson);
    }

    /**
     * -------------------------------------------------------------------------
     * @author Arnold Sikorski
     * Zapisuje zmiany do bazy
     * -------------------------------------------------------------------------
     */
    public function setitemAction() {
        $this->_helper->viewRenderer->setNoRender();
        $params = $this->_request->getParams();
        if (!isset($params['id'])) {
            $itemJson = array('success' => false);
            die;
        } else {
            $oItems = $this->oItems;
            $id = (int) $params['id'];
            if ($this->_request->isPost()) {

                $query = $this->_request->getPost();
                $response = $oItems->setItem($id, $query);
                if (is_array($response)) {
                    $itemJson = array('success' => true, 'response' => $response);
                } else {
                    $itemJson = array('success' => false);
                }
            }
        }
        echo Zend_Json::encode($itemJson);
    }

    /**
     * -------------------------------------------------------------------------
     * @author Arnold Sikorski
     * Usuwam element z bazy
     * -------------------------------------------------------------------------
     */
    public function deleteitemAction() {
        $this->_helper->viewRenderer->setNoRender();
        $params = $this->_request->getParams();
        if (isset($params['id'])) {
            $itemJson = $this->oItems->deleteItem((int) $params['id']);
            if ($itemJson) {
                $itemJson = array('success' => true);
            } else {
                $itemJson = array('success' => false);
            }
        } else {
            $itemJson = array('success' => false);
        }
        echo Zend_Json::encode($itemJson);
    }

    /**
     * -------------------------------------------------------------------------
     * @author Arnold Sikorski
     * Pobieram relacje
     * -------------------------------------------------------------------------
     */
    public function getrelationsAction() {
        $params = $this->_request->getParams();

        $this->_helper->viewRenderer->setNoRender();

        /* parametry */
        $pRow = $this->_request->getParams();

        $bRow = array(
            'language' => null,
            'module' => null);

        /* margowanie tablicy zapobiega */
        $parmsRow = array_intersect_key(array_merge($bRow, $pRow), $bRow);
        /* end params */
        //print_r($parmsRow);
        $FilesEditor = new Library_Files();

        $FilesEditor->module = $this->ModuleName;
        $FilesEditor->lang = 'pl';
        $FilesEditor->BaseUrl = $this->options['http']['application'];

        $rows = $this->oRelations->getRelationsFromParent((int) $params['id']);

        if ($rows) {
            foreach ($rows as $row) {
                //$imgURL =
                $fileType = $FilesEditor->getFileType($row['filename']);

                $row['size'] = $this->view->FormatFileSize($row['size']);
                $row['name'] = $this->view->substring($row['name'], 64);
                /* przeliczam jednostki */
                //$this->view->partial('partial/itemtoolbar.phtml',
                // array('row' => $row)
                $rowArray = array('thumb' => $this->view->partial('partial/fileiconthumb.phtml',
                            array('row' => $row,
                                'parmsRow' => $parmsRow,
                                'filetype' => $fileType)),
                    'date' => $this->view->partial('partial/displaytime.phtml',
                            array('row' => $row)),
                    'action' => $this->view->partial('partial/filetoolbar.phtml',
                            array('row' => $row, 'filetype' => $fileType)));
                $itemsJson[] = array_merge($rowArray, $row);
            }
        } else {
            $itemsJson[] = array('active' => 'checked', 'filename' => '', 'date_add' => '');
        }


        echo Zend_Json::encode($itemsJson);

        if (isset($this->oRequest['parent_id'])) {

        }
    }

    public function removefileAction() {

    }

    /**
     * -------------------------------------------------------------------------
     * @author Arnold Sikorski
     * Usuwam wybrany element
     * -------------------------------------------------------------------------
     */
    public function removeitemAction() {
        $params = $this->_request->getParams();
        $this->_helper->viewRenderer->setNoRender();

        if (isset($params['id'])) {
            (int) $id = $params['id'];
            if ($this->oItems->deleteItem($id)) {
                $itemJson = array('success' => true);
            } else {
                $itemJson = array('success' => false);
            }
        } else {
            $itemJson = array('success' => false);
        }
        echo Zend_Json::encode($itemJson);
    }

    /**
     * -------------------------------------------------------------------------
     * @author Arnold Sikorski
     * Dodaje nowy element
     * -------------------------------------------------------------------------
     */
    public function additemAction() {
//        $params = $this->_request->getParams();
//        $this->_helper->viewRenderer->setNoRender();
//
//        $itemJson = array('success' => false);
//        echo Zend_Json::encode($itemJson);
//        die;
//
//        if (is_array($params)) {
//            if ($this->oItems->addItem((int) $params['id'], $params)) {
//                $itemJson = array('success' => true);
//            } else {
//                $itemJson = array('success' => false);
//            }
//        } else {
//            $itemJson = array('success' => false);
//        }
//        echo Zend_Json::encode($itemJson);
        $params = $this->_request->getParams();
        $this->_helper->viewRenderer->setNoRender();
        if (!isset($params['category_id'])) {

            $itemJson = array('success' => false);
        } else {
            $category_id = (int) $params['category_id'];
            $row = array('name' => 'Nowy element');
            $this->oItems->addItem($category_id, array('name' => 'Nowy element'));
            $itemJson = array('success' => true);
        }
        echo Zend_Json::encode($itemJson);
    }

    public function getsitestreeAction() {
        $this->_helper->viewRenderer->setNoRender();

        //  new var_dump($this->oTree->_RootToArrayTreeEasyUi(1));
        // $arrayRows = array_merge($this->oTree->_RootToArrayTreeEasyUi(1));
        // $rows = $this->oTree->_RootToArrayTreeEasyUi(1);
        $cTree = $this->oTree->_RootToArray(1);

        $this->_GenerateRecursiveTreeEasyUi(1, $cTree);
        if (!$this->cacheTree) {
            $this->cacheTree = $issetArray;
        }
        $rows = $this->cacheTree;
        if (is_array($rows)) {
            $itemJson = $rows;
        } else {
            $itemJson = array(array('id' => 1,
                    'name' => 'Empty',
                    'children' => ''));
        }
        $itemJson = $rows;
        //$itemJson['children'] = array_merge($this->oTree->_RootToArrayTreeEasyUi(1));

        echo Zend_Json::encode($itemJson);
    }

    public function copyitemAction() {
        $this->_helper->viewRenderer->setNoRender();
        $params = $this->_request->getParams();
        if (!isset($params['id'])) {

            $itemJson = array('success' => false);
        } else {
            $id = (int) $params['id'];
            $itemJson = array('success' => true);
        }
        echo Zend_Json::encode($itemJson);
    }

    public function setrelationAction() {

        $this->_helper->viewRenderer->setNoRender();
        $params = $this->_request->getParams();
        if (!isset($params['id'])) {
            $itemJson = array('success' => false);
            die;
        } else {
            $oRelations = $this->oRelations;
            $id = (int) $params['id'];
            if ($this->_request->isPost()) {

                $query = $this->_request->getPost();
                $response = $oRelations->setRelation($id, $query);
                if (is_array($response)) {
                    $itemJson = array('success' => true, 'response' => $response);
                } else {
                    $itemJson = array('success' => false);
                }
            }
        }
        echo Zend_Json::encode($itemJson);
    }

    /**
     * -------------------------------------------------------------------------
     * @author Arnold Sikorski
     * Pobieram dane wykorzystujac do tego rekursywne generowanie struktuey
     * -------------------------------------------------------------------------
     */
    public function getcategoriesAction() {
        $this->_helper->viewRenderer->setNoRender();
        $rows = $this->oTree->_RootToArraySimpleTreeEasyUi(1);

        $pRow = $this->_request->getParams();
        /* maska danych */
        if (is_array($pRow)) {
            $bRow = array('root' => 1);
            /* margowanie tablicy zapobiega */
            $row = array_intersect_key(array_merge($bRow, $pRow), $bRow);
            //łączymy dwie tablice danych, usuwajac niepotrzebne rekordy
            if (isset($row['root'])) {
                $root = (int) $row['root'];
                $rows = $this->oTree->_RootToArraySimpleTreeEasyUi($root);
                $itemJson = $rows;
//                if (is_array($rows)) {
//                    $itemJson = array(array('id' => 9999,
//                            'text' => 'Drzewo',
//                            'children' => array_merge($rows)));
//                    /* genereuje strukture */
//                } else {
//                    $itemJson = array(array('id' => 1,
//                            'text' => 'Elementy',
//                            'children' => ''));
//                }
            }
        } else {
            $itemJson = array('success' => false);
        }

        echo Zend_Json::encode($itemJson);
    }

    /**
     * -------------------------------------------------------------------------
     * @author Arnold Sikorski
     * Dodaje nowa kategorie
     * -------------------------------------------------------------------------
     */
    public function addnewnodetorootAction() {
        $this->_helper->viewRenderer->setNoRender();
        $pRow = $this->_request->getParams();
        /* maska danych */
        if (is_array($pRow)) {
            $bRow = array('root' => 1, 'name' => '');
            /* margowanie tablicy zapobiega */
            $row = array_intersect_key(array_merge($bRow, $pRow), $bRow);
            //łączymy dwie tablice danych, usuwajac niepotrzebne rekordy
            if (isset($row['root']) && isset($row['name'])) {
                $root = (int) $row['root'];
                /* dodaje nowy korzeń drzewa */
                $response = $this->oTree->addRoot($root, array('name' => $row['name']));
                if ($response) {
                    $itemJson = array('success' => true);
                } else {
                    $itemJson = array('success' => false);
                }
            }
        } else {
            $itemJson = array('success' => false);
        }
        echo Zend_Json::encode($itemJson);
    }

    /**
     * -------------------------------------------------------------------------
     * @author Arnold Sikorski
     * Dodaje nowa kategorie
     * -------------------------------------------------------------------------
     */
    public function removerootAction() {
        $this->_helper->viewRenderer->setNoRender();

        $pRow = $this->_request->getParams();

        /* maska danych */
        if (is_array($pRow)) {
            $bRow = array('roots' => array());

            /* margowanie tablicy zapobiega */
            $row = array_intersect_key(array_merge($bRow, $pRow), $bRow);

            //łączymy dwie tablice danych, usuwajac niepotrzebne rekordy
            if (isset($row['roots'])) {
                $roots = $row['roots'];

                    $this->oTree->delateRoot($roots);

                $itemJson = array('success' => true);
            }
        } else {
            $itemJson = array('success' => false);
        }
        echo Zend_Json::encode($itemJson);
    }

    /**
     * -------------------------------------------------------------------------
     * @author Arnold Sikorski
     * Dodaje nowa kategorie
     * -------------------------------------------------------------------------
     */
    public function moverootAction() {
        $this->_helper->viewRenderer->setNoRender();

        $pRow = $this->_request->getParams();
        // print_r( $pRow);
        /* maska danych */
        if (is_array($pRow)) {
            $bRow = array('root' => null, 'target' => null);

            /* margowanie tablicy zapobiega */
            $row = array_intersect_key(array_merge($bRow, $pRow), $bRow);

            //łączymy dwie tablice danych, usuwajac niepotrzebne rekordy
            if (isset($row['root']) && isset($row['target'])) {
                $root = (int) $row['root'];
                $to = (int) $row['target'];
                $response = $this->oTree->moveRoot($root, $to);
                //Przenosze elementy
                if ($response) {
                    $itemJson = array('success' => true);
                } else {
                    $itemJson = array('success' => false);
                }
            } else {
                $itemJson = array('success' => false);
            }
        } else {
            $itemJson = array('success' => false);
        }
        echo Zend_Json::encode($itemJson);
    }

    /**
     * -------------------------------------------------------------------------
     * @author Arnold Sikorski
     * Wyszukuje elementy w bazie danych
     * -------------------------------------------------------------------------
     */
    public function serachitemAction() {
        $this->_helper->viewRenderer->setNoRender();
        $items = $this->oItems->getItemsFromRoot(2);
        if ($items) {
            foreach ($items as $row) {
//                    $action = $this->view->formCheckbox('active', null, array('checked' => 'dfd'), array(
//                            'checked' => '1',
//                            'unChecked' => '0'
//                        ));
                $activeClass = ($row['active']) ? 'sprite-accept' : 'sprite-cross';
                $action = 'g';
//Data dodania
                $dateAdd = ($row['date_add']) ? date('m-d-Y', strtotime($row['date_add'])) : '--';
                $name = $this->view->substring($row['name'], 64);
                $itemsJson[] = array('action' => $action,
                    'id' => $row['id'],
                    'category_id' => $row['category_id'],
                    'active' => 'checked',
                    'name' => $name,
                    'date_add' => $dateAdd,
                    'url' => $row['name_url'],
                    'short_desc' => $row['short_desc']);
            }
        } else {
            $itemsJson[] = array('active' => 'checked', 'name' => '', 'url' => '', 'short_desc' => '');
        }
        echo Zend_Json::encode($itemsJson);
    }

    /**
     *
     */
    public function deleterelationAction() {
        $this->_helper->viewRenderer->setNoRender();
        $params = $this->_request->getParams();

        if (isset($params['id'])) {
            $itemJson = $this->oRelations->deleteRelation((int) $params['id']);
            if ($itemJson) {
                $itemJson = array('success' => true);
            } else {
                $itemJson = array('success' => false);
            }
        } else {
            $itemJson = array('success' => false);
        }
        echo Zend_Json::encode($itemJson);
    }
    /**
     * dfd
     */
    public function moveupitemAction(){
        $this->_helper->viewRenderer->setNoRender();
         $params = $this->_request->getParams();
         $items = $this->oItems->setItemPosUp((int)$params['id']);


    }
        public function movedownitemAction(){
        $this->_helper->viewRenderer->setNoRender();
         $params = $this->_request->getParams();
         $items = $this->oItems->setItemPosDown((int)$params['id']);


    }

    /**
     * Rekursywne generowanie drzewa
     *
     * @param <type> $parentID
     * @param <type> $tab
     * @return <type>
     */
    private function _GenerateRecursiveTreeEasyUi($parentID, $tab) {
        if (!isset($tab[$parentID]) || !is_array($tab[$parentID])) {
            return false;
        } else {
            foreach ($tab[$parentID] as $element) { #iteracja podkategorii
                $row = $element;
                unset($element['ip'], $element['parentID'], $element['depth'], $element['desc'], $element['date_mod'], $element['date_add'], $element['ord'], $element['active'], $element['name_url'], $element['short_desc']);
                /*usuwanie niepotrzebnych elementów i generowanie toolabara*/
                $element['iconCls'] = 'sd';
                /*suma elementów*/
                $items = $this->oItems->getItemsFromRoot((int)$element['id'],array('ord ASC'));

                $element['name'] = $element['name'].' <span style ="font-size:9px;">('.count($items).')</span>';
                $element['active'] = $this->view->partial('partial/treeactive.phtml',
                                array('row' => $row));
                $element['action'] =  $this->view->partial('partial/treeitemedit.phtml',
                                array('row' => $row));
                $cache[$element['id']] = array_merge($element);
                $response = $this->_GenerateRecursiveTreeEasyUi($element['id'], $tab); #wyswietlenie podkategorii
                if ($response) {
                    $cache[$element['id']]['children'] = array_merge($response);
                }
            }

            $this->cacheTree = array_merge($cache);
            return $cache;
        }
    }

}
