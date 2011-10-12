<?php

/**
 * @author Arnold Sikorski
 */
class sites_TreeController extends Controller_Action {

    public $oRequest;
    public $options;

    public function Init() {
        /* Widget do obsługi Facebooka */
        parent::init();
        //inicjalizuje rodzica
        $request = $this->request->getQuery();
        $this->oRequest = $request;
        $this->view->parms = $request;
        $this->view->request = $request;
        $urlGenerator = new Helpers_URL($request);
        /* połaczenie z baza danych */
        $lang = 'pl';
        $module = $this->ModuleName;

        /* konstruktory */
        $itemsTableName = 'cm_' . $module . '_elements_' . $lang;
        $relationsTableName = 'cm_' . $module . '_relations_' . $lang;
        $treeTableName = 'cm_' . $module . '_categories_' . $lang;
        /* konektory */
        $this->oItems = $this->oItems = new Library_Items(array('name' => $itemsTableName));
        $this->oRelations = new Library_Relations(array('name' => $relationsTableName));
        $this->oTree = new Library_Tree(array('name' => $treeTableName));
        /* Ustawienia */
        $options = Zend_Registry::get('options');
        $this->options = $options->toArray();
        $this->view->assign('options', $this->options);
        /* moduły */
        /* Widget do obsługi Facebooka */
        $FacebookWidget = new Widgets_Facebook_Init();
        $this->view->oWidgetFacebook = $FacebookWidget->render();
        /* widget do wyswietlania zaisntalowanych modulów */
        $ModulesListWidget = new Widgets_ModuleList_Init();
        $this->view->oWidgetModulesList = $ModulesListWidget->render();
    }

    public function indexAction() {

    }

    public function editAction() {
        $parms = $this->oRequest;
        if (isset($parms['category_id']) && isset($parms['id'])) {
            //$category_id = (int)$parms['category_id'];
            $this->view->row = $parms;
        }
    }

    /**
     * @author Arnold Sikorski
     * Edytowanie elementu
     */
    public function edititemAction() {

        $this->_helper->layout->disableLayout();
        if (isset($this->oRequest['id'])) {
            $oItems = $this->oItems;
            $oRelations = $this->oRelations ;
            $id = (int) $this->oRequest['id'];
            $row = $oItems->getItemFromId($id);
            $files = $oRelations->getRelationsFromParent($id,array('ord ASC'));
                #pobieramy pliki
            $this->view->row = $row;
            $this->view->files = $files;
        } else {
            die;
        }
    }

    public function contentAction() {
        $request = $this->getRequest();
        $pRow = $request->getParams();
        //Pobieramy parametry
        //print_r($pRow);
        $bRow = array('id' => null,
            'category' => null,
            'language' => null);

        /* margowanie tablicy zapobiega */
        $dValidator = new Zend_Validate_Digits();
        /* validator */
        $row = array_intersect_key(array_merge($bRow, $pRow), $bRow);
        if (!empty($row['id']) && !empty($row['category'])) {
            if (($dValidator->isValid($row['id'])) && ($dValidator->isValid($row['category']))) {
                /* validatory sprawdziły dane */
                $oItems = $this->oItems;
                /* obiekt elementów */
                $row = $oItems->getItemFromId((int) $row['id']);
                /* pobieram z bazy */
                if ($row) {
                    $oView->row = $row;
                    print_r($row);
                    /* odbieramy dane postem zapisujemy */
                    if ($this->_request->isPost()) {
                        $query = $this->_request->getPost();
                        $rSave = $oItems->setItem((int) $row['id'], $query);
                        if ($rSave) {
                            $oView->save->response = true;
                        } else {
                            $oView->save->response = false;
                        }
                    }
                    /* koniec odbioru danych */
                } else {
                    $oView->error->response = true;
                }
            } else {
                $oView->error->response = true;
            }
        }
        $this->view->assign((array) $oView);
    }

    public function filesAction() {
        if (!isset($this->oRequest['id'])) {


            $this->view->error = array('code' => 'No type');
            die;
        } else {
            $oItems = $this->oItems;
            $id = (int) $this->oRequest['id'];
            $row = $oItems->getItemFromId($id);
            $this->view->row = $row;
            //$this->view->row['id'] = $id;
            // $oRelations = $this->oRelations;
            //$test = $oRelations->getRelationsFromParent($id);
        }
    }

    public function editfileAction() {
        $this->_helper->layout->disableLayout();
        if (isset($this->oRequest['id'])) {
            $oRelations = $this->oRelations;
            $id = (int) $this->oRequest['id'];
            $row = $oRelations->getrelationFromId($id);
            //new var_dump($row);
            //die;
            $this->view->row = $row;
        } else {
            die;
        }
    }

    public function categoriesAction() {

    }

    public function fileslistAction() {
        $relations = $this->oRelations->getAllRelations();
        if ($relations) {
            $filesCount = count($relations);
            //Ilosc elementów
            $sumSize = 0;
            foreach ($relations as $row) {
                $sumSize = $sumSize + (int) $row['size'];
            }
            //rozmiar elementów
        }
        $this->view->relationsList = $relations;
    }

}
