<?
  include_once( ROOT.'/_components/docthumbs/docthumbs.php' );

  $data = $this->DB->selectAssocs("SELECT druki.id, druki.data, druki.numer, druki.dokument_id, druki.tytul_oryginalny, druki_autorzy.autor, druki_typy.label as 'typ' FROM druki LEFT JOIN druki_autorzy ON druki.autorA_id=druki_autorzy.id LEFT JOIN druki_typy ON druki_typy.id=druki.typ_id WHERE druki.numer LIKE '".$q."%' ORDER BY druki.numer ASC LIMIT 10");
  
  $html = '';
  foreach( $data as $item ) {
    $html .= '<li class="item" itemId="'.$item['id'].'">'.sf_docthumbs(array('id'=>$item['dokument_id'], 'size'=>3)).'<div class="content"><h4>'.$item['numer'].'</h4><p class="data">'.$item['data'].'</p><p>'.$item['typ'].' - '.$item['autor'].'</p><p class="tytul">'.$item['tytul_oryginalny'].'</p></div></li>';
  }
   
  return array('html'=>$html);
?>