<?php

/*
 * Formularz autoryzacji
 */

class ChangePasswordForm extends Zend_Form {

    public function __construct() {
        parent::__construct();
        $this->setName('ChangePassword_form');
        $this->setAttrib('enctype', 'multipart/form-data');
        $this->setAttrib('action', null);
        //$this->_decorators->
        # create form elements

        /* dodajemy punkty */
        $Old_Password = new Zend_Form_Element_Password('old_password');
        $Old_Password->setLabel('Stare hasło:')
                ->addValidator('regex', false, array('/^[a-z]/'))
                ->setAttrib('class', 'easyui-validatebox')
                ->setAttrib('required', 'true')
                ->setRequired(true)
                ->addFilter('StringToLower');
        /*
         * Stare hasło
         */
        $New_Password = new Zend_Form_Element_Password('new_password');
        $New_Password->setLabel('Nowe hasło:')
                ->addValidator('regex', false, array('/^[a-z]/'))
                ->setAttrib('class', 'easyui-validatebox')
                ->setAttrib('required', 'true')
                ->setRequired(true)
                ->addFilter('StringToLower');

        /*
         * Nowe hasło
         */
        $New_Password_Repeat = new Zend_Form_Element_Password('new_password_repeat');
        $New_Password_Repeat->setLabel('Powtorz nowe hasło')
                ->addValidator('regex', false, array('/^[a-z]/'))
                ->setAttrib('class', 'easyui-validatebox')
                ->setAttrib('required', 'true')
                ->setRequired(true)
                ->addFilter('StringToLower');

        $this->addElements(array( $Old_Password, $New_Password,$New_Password_Repeat));
    }

}