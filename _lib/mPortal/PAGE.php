<?
require_once( $_SERVER['DOCUMENT_ROOT'].'/_lib/mPortal/PATTERN.php' );

class PAGE extends PATTERN {
	var $ID, $NAME, $TITLE, $LOGIN, $FULLSCREEN, $LAYOUT, $FRONT_MENU, $FRONT_SUBMENUS, $FRONT_MENU_SELECTED;
	var $META = array();
  var $JSSLIBS = array();
  var $CSSLIBS = array();
	var $LIGHTBOXES = array();
	var $COMPONENTS = array();
	var $MENU = array();
	var $MENUGROUPS = array();
	var $MENUGROUPSIDS = array();
	var $STAMPS = array();
	var $LINKS = array();
  
  var $LIBRARIES = array();
  
  var $mainmenu = array(
    array('url'=>'', 'label'=>''),
  );
  
  var $TEMPMENU;
  
  function PAGE($page){
	  $this->ID = $page;
    parent::PATTERN();
        
    $pagedata = $this->DB->selectRow("SELECT title, fullscreen, layout, js_stamp, css_stamp FROM ".DB_TABLE_pages." WHERE id='".$page."'");
	  if( empty($pagedata) ) {
	    $this->ID = ERROR_PAGE;
	    $pagedata = $this->DB->selectRow("SELECT title, fullscreen, layout, js_stamp, css_stamp FROM ".DB_TABLE_pages." WHERE id='".ERROR_PAGE."'");
	  } 
	  	  
	  list($title, $fullscreen, $layout, $js_stamp, $css_stamp) = $pagedata;
	  
  	$this->TEMPMENU = $this->DB->selectRows("SELECT id, tytul FROM temp_menu ORDER BY ord ASC");
  	foreach( $this->TEMPMENU as $item ) if( $item[0]==$_GET['_TYPE'] ) $title = $item[1];
  	
  	// Ustawiamy podstawowe właściwości
		$pathparts = pathinfo($this->ID);
		
	  $this->TITLE = $title;
	  $this->LAYOUT = $layout ? $layout : DEFAULT_PAGE_LAYOUT;
	  $this->FULLSCREEN = ($fullscreen=='1');
	  $this->STAMPS = array(
	    'js' => $js_stamp,
	    'css' => $css_stamp,
	  );
	  
	  $stamps = $this->DB->selectAssocs("SELECT file, ext, stamp FROM ".DB_TABLE_files_stamps);
	  foreach( $stamps as $s ) $this->STAMPS[ $s['file'].'_'.$s['ext'] ] = $s['stamp'];
	  
		$this->NAME = $pathparts['filename'];
	  $this->META = $this->DB->selectRows("SELECT name, content FROM ".DB_TABLE_meta."");
    

    // PAGE LIBS
    $libs = $this->DB->selectValues("SELECT lib FROM ".DB_TABLE_pages_libs." WHERE page='".$this->ID."'");
    foreach( $libs as $lib ) $this->addLib($lib);    
    
		$file = ROOT.'/_lib/mPortal/PAGE-addon.php';
		if( file_exists($file) ) include $file;
  }
  
  function set_meta($name, $content) {
    foreach( $this->META as &$m ) {
      if( $m[0]==$name ) return $m[1] = $content;
    }
    $this->META[ $name ] = $content;
    return true;
  }
  
	function render_page(){	  
	   
	  
	  // $groups = $this->DB->selectRow("SELECT `group` FROM ".DB_TABLE_pages_access." WHERE page='".$page."'");
		
		$this->SMARTY->template_dir = ROOT.'/_pages/'.$this->ID.'/';
		$this->SMARTY->compile_dir  = ROOT.'/_pages/'.$this->ID.'/_templates_c/';
		$this->SMARTY->config_dir   = ROOT.'/_pages/'.$this->ID.'/_configs/';
		$this->SMARTY->cache_dir    = ROOT.'/_pages/'.$this->ID.'/_cache/';
		
		$logic_file = ROOT.'/_pages/'.$this->ID.'/'.$this->NAME.'.php';
		if( file_exists($logic_file) ) include($logic_file);
		
		// Przygotowujemy menu
		$q = "SELECT id, name, class FROM ".DB_TABLE_menu_groups." WHERE `access`='' ";
		if( $this->isUserLogged() ) {
		  $q .= "OR `access`='".$this->getUserGroup()."' ";
		}
		$q .= "ORDER BY ord";
		$this->MENUGROUPS = $this->DB->selectPairs($q);
		
		$this->MENUGROUPSIDS = array_keys( $this->MENUGROUPS );
		
		$data = $this->DB->selectAssocs("SELECT `group`, parent, id, label FROM ".DB_TABLE_menu." WHERE (`group`='".implode("' OR `group`='", $this->MENUGROUPSIDS)."') ORDER BY `group` ASC, parent ASC, ord ASC");
		$this->SUBMENUS_TABLE = array();
		if( is_array($data) ) foreach($data as $item) {
		  if( $item['parent']=='' ) {
		    $this->MENU[$item['group']][] = array( 'id'=>$item['id'], 'label'=>$item['label'] );
		  } else {
		    $key = assoc_search_key( $this->MENU[$item['group']], 'id', $item['parent'] );
		    $this->SUBMENUS_TABLE[$item['id']] = $this->MENU[$item['group']][$key]['id'];
		    $this->MENU[$item['group']][$key]['SUBMENU'][] = array( 'id'=>$item['id'], 'label'=>$item['label'] );
		  }		  
		}
		
		  
	  $front_menu_items = $this->MENU[7];
	  $this->FRONT_MENU = array();
	  $this->FRONT_SUBMENUS = array();
	  foreach( $front_menu_items as &$menu_item ) {
	    
	    $selected = false;
	    if( is_array($menu_item['SUBMENU']) ) {		      
	      foreach( $menu_item['SUBMENU'] as &$item ) if( ($item['id']==$_GET['_PAGE']) || ($_GET['_TYPE']!='' && $item['id']==$_GET['_TYPE']) || $item['id']==$_GET['_SUB_MENU_SELECTED'] ) {
	        $item['selected'] = true;
	        $selected = true;
	        break;
	      }
	      
	      $this->FRONT_SUBMENUS[] = array(
	        'id' => $menu_item['id'],
	        'menu' => $menu_item['SUBMENU'],
	        'show' => $selected,
	      );
	      
	      unset( $menu_item['SUBMENU'] );
	    }
	    
	    
	    if( $selected || $menu_item['id']==$_GET['_PAGE'] || $menu_item['id']==$_GET['_FRONT_MENU_SELECTED'] ) {
	      $menu_item['selected'] = true;
	      $this->FRONT_MENU_SELECTED = $menu_item['id'];
	    }
	    $this->FRONT_MENU[] = $menu_item;
	  }
		  
		
	  // Przygotowujemy sekcję META
	  $smarty_params = array(
	    'ROOT' => ROOT,
	    'MENUGROUPS' => $this->MENUGROUPS,
	    'MENUGROUPSIDS' => $this->MENUGROUPSIDS,
	    'MENU' => $this->MENU,
	    'TEMPMENU' => $this->TEMPMENU,
	    'ID' => $this->ID,
	    'LOGIN' => $this->LOGIN,
	    'NAME' => $this->NAME,
	    'TITLE' => $this->TITLE,
	    'META' => $this->META,
	    'JSLIBS' => $this->JSLIBS,
	    'CSSLIBS' => $this->CSSLIBS,
	    'LIGHTBOXES' => $this->LIGHTBOXES,
	    'LAYOUT' => $this->LAYOUT,
	    'jsInline' => file_exists(ROOT.'/_pages/'.$this->ID.'/'.$this->NAME.'-inline.js'),
	    'cssInline' => file_exists(ROOT.'/_pages/'.$this->ID.'/'.$this->NAME.'-inline.css'),
	    'jsFile' => file_exists(ROOT.'/js/'.$this->ID.'-'.$this->STAMPS['js'].'.js'),
	    'cssFile' => file_exists(ROOT.'/css/'.$this->ID.'-'.$this->STAMPS['css'].'.css'),
	    'isUserLogged' => $this->isUserLogged(),
	    'USER' => $this->USER,
	    'SUBMENUS_TABLE' => $this->SUBMENUS_TABLE,
	    'FULLSCREEN' => $this->FULLSCREEN,
	    'DEFAULT_PAGE_TITLE' => DEFAULT_PAGE_TITLE,
	    'STAMPS' => $this->STAMPS,
	    'FRONT_MENU' => $this->FRONT_MENU,
	    'FRONT_SUBMENUS' => $this->FRONT_SUBMENUS,
	    'FRONT_MENU_SELECTED' => $this->FRONT_MENU_SELECTED,
	    'LINKS' => $this->LINKS,
	  );
	  
	  
	  $this->SMARTY->assign('M', $smarty_params);
	  $menu_html = $this->SMARTY->fetch( ROOT.'/_lib/mPortal/resources/menu.tpl' );
	  // $submenus_html = $this->SMARTY->fetch( ROOT.'/lib/mPortal/resources/layout/mPortal-sub-menus.tpl' );
	  
	  $template_file = ROOT.'/_pages/'.$this->ID.'/'.$this->NAME.'.tpl';
	  if( file_exists($template_file) ) {$page_html = $this->SMARTY->fetch($template_file); }
	  
	  $smarty_params = array_merge($smarty_params, array(
	    'MENU_HTML' => $menu_html,
	    'PAGE_HTML' => $page_html,
	  ));
	  $this->SMARTY->assign('M', $smarty_params);
	  

    $output = $this->SMARTY->fetch( ROOT.'/_lib/mPortal/resources/layout.tpl' );
	  	  
	  // Minify and display final html output
	  if( $_REQUEST['MINIFY_OUTPUT']!=='0' ){ $output = minify_html($output); }
	  echo $output;
		
	}
	
	function addLib($lib){	  
	  $parts = parse_url($lib);
	  $lib = $parts['path'];
	  $params = $parts['query'];
	  
	  $fulllib = $lib.'.js';
	  if( !empty($params) ) { $fulllib .= '?'.$params; }
	  if( file_exists(ROOT.'/jsLibs/'.$lib.'.js') ) { $this->JSLIBS[] = $fulllib; }
	  
	  $fulllib = $lib.'.css';
	  if( !empty($params) ) { $fulllib .= '?'.$params; }
	  if( file_exists(ROOT.'/cssLibs/'.$lib.'.css') ) { $this->CSSLIBS[] = $fulllib; }
	}

}