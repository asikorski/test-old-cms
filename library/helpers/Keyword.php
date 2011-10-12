<?php
/**
 * Helper do generowania słów kluczowych dla linku
 * 
 * @author unknown
 * 
 * @todo chyba można by z tego helepra zrobić bardziej uniwersalne narzędie
 * @todo Arni; wydaje mi sie ze mozna podpiąc jakis zewnetrzny plik z któþrego bedzie pobierany kywords
 * 
 */
class Global_View_Helper_Keyword extends Zend_View_Helper_Abstract {


    /**
     * Generuje słowo kluczowe
     *
     * @param string $e7_lang wersja językowa
     * @return string słowo kluczowe
     */
    public function keyword($lang = 'pl')
    {
        $keywords = array(
                        'pl' => array(
                                'Tworzenie stron www',
                                'Tworzenie stron warszawa',
                                'Tworzenie stron',
                                'Strony www',
                                'Projektowanie stron www',
                                'Projektowanie stron'
                                ),
                        'en' => array(
                                'Web Design', 
                                'Web Design London', 
                                'Web Design', 
                                'Website Design', 
                                'Web Development', 
                                'Website Development',  
                                'Web Design UK', 
                                'IT Solutions', 
                                'Interactive Agency'),
                        'sk' => array(
                                'Web Design', 
                                'Web Design London', 
                                'Web Design', 
                                'Website Design', 
                                'Web Development', 
                                'Website Development',  
                                'Web Design', 
                                'IT Solutions', 
                                'Interactive Agency'),
                        'de' => array(
                                'Schaffung von Seiten',
                                'Schaffung von Seiten, Warschau',	
                                'Website-Design'
                                )
        );
     
 		$file = $_SERVER['REQUEST_URI'];
 		$sum  = 0;
 		for($i=0; $i<strlen($file); $i++)
  		$sum += ord($file[$i]);
 		return $keywords[$lang][$sum%count($keywords[$lang])];
    }

}
