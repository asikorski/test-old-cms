<?php

/*
 * Formularz autoryzacji
 */

class articles_AddArticleForm extends Zend_Form {

    public function __construct() {
        parent::__construct();
        $this->setName('article_form');
        //$this->setAttrib('enctype', 'text/plain');
        //$this->setAttrib('action', '/article');
        //$this->setAttrib('method', 'POST');
        //$this->_decorators->
        # create form elements
        $Category = new Zend_Form_Element_Select('category');
        $Category->setLabel('Kategoria artykułu')
              ->setMultiOptions($this->getCategory())
              ->setRequired(true)->addValidator('NotEmpty', true);
        
        /* dodajemy punkty */
        $TitleJsValidate = 'validate[required,maxSize[65]]';
        $Title = new Zend_Form_Element_Text('name');
        $Title->setValue('')
                ->addValidator('NotEmpty', true, array('messages' => 'Pole nie może być puste!'))
                ->setAttrib('title', 'Podaj tytuł artykułu')
                ->setAttrib('class', $TitleJsValidate.' nameInput')
                ->setAttrib('required', 'true')
                //->setAttrib('size', '255')
                ->setLabel('Tytuł')
                ->setRequired(true)
                ->addFilter('StringToLower')
                ->removeDecorator('HtmlTag');

        
        $LeadJsValidate = 'validate[required,maxSize[300]]';
        $Lead = new Zend_Form_Element_Textarea('lead');
        $Lead->setValue('')
                ->addValidator('NotEmpty', true, array('messages' => 'Pole nie może być puste!'))
                ->setAttrib('title', 'Podaj lead artykułu')
                ->setAttrib('class', $LeadJsValidate.' leadInput')
                ->setAttrib('required', 'true')
                ->setLabel('Lead')
                ->setRequired(true)
                ->addFilter('StringToLower')
                ->removeDecorator('HtmlTag');
        
        $TextJsValidate = 'validate[required,minSize[1800]]';
        $Text = new Zend_Form_Element_Textarea('text');
        $Text->setValue('')
                ->addValidator('NotEmpty', true, array('messages' => 'Pole nie może być puste!'))
                ->setAttrib('title', 'Podaj treść artykułu')
                ->setAttrib('class', $TextJsValidate)
                ->setAttrib('required', 'true')
                //->setAttrib('size', '255')
                ->setLabel('Treść artykułu')
                ->setRequired(true)
                ->addFilter('StringToLower')
                ->removeDecorator('HtmlTag');
        /* button */
        $Submit = new Zend_Form_Element_Submit('submit');
        $Submit->setLabel('Dodaj artykuł');

        
        
        $this->addElements(array($Category, $Title, $Lead, $Text, $Submit));
    }
    
    public function getCategory(){
        $model = new CMS_Connection(array('module' => 'sites',
                    'lang' => 'pl',
                    'baseURL' => 'gazeta.localhost'));
        $categoryArray = $model->getRootChild(2);
        foreach($categoryArray as $item){
            $category[$item['id']] = $item['name'];
        }
        return $category;
    }
    
}