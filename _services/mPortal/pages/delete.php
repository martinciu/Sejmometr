<?
  $page = $_PARAMS;
  if( empty($page) ) return 0;
  
  // files
  recurse_rmdir( ROOT.'/_pages/'.$page );  
  
  // db _pages
  $this->DB->q("DELETE FROM ".DB_TABLE_pages." WHERE id='$page'");
  
  // db _pages_access
  $this->DB->q("DELETE FROM ".DB_TABLE_pages_access." WHERE page='$page'");
  
  // db _components
  $this->DB->q("DELETE FROM ".DB_TABLE_pages_components." WHERE page='$page'");
  
  // db _pages_lbs
  $this->DB->q("DELETE FROM ".DB_TABLE_pages_libs." WHERE page='$page'");
  
  // db _services
  $this->DB->q("DELETE FROM ".DB_TABLE_services." WHERE page='$page'");
  $this->DB->q("DELETE FROM ".DB_TABLE_services_access." WHERE page='$page'");
    
  $this->S('mPortal/pages/build', $page);
?>