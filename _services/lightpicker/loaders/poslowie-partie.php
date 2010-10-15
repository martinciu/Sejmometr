<?
  if($q!='') $data = $this->DB->selectAssocs("SELECT ludzie.imie, ludzie.nazwisko, ludzie.partia_id, druki_autorzy.autor as 'partia' FROM ludzie LEFT JOIN druki_autorzy ON ludzie.partia_id=druki_autorzy.id WHERE ludzie.nazwisko LIKE '".$q."%' ORDER BY ludzie.nazwisko ASC, ludzie.imie ASC LIMIT 15");
  
  $html = '';
  foreach( $data as $item ) {
    $html .= '<li class="item" itemId="'.$item['partia_id'].'"><p><b>'.$item['nazwisko'].' '.$item['imie'].'</b></p><p>'.$item['partia'].'</p></li>';
  }
   
  return array('html'=>$html);
?>