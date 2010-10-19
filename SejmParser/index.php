<?
  $ACTION = trim($_GET['_ACTION']);
  if( empty($ACTION) ) { die(); }
  
  require_once('/_lib/SejmParser.php');
  $SP = new SejmParser();
  
  switch( $ACTION ) {
    case 'druki/lista-id': {
      $result = $SP->druki_lista_id( $_GET['limit'] ); break;
    }
    case 'druki/info_html': {
      echo $SP->druki_info_html( $_GET['id'] ); die();
    }
    case 'druki/info': {
      $result = $SP->druki_info( $_GET['id'] ); break;
    }
    case 'posiedzenia/lista': {
      $result = $SP->posiedzenia_lista(); break;
    }
    case 'posiedzenia/dni/lista': {
      $result = $SP->posiedzenia_dni_lista($_GET['id']); break;
    }
  }
  
  echo json_encode($result);
  // var_export($result);
?>