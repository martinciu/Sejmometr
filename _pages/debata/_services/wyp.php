<?
  list( $id, $height ) = $_PARAMS;
  if( strlen($id)!=5 ) return false;
  
  $length = is_numeric($height) ? $height * 2.5 : 450;
  
  list($txt, $ilosc_slow) = $this->DB->selectRow("SELECT SUBSTRING(text, 1, $length), ilosc_slow FROM wypowiedzi WHERE id='".$id."'");
  $this->DB->q("UPDATE wypowiedzi SET ilosc_odwiedzin_ajax=ilosc_odwiedzin_ajax+1 WHERE id='$id'");
  
  $ilosc_slow = (int) $ilosc_slow;
  $p = strripos($txt, '. ');
  
  $txt = ($p===false) ? $txt : substr($txt, 0, $p+1);
  
  $_ilosc_slow = $this->S('utils/policz_slowa', $txt);
  
  
  if( $_ilosc_slow==0 || $ilosc_slow==0 ) {
    $ratio = 0;
  } else {
	  $r = $_ilosc_slow/$ilosc_slow;
	  $floor = floor( 10*$r )*10;
	  $ratio = ($floor==0) ? round($r) : $floor;
	  if( $ratio==0 ) $ratio = 1;
  }
  
  return array($txt, $ratio);
?>