<?php

/*
 * Formularz autoryzacji
 */

class NewAdminForm extends Zend_Form {

    public function __construct() {
        parent::__construct();
        $this->setName('NewAdminForm_form');
        $this->setAttrib('enctype', 'multipart/form-data');
        $this->setAttrib('action', null);
        //$this->_decorators->
        # create form elements

        /* dodajemy punkty */
        $User_Name = new Zend_Form_Element_Text('user_name');
        $User_Name->setLabel('Nazwa użytkownika:')
                ->addValidator('regex', false, array('/^[a-z]/'))
                ->setRequired(true)
                ->addFilter('StringToLower');
        /*
         * User
         */
        $User_Email = new Zend_Form_Element_Text('user_email');
        $User_Email->setLabel('Adres email:')
                ->addValidator('regex', false, array('/^[a-z]/'))
                ->setRequired(true)
                ->addFilter('StringToLower');
        /*
         * email
         */
        $Password = new Zend_Form_Element_Password('password');
        $Password->setLabel('Hasło:')
                ->addValidator('regex', false, array('/^[a-z]/'))
                ->setRequired(true)
                ->addFilter('StringToLower');
        /*
         * Stare hasło
         */
        $Repeat_Password = new Zend_Form_Element_Password('repeat_password');
        $Repeat_Password->setLabel('powtórz hasło:')
                ->addValidator('regex', false, array('/^[a-z]/'))
                ->setRequired(true)
                ->addFilter('StringToLower');
        /*
         * Nowe hasło
         */


        /* opis markera */
        $Submit = new Zend_Form_Element_Submit('submit');
        $Submit->setLabel('Dodaj');
        /* button */
        $clear = new Zend_Form_Element_Button('clear');
        $clear->setLabel('Wyczyść');
        $this->addElements(array($User_Name,$User_Email, $Password,$Repeat_Password, $Submit));
    }

}