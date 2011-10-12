<?php

/*
 * Formularz autoryzacji
 */

class AuthForm extends Zend_Form {

    public function __construct() {
        parent::__construct();
        $this->setName('auth_form');
        $this->setAttrib('enctype', 'multipart/form-data');
        $this->setAttrib('action', null);
        //$this->_decorators->
        # create form elements

        /* dodajemy punkty */
        $User = new Zend_Form_Element_Text('username');
        $User->setValue('login')
                ->addValidator('NotEmpty', true, array('messages' => 'Pole nie może być puste!'))
                ->setAttrib('title', 'Podaj swój login')
                ->setAttrib('class', 'easyui-validatebox')
                ->setAttrib('required', 'true')
                ->setAttrib('size', '255')
                ->setLabel('login')
                ->setRequired(true)
                ->addFilter('StringToLower');
        $Password = new Zend_Form_Element_Password('password');
        $Password->setValue('haslo')
                ->setAttrib('title', 'Podaj swoje hasło dostępu')
               // ->setAttrib('size', '255')
                ->setLabel('hasło')
                ->setAttrib('class', 'easyui-validatebox')
                ->setAttrib('required', 'true')
                ->setAttrib('validType', 'minLength[7]')

                /* ->setAttrib('size', '255') */
                ->addValidator('NotEmpty', true, array('messages' => 'Pole nie może być puste!'))
                ->setRequired(true);
        /* button */
        $this->addElements(array($User, $Password));
    }

}