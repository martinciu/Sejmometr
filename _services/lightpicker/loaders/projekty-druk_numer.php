<?    
  if( strlen($q)<3 ) { $html=''; } else {
       
	  $q = "select projekty.id, druki.numer, druki_autorzy.autor, projekty.tytul, pdruki.numer from druki join projekty_etapy on druki.id=projekty_etapy.etap_id join projekty ON projekty_etapy.projekt_id=projekty.id left join druki_autorzy on projekty.autor_id=druki_autorzy.id join druki as pdruki on projekty.druk_id=pdruki.id where projekty_etapy.typ_id=0 AND druki.numer LIKE \"%$q%\" LIMIT 10";
	  $data = $this->DB->selectAssocs($q);
	  
	  $html = '';
	  foreach( $data as $item ) {
	    $html .= '<li class="item" itemId="'.$item['id'].'"><h4>'.$item['numer'].'</h4><p>'.$item['autor'].' - '.$item['tytul'].'</p></li>';
	  }
  
  }
   
  return array('html'=>$html);
?>