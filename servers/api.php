<?
  // echo 'SERVICE SERVER'; die();
  require_once( $_SERVER['DOCUMENT_ROOT'].'/_constructors/REQUEST.php' );
     
  $_ACTION = $_REQUEST['_ACTION'];
  $_PARAMS = $_REQUEST;
  
  
  if( empty($_ACTION) ) { die(); }
    
  echo( json_encode($M->S('api/'.$_ACTION, $_PARAMS ? params_decode($_PARAMS) : null)) );
?>