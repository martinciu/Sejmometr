<?
  $component = $_PARAMS;
  if( empty($component) ) return 0;
  
  $pages = $this->DB->selectValues("SELECT page FROM ".DB_TABLE_pages_components." WHERE component='$component'");
  if( is_array($pages) ) foreach($pages as $page) {
    $this->S('mPortal/pages/build', $page);
  }
?>