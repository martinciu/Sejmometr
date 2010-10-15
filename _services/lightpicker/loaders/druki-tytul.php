<?
  include_once( ROOT.'/_components/docthumbs/docthumbs.php' );
  
    
  if( strlen($q)<3 ) { $html=''; } else {
    
    $where = '1';
    if( $params['date_gt'] ) {
      $where = "druki.data<='".$params['date_gt']."'";
    }
    
	  $q = "SELECT druki.id, druki.numer, druki.data, druki.dokument_id, druki.tytul_oryginalny, druki_autorzy.autor, druki_typy.label as 'typ' FROM druki LEFT JOIN druki_autorzy ON druki.autorA_id=druki_autorzy.id LEFT JOIN druki_typy ON druki_typy.id=druki.typ_id WHERE druki.tytul_oryginalny LIKE '%".$q."%' AND ($where) ORDER BY druki.numer ASC LIMIT 10";
	  $data = $this->DB->selectAssocs($q);
	  
	  $html = '';
	  foreach( $data as $item ) {
	    $html .= '<li class="item" itemId="'.$item['id'].'">'.sf_docthumbs(array('id'=>$item['dokument_id'], 'size'=>3)).'<div class="content"><h4>'.$item['numer'].'</h4><p class="data">'.$item['data'].'</p><p>'.$item['typ'].' - '.$item['autor'].'</p><p class="tytul">'.$item['tytul_oryginalny'].'</p></div></li>';
	  }
  
  }
   
  return array('html'=>$html);
?>