<?php
	/*Zadaniem klasy jest połaczenie z api facebooka*/
    require_once 'api/facebook.php';

	class FacebookConnection{

	    public $FacebookSettings;
	    public $facebook;
	    public $session;
	    public $isConnect=false;
	    public $user=null;
	    public $settings=null;
	    public $me;
	    public $accesstoken;
#-----------------------------------------------------------------------------
		public function __construct($settings){

		        $this->settings=$settings;

		            //var_dump($this->notAuthorize);

		        //var_dump($this->notAuthorize);
		      #pobieram ustawienia
		      //Zend_Loader::loadClass('Facebook',APPLICATION_PATH . '/models');

		}
	public function Connect(){

	    $this -> facebook = new Facebook(array(
  				    'appId'  => $this->settings->facebook->AplicationId,
 				    'secret' => $this->settings->facebook->secret,
 					'cookie' => false,
		      ));
		      #połączenie z api


		      $this ->session = $this ->facebook->getSession();
		      //echo $this -> facebook->require_login();
		      //print_r($_REQUEST['session']);
		      #pobieram sesje
		      //var_dump($this -> facebook);
//var_dump($this ->facebook->api('/me'));
//die();

		if ($this ->session) {
				#sesja nie moze zostan aktywowana
		        $this->isConnect=TRUE;
		        $this ->me = $this ->facebook->api('/me');
				$this->accesstoken = $this->session['access_token'];
		        return true;

			}else{
				$this->isConnect=FALSE;

				return false;


			}
			//echo $this->getRequest()->signed_request;
	}
	#-----------------------------------------------------------------------------
	public function NewLoginURL(){

		#nowa metoda generowania url logowania za pomoca ograph
		    $auth_url = "http://www.facebook.com/dialog/oauth?client_id=" .$this->settings->facebook->AplicationId.
						"&redirect_uri=" . urlencode($this->settings->facebook->callbackuri.$_SERVER["REQUEST_URI"]).
						"&display=".$this->settings->facebook->logindisplay.
						"&scope=".$this->settings->facebook->permissions;
			#link autoryzacyjny
			$signed_request = $_REQUEST["signed_request"];

			list($encoded_sig, $payload) = explode('.', $signed_request, 2);
			$data = json_decode(base64_decode(strtr($payload, '-_', '+/')), true);

			 //print_r($auth_url);
			 return $auth_url;

	}
	#--------------------------------------------------------------------------------------------
		public function LoginUri(){
		//generuje link do przekierowania


			$loginurl = $this -> facebook->getLoginUrl(
               					 array(
               							'canvas'    => 1,
                						'fbconnect' => 0,
                						'req_perms' => $this->settings->facebook->permissions,
     									'next' => $this->settings->facebook->callbackuri.$_SERVER["REQUEST_URI"],
									'cancel_url'=>$this->settings->facebook->cancel
										 )
										 // przekierwuje mnie na podany link
   								  );

			return $loginurl;

	}
	#--------------------------------------------------------------------------------------------
	public function ParseSignedRequest($signed_request) {
	  list($encoded_sig, $payload) = explode('.', $signed_request, 2);

	  // decode the data
	  $sig = $this->base64_url_decode($encoded_sig);
	  $data = json_decode($this->base64_url_decode($payload), true);

	  if (strtoupper($data['algorithm']) !== 'HMAC-SHA256') {
		return null;
	  }

	  // check sig
	  $expected_sig = hash_hmac('sha256', $payload, $this->settings->facebook->secret, $raw = true);
	  if ($sig !== $expected_sig) {
		return null;
	  }

	  return $data;
	}
	#--------------------------------------------------------------------------------------------
	#Pobieram ustawienia
	private function base64_url_decode($input) {
		  return base64_decode(strtr($input, '-_', '+/'));
		}
	#--------------------------------------------------------------------------------------------
	#generowanie przekierowania
	public function RedirectJavaScritp($uri){
	    return "<script type='text/javascript'>top.location.href = '".$uri."';</script>";
	}
	public function RedirectJavaScriptNormal($uri){
	    return "<script type='text/javascript'>window.location= '".$uri."';</script>";
	}
	#--------------------------------------------------------------------------------------------
	public function GetUserInfo(){
	    #pobiera dane usera


	    //$this ->user
	}
	#---------------------------------------------------------------------------------------------
	public function Publish($user_id,$dane){
		try{
		$this->facebook->api('/'.$user_id.'/feed', 'post', array(
   								'message' => $dane->message,
    							'name' => $dane->name,
    							'caption' => $dane->caption,
    							'picture' => $dane->picture,
    							'link' => $dane->link  ));
							//posted

							return true;
		}catch(FacebookApiException $e){
			return false;
		}
	}
	#-----------------------------------------------------------------------------------------------
	public function getFriends(){

	    try{
		return $this->facebook->api('me/friends');
							//posted


		}catch(FacebookApiException $e){
			return false;
		}

	}
	#-----------------------------------------------------------------------------------------------
	public function getLikes($urlquery){
	    #zwraca liki usera
	    try{
		return $this->facebook->api('/me/likes?'.$urlquery);
							//posted

		}catch(FacebookApiException $e){
			return false;
		}
	}
	#-----------------------------------------------------------------------------------------------
	public function getBooks(){
	    #zwraca liki usera
	    try{
		return $this->facebook->api('/me/books');
							//posted

		}catch(FacebookApiException $e){
			return false;
		}
	}
	#-----------------------------------------------------------------------------------------------
	public function getMusic(){
	    #zwraca liki usera
	    try{
		return $this->facebook->api('/me/music');
							//posted

		}catch(FacebookApiException $e){
			return false;
		}
	}
	#-----------------------------------------------------------------------------------------------
	public function getAlbums(){
	    #zwraca liki usera
	    try{
		return $this->facebook->api('/me/albums');
							//posted

		}catch(FacebookApiException $e){
			return false;
		}
	}
	#-----------------------------------------------------------------------------------------------
	public function getMovies(){
	    #zwraca liki usera
	    try{
		return $this->facebook->api('/me/movies');
							//posted

		}catch(FacebookApiException $e){
			return false;
		}
	}
	#-------------------------------------------------------------------------------------------------
	public function isAuthorize(){
	    $str = substr($_SERVER['REQUEST_URI'], 1);
           list($uri) = explode("/", $str);
	    #funcja sprawdza czy nalezy zautoryzować adres
	    if(!empty($uri)){
	    if(!empty($this->settings->facebook->notauthorize)){
	        $oAuthorize =explode("?",$this->settings->facebook->notauthorize);
	        $i = count($oAuthorize);
	        for( $x = 0; $x < $i; $x++ ){
	            if ($oAuthorize[$x]==$uri) return false;
	            }
	            return true;
	    }else{
	        return true;
	    }
	    }else{
	        return true;
	    }
	}
	###########################################################################
	# Nowa metoda publikowania na wallu
	###########################################################################
	/*
	 * Dodano obsługe publikowania na tablicy innego usera
	 * możliwość personalizowania publikowania, obiektowość
	 */
	public function StreamPublish($uid,$dane){
	    try{
		$this->facebook->api('/'.$uid.'/feed', 'post', array(
   							'message' => $dane->message,
    							'name' => $dane->name,
    							'caption' => $dane->caption,
    							'picture' => $dane->picture,
    							'link' => $dane->link  ));
							//posted

							return true;
		}catch(FacebookApiException $e){
			return false;
		}
	}
	public function isLikePage($uid){
	    $api_call = array(
			'method' => 'pages.isFan',
			'uid' => $uid,
			'page_id' => $this->settings->facebook->pageid,
			'access_token'=> $this->accesstoken
				);


		return $this -> facebook->api($api_call); ;
		
	}
	public function getCookie(){

	  $args = array();
	  parse_str(trim($_COOKIE['fbs_171398826245983'], '\\"'), $args);
	  ksort($args);
	  $payload = '';
	  foreach ($args as $key => $value) {
	    if ($key != 'sig') {
	      $payload .= $key . '=' . $value;
	    }
	  }
	  if (md5($payload . 'd396c59618f7819c9dea72c36e7227fa') != $args['sig']) {
	    return null;
	  }
	  return $args;
	}

	

	}









