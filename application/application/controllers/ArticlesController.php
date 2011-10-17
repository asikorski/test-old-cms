<?php

/**
 * @author Arnold Sikorski
 * @category Controllers
 * @name IndexController
 *
 * Główny kontroler dostepu
 *
 * Do dumpowania danych korzystamy z new var_dump($cos);!!
 */
class ArticlesController extends myControllerView {

    public $oDbModel;

    public function init() {
        parent::init();

        $this->oDbModel = new CMS_Connection(array('module' => 'sites',
                    'lang' => 'pl',
                    'baseURL' => 'gazeta.localhost'));
    }

    public function indexAction() {
         if(CMS_Users::checkStatus()!=CMS_Users::USER_ACTIVE){
            $redirector = new Zend_Controller_Action_Helper_Redirector();
            $redirector->gotoRouteAndExit(array(null), 'userlogin', true);
        }
        /** TODO
         * - dodac walidacje czy jest juz taki art o podanym tytule
         * 
         */
        try {

            $path = realpath(dirname(__FILE__)) . '/../views/scripts/articles';
            $this->oView = $this->setZendView($path);
            if ($this->_request->isPost()) {
                $formDataResponse = $this->_request->getPost();

                $Form = new articles_AddArticleForm($formDataResponse);
                if ($Form->isValid($formDataResponse)) {
                    $formDataResponse['name'] = strip_tags($formDataResponse['name']);
                    $category = (int) $formDataResponse['category'];
                    $InsertData['name'] = (string) $formDataResponse['name'];
                    $InsertData['short_desc'] = (string) $formDataResponse['name'];
                    $InsertData['lead'] = strip_tags((string) $formDataResponse['lead']);
                    $InsertData['name_url'] = $this->toPermalink($formDataResponse['name']);
                    $InsertData['desc'] = $this->remove_tags($formDataResponse['text']);

                    $modelReturn = $this->oDbModel->addItem((int) $formDataResponse['category'], $InsertData);
                    if ($modelReturn) {
                        $this->oView->articleId = $modelReturn;
                        $this->layout->leftContent = $this->oView->render('articlesFoto.phtml');
                        return TRUE;
                    }
                }
            } else {
                $Form = new articles_AddArticleForm();
            }

            //generuje form
            //$form = '<form method="post"><input type="text" name="asd"><input type="submit" /></form>';//$Form;
            $this->oView->form = $Form;



            $this->oView->post = $_POST;
            $this->layout->leftContent = $this->oView->render('index.phtml');
            //$this->layout->leftContent = $this->oView->render('articlesFoto.phtml');
        } catch (Library_Exception $e) {

            return false;
        }
    }

    public function addAction() {
        
    }

    public function editAction() {
        
    }

    public function deleteAction() {
        
    }

    private function toPermalink($string) {


        $unPretty = array('/ä/', '/ö/', '/ü/', '/Ä/', '/Ö/', '/Ü/', '/ß/',
            '/ą/', '/Ą/', '/ć/', '/Ć/', '/ę/', '/Ę/', '/ł/', '/Ł/', '/ń/', '/Ń/', '/ó/', '/Ó/', '/ś/', '/Ś/', '/ź/', '/Ź/', '/ż/', '/Ż/',
            '/Š/', '/Ž/', '/š/', '/ž/', '/Ÿ/', '/Ŕ/', '/Á/', '/Â/', '/Ă/', '/Ä/', '/Ĺ/', '/Ç/', '/Č/', '/É/', '/Ę/', '/Ë/', '/Ě/', '/Í/', '/Î/', '/Ď/', '/Ń/',
            '/Ň/', '/Ó/', '/Ô/', '/Ő/', '/Ö/', '/Ř/', '/Ů/', '/Ú/', '/Ű/', '/Ü/', '/Ý/', '/ŕ/', '/á/', '/â/', '/ă/', '/ä/', '/ĺ/', '/ç/', '/č/', '/é/', '/ę/',
            '/ë/', '/ě/', '/í/', '/î/', '/ď/', '/ń/', '/ň/', '/ó/', '/ô/', '/ő/', '/ö/', '/ř/', '/ů/', '/ú/', '/ű/', '/ü/', '/ý/', '/˙/',
            '/Ţ/', '/ţ/', '/Đ/', '/đ/', '/ß/', '/Œ/', '/œ/', '/Ć/', '/ć/', '/ľ/');

        $pretty = array('ae', 'oe', 'ue', 'Ae', 'Oe', 'Ue', 'ss',
            'a', 'A', 'c', 'C', 'e', 'E', 'l', 'L', 'n', 'N', 'o', 'O', 's', 'S', 'z', 'Z', 'z', 'Z',
            'S', 'Z', 's', 'z', 'Y', 'A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'N',
            'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 'a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e',
            'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y',
            'TH', 'th', 'DH', 'dh', 'ss', 'OE', 'oe', 'AE', 'ae', 'u');

        $permalink = strtolower(preg_replace($unPretty, $pretty, $string));
        return str_replace(" ", "-", preg_replace("/[^a-zA-Z0-9 ]/", "", $permalink));
    }

    private function remove_tags($str) {
        $allowable_tags = '<b><i><sup><sub><em><strong><u><br><p>';
        return strip_tags($str, $allowable_tags);
    }

}

