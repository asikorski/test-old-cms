<?php

/*
 * Formularz autoryzacji
 */

class UserForm extends Zend_Form {

    public function __construct() {
        parent::__construct();
        $this->setName('User_form');
        $this->setAttrib('enctype', 'multipart/form-data');
        $this->setAttrib('action', null);

        $User_name = new Zend_Form_Element_Text('name');
        $User_name->setLabel('Nazwa użytkownika:')
                ->addValidator('regex', false, array('/^[a-z]/'))
                ->setAttrib('class', 'easyui-validatebox')
                ->setAttrib('required', 'true')
                ->setRequired(true)
                ->addFilter('StringToLower');
        /* name */
        $User_email = new Zend_Form_Element_Text('email');
        $User_email->setLabel('Adres email:')
                ->addValidator('regex', false, array('/^[a-z]/'))
                ->setAttrib('class', 'easyui-validatebox')
                ->setAttrib('required', 'false')
                ->setRequired(false)
                ->addFilter('StringToLower');
        /* email */
        $User_firstname = new Zend_Form_Element_Text('first_name');
        $User_firstname->setLabel('Imię:')
                ->addValidator('regex', false, array('/^[a-z]/'))
                ->setAttrib('class', 'easyui-validatebox')
                ->setAttrib('required', 'false')
                ->setRequired(false)
                ->addFilter('StringToLower');
        /* firstname */
        $User_lastname = new Zend_Form_Element_Text('last_name');
        $User_lastname->setLabel('Nazwisko:')
                ->addValidator('regex', false, array('/^[a-z]/'))
                ->setAttrib('class', 'easyui-validatebox')
                ->setAttrib('required', 'false')
                ->setRequired(false)
                ->addFilter('StringToLower');
        /* lastname */
        $User_desc = new Zend_Form_Element_Textarea('desc');
        $User_desc->setLabel('Opis:')
                ->addValidator('regex', false, array('/^[a-z]/'))
                ->setAttrib('class', 'easyui-validatebox')
                ->setAttrib('required', 'false')
                ->setRequired(false)
                ->addFilter('StringToLower');
        /* lastname */
        $User_lang = new Zend_Form_Element_Text('lang');
        $User_lang->setLabel('Język:')
                ->addValidator('regex', false, array('/^[a-z]/'))
                ->setAttrib('class', 'easyui-validatebox')
                ->setAttrib('required', 'false')
                ->setRequired(false)
                ->addFilter('StringToLower');
        /* lastname */

        $this->addElements(array($User_name, $User_email, $User_firstname,$User_lastname,$User_desc,$User_lang));
    }

}