<?
  switch( strtolower($_REQUEST['_PAGE']) ) {
    case 'ofensywa': {
      header( "HTTP/1.1 301 Moved Permanently" );
      header( "Location: http://sejmometr.pl/blog/2,W_poszukiwaniu_ofensywy" );
    }
  }
  
  require_once( $_SERVER['DOCUMENT_ROOT'].'/_lib/mPortal/PAGE.php' );  
  $M = new Page($_REQUEST['_PAGE']);    
  $M->render_page();
?>