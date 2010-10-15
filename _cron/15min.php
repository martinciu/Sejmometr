<?
  $_SERVER['DOCUMENT_ROOT'] = '/sejmometr/';
  require_once( $_SERVER['DOCUMENT_ROOT'].'/_lib/mPortal/REQUEST.php' );
  $M = new REQUEST(array(
    'DONT_VERIFY_USER' => true
  ));
  $DB = &$M->DB;
  $M->USER['group'] = '2';  
    
  // DOKUMENTY
  $M->S('graber/dokumenty/scribd/pobierz_pdfy');
  $M->S('liczniki/nastaw/dokumenty_obrabiane');
?>