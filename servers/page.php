<?
  require_once( $_SERVER['DOCUMENT_ROOT'].'/_lib/mPortal/PAGE.php' );  
  $M = new Page($_REQUEST['_PAGE']);    
  $M->render_page();
?>