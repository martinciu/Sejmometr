<?
  $_SERVER['DOCUMENT_ROOT'] = '/sejmometr/';
  require_once( $_SERVER['DOCUMENT_ROOT'].'/_lib/mPortal/REQUEST.php' );
  $M = new REQUEST(array(
    'DONT_VERIFY_USER' => true
  ));
  $DB = &$M->DB;
  $M->USER['group'] = '2';  
  
  $M->S('liczniki/nastaw_wszystkie');
  $M->S('utils/ofensywa/refresh');
  $M->S('kontrola/dokumenty/sprawdz_kolejne', 100);
?>