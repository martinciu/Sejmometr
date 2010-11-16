<?
  $id = $_PARAMS;
  if( strlen($id)!=5 ) return false;
  
    
  $ilosc_wypowiedzi = $this->DB->selectCount("SELECT COUNT(*) FROM wypowiedzi WHERE punkt_id='$id' AND wypowiedzi.typ!='3'");
  
  $urzednicy = $this->DB->selectValues("SELECT wypowiedzi_funkcje.dopelniacz FROM wypowiedzi LEFT JOIN wypowiedzi_funkcje ON wypowiedzi.funkcja_id=wypowiedzi_funkcje.id WHERE wypowiedzi.punkt_id='".$id."' AND wypowiedzi.typ!='3' AND wypowiedzi.funkcja_id!=1 GROUP BY wypowiedzi.funkcja_id ORDER BY wypowiedzi.ord");
  $urzednicy_count = count($urzednicy);
  
  $poslowie = $this->DB->selectValues("SELECT DISTINCT(poslowie.id) FROM wypowiedzi LEFT JOIN poslowie ON wypowiedzi.autor_id=poslowie.id WHERE wypowiedzi.punkt_id='".$id."' AND wypowiedzi.typ!='3' AND wypowiedzi.funkcja_id=1 GROUP BY poslowie.id");
  $poslowie_count = count($poslowie);

  $ludzie = $this->DB->selectRows("SELECT ludzie.id, ludzie.avatar FROM wypowiedzi LEFT JOIN ludzie ON wypowiedzi.autor_id=ludzie.id WHERE wypowiedzi.punkt_id='".$id."' AND wypowiedzi.typ!='3' GROUP BY ludzie.id ORDER BY SUM(wypowiedzi.ilosc_slow) DESC");
  
  
  
  $count = min( 6, count($ludzie) );
  if( $count ) {
    $columns = min( 3, $count );
    $rows = floor(($count-1)/3)+1;
    
	  $x_size = 34*$columns+1;
	  $y_size = 34*$rows+1;
	  $img = imagecreatetruecolor($x_size, $y_size);
	  $white = imagecolorallocate($img, 255, 255, 255);
		imagefill($img, 0, 0, $white);
	  for( $i=0; $i<$count; $i++ ) {
	    $column = $i % 3;
	    $row = floor($i/3);
	    $posel_id = $ludzie[$i][0];		  
		  $pimg = $ludzie[$i][1]=='1' ? imagecreatefromjpeg( ROOT.'/l/3/'.$posel_id.'.jpg' ) : imagecreatefromjpeg( ROOT.'/g/gp_3.jpg' );
		  
	    imagecopy($img, $pimg, $column*34+1, $row*34+1, 0, 0, 33, 33);
	    imagedestroy($pimg);
	  }
	  
	  $file = ROOT.'/resources/debaty/banery/'.$id.'.jpg';
	  @unlink($file);
	  imagejpeg($img, $file, 95);
  }
  
  
  
  if( !$urzednicy_count && !$poslowie_count ) return false;
  
  $info = $ilosc_wypowiedzi==1 ? 'Wystąpienie' : 'Debata z udziałem';
  if( $urzednicy_count ) {
    $info .= ' <span class="u">'.implode('</span>, <span class="u">', $urzednicy).'</span>';
  }
  if( $poslowie_count ) {
    if( $urzednicy_count ) $info .= ' oraz';
    if( $ilosc_wypowiedzi>1 ) $info .= ' <b>'.$poslowie_count.'</b>';
	  $info .= $poslowie_count>1 ? ' posłów' : ' posła';
  }
  $info .= '.';
  
  return $info;
?>