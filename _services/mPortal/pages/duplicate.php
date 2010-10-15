<?
  /*
    return codes:
  
	    2 - source page doesn't exists
  
  */
  
  $src = $_PARAMS[0];
  $dest = $_PARAMS[1];
  
  
  $src_page_data = $this->DB->selectRow("SELECT id, title, layout, fullscreen FROM ".DB_TABLE_pages." WHERE id='$src'");
  
  if( empty($src_page_data) ) return 2;
  
  // files
  $temp = '_'.uniqid();
  
  recurse_copy( ROOT.'/_pages/'.$src, ROOT.'/_pages/'.$temp );
  rename(ROOT.'/_pages/'.$temp, ROOT.'/_pages/'.$dest);
  
  foreach( array('.php', '.tpl', '.js', '.css', '-inline.js', '-inline.css') as $ext ) @rename( ROOT.'/_pages/'.$dest.'/'.filename($src).$ext, ROOT.'/_pages/'.$dest.'/'.filename($dest).$ext );  
  
  
  // db _pages
  $this->DB->q("INSERT IGNORE INTO ".DB_TABLE_pages." (`id`, `title`, `layout`, `fullscreen`) VALUES ('".$dest."', '".$src_page_data[1]."', '".$src_page_data[2]."', '".$src_page_data[3]."') ON DUPLICATE KEY UPDATE `title`='".$src_page_data[1]."', `layout`='".$src_page_data[2]."', `fullscreen`='".$src_page_data[3]."'");
  
  // db _pages_access
  $groups = $this->DB->selectValues("SELECT DISTINCT `group` FROM ".DB_TABLE_pages_access." WHERE `page`='$src'");
  if( !empty($groups) ) $this->DB->q("INSERT IGNORE INTO ".DB_TABLE_pages_access." (`page`, `group`) VALUES ('".$dest."', '".implode("'), ('".$dest."', '", $groups)."')");
  
  // db _components
  $components = $this->DB->selectValues("SELECT DISTINCT `component` FROM ".DB_TABLE_pages_components." WHERE `page`='$src'");
  if( !empty($components) ) $this->DB->q("INSERT IGNORE INTO ".DB_TABLE_pages_components." (`page`, `component`) VALUES ('".$dest."', '".implode("'), ('".$dest."', '", $components)."')");
  
  // db _pages_lbs
  $libs = $this->DB->selectValues("SELECT DISTINCT `lib` FROM ".DB_TABLE_pages_libs." WHERE `page`='$src'");
  if( !empty($libs) ) $this->DB->q("INSERT IGNORE INTO ".DB_TABLE_pages_libs." (`page`, `lib`) VALUES ('".$dest."', '".implode("'), ('".$dest."', '", $libs)."')");
  
  // db _services
  $services = $this->DB->selectValues("SELECT DISTINCT `id` FROM ".DB_TABLE_services." WHERE `page`='$src'");
  if( !empty($services) ) foreach( $services as $service ) {
    $this->DB->q("INSERT IGNORE INTO ".DB_TABLE_services." (`id`, `page`) VALUES ('$service', '$dest')");
    $groups = $this->DB->selectValues("SELECT DISTINCT `group` FROM ".DB_TABLE_services_access." WHERE (`service`='$service' AND `page`='$src')");
	  if( !empty($groups) ) $this->DB->q("INSERT IGNORE INTO ".DB_TABLE_services_access." (`service`, `page`, `group`) VALUES ('".$service."', '".$dest."', '".implode("'), ('".$service."', '".$dest."', '", $groups)."')"); 
  }
    
  
  $this->S('mPortal/pages/build', $dest);
?>