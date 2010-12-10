<?
  $id = $_PARAMS;
  $wpisy = array();
  
  $this->DB->update_assoc('poslowie', array('status_kluby_historia'=>'1', 'data_kluby_historia'=>'NOW()'), $id);
  list( $data_wybrania, $_klub_id ) = $this->DB->selectRow("SELECT data_wybrania, klub FROM poslowie WHERE id='$id'");
  
  $kluby_ids = $this->DB->selectValues("SELECT DISTINCT(klub_id) FROM glosowania_glosy WHERE czlowiek_id='$id'");
  if( count($kluby_ids)==1 ) {
    $ostatni_klub = $kluby_ids[0];
    $wpisy[] = array( $data_wybrania, $ostatni_klub );
  } else {
  
    $min_timestamp = 0;
    $max_timestamp = 0;
    foreach( $kluby_ids as $klub_id ) {
      $dzien_id = $this->DB->selectValue("SELECT glosowania.dzien_id FROM glosowania_glosy JOIN glosowania ON glosowania_glosy.glosowanie_id=glosowania.id JOIN posiedzenia_dni ON glosowania.dzien_id=posiedzenia_dni.id WHERE glosowania_glosy.czlowiek_id='$id' AND glosowania_glosy.klub_id='$klub_id' ORDER BY posiedzenia_dni.data ASC LIMIT 1");
      $data = $this->DB->selectValue("SELECT data FROM posiedzenia_dni WHERE posiedzenie_id IN (SELECT posiedzenie_id FROM posiedzenia_dni WHERE id='$dzien_id') ORDER BY data ASC LIMIT 1");
      $timestamp = strtotime($data);
      if( $min_timestamp==0 || $timestamp<$min_timestamp ) {
        $min_timestamp = $timestamp;
        $min_date = $data;
      }
      if( $max_timestamp==0 || $timestamp>$max_timestamp ) {
        $max_timestamp = $timestamp;
        $max_date = $data;
      }
      $wpisy[] = array( $data, $klub_id );
    }
    
    foreach( $wpisy as &$_wpis ) {
      if( $_wpis[0]==$min_date ) $_wpis[0] = $data_wybrania;
      if( $_wpis[0]==$max_date ) $ostatni_klub = $_wpis[1];
    }
    
  }
  
  var_export($wpisy);
  die();
  
  
  foreach($wpisy as $wpis) {
    $this->DB->q("INSERT IGNORE INTO poslowie_kluby_historia (`posel_id`, `data_sejm`, `data`, `klub_id`) VALUES ('$id', '".$wpis[0]."', '".$wpis[0]."', '".$wpis[1]."')");
  }
  
  $params = array(
    'status_kluby_historia' => '2',
    'data_kluby_historia' => 'NOW()',
  );
  
  if( $ostatni_klub!=$_klub_id ) $params['update'] = '1';
  
  $this->DB->update_assoc('poslowie', $params, $id);
  
  if( $ostatni_klub!=$_klub_id ) $this->S('liczniki/nastaw/poslowie');
?>