<?
require_once( $_SERVER['DOCUMENT_ROOT'].'/_lib/mPortal/REQUEST.php' );

class PATTERN extends REQUEST {
	var $SMARTY, $IE6;
	
	function PATTERN($params=null){
	  if( $params['ID'] ) $this->ID = $params['ID'];
	  
		parent::REQUEST();
		$this->IE6 = (boolean) stripos($_SERVER['HTTP_USER_AGENT'], 'MSIE 6');
						
		// Inicjujemy SMARTY
		require_once(ROOT.'/_lib/smarty/Smarty.class.php');
		$this->SMARTY = new Smarty();
		//$this->smarty->error_reporting = false;
						
		// Rejestrujemy domyślne moduły SMARTY
		$smarties = $this->DB->selectValues("SELECT ".DB_TABLE_components_defaults.".id FROM `".DB_TABLE_components_defaults."` LEFT JOIN `".DB_TABLE_components."` ON ".DB_TABLE_components_defaults.".id=".DB_TABLE_components.".id WHERE ".DB_TABLE_components.".smarty='1'");
		if( is_array($smarties) ) foreach($smarties as $smarty_module) {
		  $this->register_smarty_module($smarty_module);
		}

		// Rejestrujemy moduły SMARTY dla strony
		if( $this->ID ) {
			$smarties = $this->DB->selectValues("SELECT component FROM ".DB_TABLE_pages_components." WHERE page='".$this->ID."'");
			if( is_array($smarties) ) foreach($smarties as $smarty_module) {
			  $this->register_smarty_module($smarty_module);
			}
		}
	}
		
	function render_pattern($pattern, $_PARAMS=null){				
		$gfile = ROOT.'/_patterns/'.$pattern;
		$pfile = ROOT.'/_pages/'.$this->ID.'/_patterns/'.$pattern;
		
		if( !empty($this->ID) && file_exists($pfile) ){
		  $folder = $pfile;
		} else {
		  if( file_exists($gfile) ) { $folder = $gfile; }
		}
				
		$pattern_name = filename($pattern);
		
		$templateFile = $folder.'/'.$pattern.'.tpl';
		$logicFile = $folder.'/'.$pattern.'.php';
		
		$folder = empty($this->ID) ? ROOT.'/_patterns/_SMARTY/' : ROOT.'/_pages/'.$this->ID.'/';
				
		if( !file_exists($folder) ) { return false; }
		
		$this->SMARTY->template_dir = $folder;
		$this->SMARTY->compile_dir  = $folder.'_templates_c/';
		$this->SMARTY->config_dir   = $folder.'_configs/';
		$this->SMARTY->cache_dir    = $folder.'_cache/';
		
		$logicFileExists = file_exists($logicFile);
		$templateFileExists = file_exists($templateFile);
		
		if( !$logicFileExists && !$templateFileExists ) { return false; }
		
		if( $logicFileExists ) {
		 $data = include($logicFile);
		 $this->SMARTY->assign('DATA', $data);
		}
				
		if( $templateFileExists ){
			$output = $this->SMARTY->fetch($templateFile);
			 
			 // Minify and display final html output
		  if( $_REQUEST['MINIFY_OUTPUT']!=='0' ){ $output = minify_html($output); }
		  echo $output;		 
		}
	}
	
	function register_smarty_module($module){
	  @include_once( ROOT.'/_components/'.$module.'/'.$module.'.php' );
	  
	  if( function_exists('sf_'.$module) ) {
	    // Rejestrujmey funkcję SMARTY
	    $this->SMARTY->register_function($module, 'sf_'.$module);
	  }
	  
	  if( function_exists('sm_'.$module) ) {
	    // Rejestrujemy modyfikator SMARTY
	    $this->SMARTY->register_modifier($module, 'sm_'.$module);
	  }
	}
	
	function assignService($varname, $service=null, $params=null){
	  $data = $this->service($service ? $service : $varname, $params);
	  $this->SMARTY->assign($varname, $data);
	  return $data;
	}
	
	function assignPattern($pattern_name, $pattern=null, $params=null){
	  $html = $this->pattern($pattern ? $pattern : $pattern_name, $params);
	  $this->SMARTY->assign($pattern_name, $html);
	  return $data;
	}
	
	function assignVar($varname, $var=null){
	  $data = $this->getVar($var ? $var : $varname);
	  $this->SMARTY->assign($varname, $data);
	  return $data;
	}
}
?>