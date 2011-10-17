<?php

/*
 * Formularz autoryzacji
 */

class user_Forms_Login extends Zend_Form {

    public function __construct() {
        parent::__construct();
        $this->setName('login_form');
        //$this->setAttrib('enctype', 'text/plain');
        //$this->setAttrib('action', '/article');
        //$this->setAttrib('method', 'POST');
        //$this->_decorators->
        # create form elements
        $UserJsValidate = 'validate[required,maxSize[65]]';
        $userName = new Zend_Form_Element_Text('username');
        $userName->setValue('')
                ->addValidator('NotEmpty', true, array('messages' => 'Pole nie może być puste!'))
                ->setAttrib('title', 'Wpisz swój login (email)')
                ->setAttrib('class', $UserJsValidate.' nameInput')
                ->setAttrib('required', 'true')
                ->setAttrib('size', '100')
                ->setLabel('Nazwa usera')
                ->setRequired(true)
                ->addFilter('StringToLower')
                ->removeDecorator('HtmlTag');

        
        $passwordJsValidate = 'validate[required,maxSize[65]]';
        $password = new Zend_Form_Element_Password('password');
        $password->setValue('')
                ->addValidator('NotEmpty', true, array('messages' => 'Pole nie może być puste!'))
                ->setAttrib('title', 'Podaj swoje hasło')
                ->setAttrib('class', $UserJsValidate.' nameInput')
                ->setAttrib('required', 'true')
                ->setAttrib('size', '100')
                ->setLabel('Hasło')
                ->setRequired(true)
                ->addFilter('StringToLower')
                ->removeDecorator('HtmlTag');

        /* button */
        $Submit = new Zend_Form_Element_Submit('submit');
        $Submit->setLabel('Zaloguj');

        
        
        $this->addElements(array($userName, $password, $Submit));
    }

    
}