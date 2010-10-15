<?
  require_once( $_SERVER['DOCUMENT_ROOT'].'/_lib/mPortal/PATTERN.php' );
  $M = new PATTERN(array('ID'=>$_REQUEST['_PID']));
  $DB = &$M->DB;
       
  $_SERVICE = $_REQUEST['_SERVICE'];
  $_PARAMS = $_REQUEST['_PARAMS'];
    
  if( empty($_SERVICE) ) { die(); }
  $M->render_pattern($_SERVICE, $_PARAMS ? params_decode($_PARAMS) : null);
?>