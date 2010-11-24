<?
  if($q!='') $data = $this->DB->selectAssocs("SELECT poslowie.imie, poslowie.nazwisko, poslowie.klub, druki_autorzy.autor as 'partia' FROM poslowie LEFT JOIN druki_autorzy ON poslowie.klub=druki_autorzy.id WHERE poslowie.nazwisko LIKE '".$q."%' ORDER BY poslowie.nazwisko ASC, poslowie.imie ASC LIMIT 15");
  
  $html = '';
  foreach( $data as $item ) {
    $html .= '<li class="item" itemId="'.$item['partia_id'].'"><p><b>'.$item['nazwisko'].' '.$item['imie'].'</b></p><p>'.$item['partia'].'</p></li>';
  }
   
  return array('html'=>$html);
?>