<?    
  if( strlen($q)<3 ) { $html=''; } else {
    
    $where = 'projekty.id IS NOT NULL';
    
    $a = "SELECT projekty_druki.projekt_id FROM projekty_druki LEFT JOIN druki ON projekty_druki.druk_id=druki.id WHERE druki.numer LIKE '".$q."%'";
    
    
	  $q = "SELECT projekty.id, druki.numer, druki_autorzy.autor, projekty.tytul, druki.numer FROM druki LEFT JOIN projekty ON druki.id=projekty.druk_id LEFT JOIN druki_autorzy ON projekty.autor_id=druki_autorzy.id WHERE projekty.id IN ($a) AND ($where) ORDER BY druki.numer ASC LIMIT 10";
	  $data = $this->DB->selectAssocs($q);
	  
	  $html = '';
	  foreach( $data as $item ) {
	    $html .= '<li class="item" itemId="'.$item['id'].'"><h4>'.$item['numer'].'</h4><p>'.$item['autor'].' - '.$item['tytul'].'</p></li>';
	  }
  
  }
   
  return array('html'=>$html);
?>