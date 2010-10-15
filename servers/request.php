<?
  require_once( $_SERVER['DOCUMENT_ROOT'].'/_lib/mPortal/REQUEST.php' );
  $M = new REQUEST(array(
    'DONT_VERIFY_USER' => true
  ));
  $DB = &$M->DB;
  
  if( $_REQUEST['_SERVICE']!='pobierz_dokument_z_podajnika' && $_REQUEST['_SERVICE']!='build_engines' ) $M->setAccess(2);
  include( ROOT.'/_requests/'.$_REQUEST['_SERVICE'].'.php' );    
?>