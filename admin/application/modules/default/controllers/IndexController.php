<?php

/**
 * @author Arnold Sikorski
 */
class default_IndexController extends Controller_Action {
    public $options;
    /*
     * Nazwa modułu i kontrolera to: $this->request->getModuleName(),$this->request->getControllerName()
     * $this->oUser nazwa usera
     */

    public function Init() {

        $this->view->controller =
                /* Widget do obsługi Facebooka */
                $FacebookWidget = new Widgets_Facebook_Init();
        $this->view->oWidgetFacebook = $FacebookWidget->render();
        /* widget do wyswietlania zaisntalowanych modulów */
        $ModulesListWidget = new Widgets_ModuleList_Init();
        $this->view->oWidgetModulesList = $ModulesListWidget->render();
                /* Ustawienia */
        $options = Zend_Registry::get('options');
        $this->options = $options->toArray();
        $this->view->assign('options', $this->options);
        parent::init();
        //inicjalizuje rodzica
    }

    public function indexAction() {
        
    }

    /**
     * Informacje o systemie
     */
    public function aboutAction() {
        
    }

    /**
     * Formularz kontaktowy
     */
    public function contactAction() {
        /* mamy requesta przychodzacego */
        /* Generujemy maila wychodzącego do działu kontaktu */
        $locale = new Zend_Locale();
        
        
        if ($this->_request->isPost()) {
            $this->_helper->layout->disableLayout();
            $this->_helper->viewRenderer->setNoRender();
            $request = $this->getRequest()->getParams();
            //print_r($request);die;
            /* renderowanie do zmienniej kodu */
            $ScriptPath = realpath(dirname(__FILE__));
            $viewMail = new Zend_View();
            $viewMail->setScriptPath($ScriptPath.'/../views/scripts/mail');
            $viewMail->row = $request;
            //echo 'Skrypt: '.pr$viewMail->getScriptPaths();
            //print_r($viewMail->getScriptPaths());
            //$viewMail->request = $request;
            //$message = $viewMail->render('error.phtml');
            //echo $message;
            //print_r($ScriptPath.'../views/scripts/mail');
            $message = $viewMail->render('mail.phtml');
            //print_r($request);
            echo $message;die;
            $mail = new Zend_Mail();
            $mail->setBodyHtml($message);
            $mail->setFrom($this->options['contact']['from']['address'], $this->options['contact']['from']['title']);
            $mail->addTo($this->options['contact']['to']['address'] , $this->options['contact']['to']['title']);
            $mail->setSubject('problem w cms');
            $mail->send();


            echo "Wiadomość została wysłana";
        }
    }
    public function previewAction() {
        $siteWidth = '';
       // echo "działam";die;
    }

}
