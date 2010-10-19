<?
  require_once( $_SERVER['DOCUMENT_ROOT'].'/_lib/mPortal/REQUEST.php' );
  $M = new REQUEST(array(
    'DONT_VERIFY_USER' => true
  ));
  $DB = &$M->DB;
  
  $free_access_requests = array('pobierz_dokument_z_podajnika', 'build_engines', 'git_hub_sync');
  
  if( !in_array($_REQUEST['_SERVICE'], $free_access_requests) ) $M->setAccess(2);
  include( ROOT.'/_requests/'.$_REQUEST['_SERVICE'].'.php' );    
?>