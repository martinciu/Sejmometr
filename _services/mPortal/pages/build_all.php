<?
  $this->S('mPortal/pages/build_engines');
  $pages = $this->DB->selectValues("SELECT id FROM ".DB_TABLE_pages);
  if( is_array($pages) ) foreach( $pages as $page ) {
    $this->S('mPortal/pages/build', $page);
  }
?>