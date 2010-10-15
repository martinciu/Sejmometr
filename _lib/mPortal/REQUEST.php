<?
  require_once('config.php');
  require_once('general_functions.php');
  require_once('db.php');
  
  class REQUEST {
   
   var $DB;
   var $USER, $LOGIN;
   var $ACCESS = array();
   var $DONT_VERIFY_USER = false; // to jest potrzebne dla użytkownika crona
   
   function REQUEST($params = null){
     // Konfigurujemy obsługę sesji i podstawowe zmienne
     ini_set('session.gc_maxlifetime', REGULAR_SESSION_MAXLIFE);
     
     if( $params['DONT_VERIFY_USER'] ) $this->DONT_VERIFY_USER = true;
     
     add_include_path( ROOT."/_lib" );
     add_include_path( ROOT."/_lib/Zend" );
  
     require_once ROOT."/_lib/Zend/Session.php";
     Zend_Session::start();
     
     
     
     $this->USER = &$_SESSION['_mPortal']['USER'];
     if( !empty($params['ACCESS']) ) { $this->ACCESS = $params['ACCESS']; }
     $this->DB = new DB();
          
     // Reakcja na logout
     $doLogout = (boolean) $_REQUEST['logout'];
     if( $doLogout ) {
       $this->logout();
       $location = empty($_REQUEST['next']) ? $_SERVER['SCRIPT_URI'] : $_REQUEST['next'];
       header("Location: $location");
     } else {
     
	     // Reakcja na login
	     if( !empty($_REQUEST['login']) && !empty($_REQUEST['pass']) ) {
	       $doremember = (boolean) $_REQUEST['remember'];
	       $this->LOGIN = $this->login($_REQUEST['login'], md5($_REQUEST['login'].$_REQUEST['pass']), $doremember);
	     } elseif( $this->isUserValid() ) {	      
	       // Jeżeli użytkownik jest zalogowany, to okresowo sprawdzamy czy nie zmieniło się hasło.
	       if( !$this->DONT_VERIFY_USER && $this->isUserLogged() && (time()-$this->USER['last_login_verify'] > VERIFY_PASSWORD_FREQUENCY) ){	        
	         if( $this->DB->selectCountBoolean("SELECT COUNT(*) FROM `".DB_TABLE_users."` WHERE `login`='".$this->USER['login']."' AND `pass`='".$this->USER['md5pass']."'") ) {
	           $this->USER['last_login_verify'] = time();
	         } else {
	           $this->loginExpiredAccessMessage();
	         }
	       }
	     } else {
	       // Tworzymy nowy, anonimowy profil.
	       $this->createVisitor();
	     }
     
     }
   }
   
   function setAccess($params){
     // Interpretujemy parametry
     if( is_array($params) ) { $access = $params; }
     else { $access = array($params); }
     
     if( is_array($access) ) {
       $this->ACCESS = $access;
              
       // Autoryzacja dostępu
	     if( !empty($this->ACCESS) && !in_array($this->USER['group'], $this->ACCESS) ) {
	       // Żądanie nieuprawnione
	       $this->unauthorizedAccessMessage();
	     }
     }
   }
   
   function createVisitor(){
     $this->USER = array();
   }
   
   function isUserValid(){
     return is_array($this->USER);
   }
   
   function isUserLogged(){
     return !empty($this->USER['group']);
   }
   
   function getUserGroup(){
     return $this->USER['group'];
   }
   
   function unauthorizedAccessMessage(){
     header('HTTP/1.1 403 Forbidden');
     echo 'You don\'t have a permission to access this file';
     die();
   }
   
   function loginExpiredAccessMessage(){
     echo 'Please login again';
     die();
   }
   
   function login($login, $md5pass, $remember){
     $data = $this->DB->selectRow("SELECT `group`, `last_login_date` FROM `".DB_TABLE_users."` WHERE (`login`='$login' AND `pass`='$md5pass')");
     if( empty($data) ) {
       $this->removecookie();
       return false;
     } else {
       $sessionid = session_id();
       $this->USER = array(
         'login' => $login,
         'group' => $data[0],
         'last_login_date' => $data[1],
         'last_login_verify' => time(),
         'md5pass' => $md5pass,
       );
              
       if( $remember ) {
         $this->DB->q("UPDATE `".DB_TABLE_users."` SET `last_login_date`=NOW(), `last_session_id`='$sessionid' WHERE `login`='$login'");
         ini_set('session.gc_maxlifetime', REMEMBER_ME_SESSION_MAXLIFE);
         setcookie('PHPSESSID', $sessionid, time()+REMEMBER_ME_SESSION_MAXLIFE); 
       } else {
         $this->DB->q("UPDATE `".DB_TABLE_users."` SET `last_login_date`=NOW(), `last_session_id`='' WHERE `login`='$login'");
       }
       return true;
     }
   }
   
   function logout(){
     $this->DB->q("UPDATE `".DB_TABLE_users."` SET `last_session_id`='' WHERE `login`='".$this->USER['login']."'");
     $this->createVisitor();
     $this->removecookie();
     session_destroy();     
   }
   
   function removecookie(){
     setcookie('PHPSESSID', '');
   }
   
   function setVar($key, $value){
     if(!empty($key)) { return $this->DB->q("INSERT INTO `".DB_TABLE_vars."` (`key`, `value`) VALUES ('$key', '$value') ON DUPLICATE KEY UPDATE `value`='$value'"); }
   }

   function getVar($key){
     if(!empty($key)) {
       return $this->DB->selectValue("SELECT value FROM `".DB_TABLE_vars."` WHERE `key`='$key' LIMIT 1");
     }
   }
   
   function service($s, $_PARAMS=null){
     $q = "SELECT page FROM `".DB_TABLE_services."` WHERE id='$s' AND (`page`='' OR `page`='".$this->ID."') ORDER BY page DESC LIMIT 1";
     $page = $this->DB->selectValue($q);
     
     if( is_null($page) ) return false;
     
     $groups = $this->DB->selectValues("SELECT `group` FROM ".DB_TABLE_services_access." WHERE `service`='$s' AND `page`='$page'");
     if( empty($groups) || in_array($this->USER['group'], $groups) ) {
	     $file = $page==='' ? ROOT.'/_services/'.$s.'.php' : ROOT.'/_pages/'.$this->ID.'/_services/'.$s.'.php';
       
       if( $this->USER['group']==2 ) {
		     return include $file;
		   } else {
		     return @include $file;
		   }
       
     } else return false;
     
   }
   
   function S($s, $_PARAMS=null){
     return $this->service($s, $_PARAMS);
   }
 }
?>