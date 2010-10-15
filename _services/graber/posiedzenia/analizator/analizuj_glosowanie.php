<?
  /* return codes
    
    2 - identify errors
    3 - dzień był już wcześniej pobierany
    4 - OK
    5 - statusy error
    
  */
  
  $dzien_id = $_PARAMS;
  if( strlen($dzien_id)!=5 ) return false;
  
  $this->DB->q("INSERT INTO analizator (dzien_id, typ) VALUES ('$dzien_id', '1')");
  
  $statusy = $this->DB->selectValues("SELECT DISTINCT(status) FROM `glosowania` WHERE `dzien_id`='$dzien_id'");
  
  
  
  if( empty($statusy) ) {
    
    $this->DB->update_assoc('posiedzenia_dni', array('analiza_glosowania'=>'4'), $dzien_id);
    return 4;
    
  } elseif( count($statusy)==1 && $statusy[0]=='2' ) {
    
    // $this->DB->update_assoc('posiedzenia_dni', array('analiza_glosowania'=>'1'), $dzien_id);
    
    $data = $this->DB->selectAssocs("SELECT glosowania.id, glosowania_modele.punkt FROM glosowania LEFT JOIN glosowania_modele ON glosowania.id=glosowania_modele.glosowanie_id WHERE glosowania.dzien_id='$dzien_id' ORDER BY glosowania.numer ASC");
           
    // PUNKTY
    $punkty = array();
    for( $i=0; $i<count($data); $i++ ) {
      $p = trim($data[$i]['punkt']);
      if( $p!='' ) {
        
        $found = false;
        for( $j=0; $j<count($punkty); $j++ ) {
          if( $punkty[$j]['label']==$p ) {
            $found = true;
            $punkt_id = $punkty[$j]['id'];
            $punkty[$j]['count']++;
          }
        }
        
        if( !$found ) {
          $punkt_id = $this->DB->generate_id('punkty_glosowania');
          $punkty[] = array('id'=>$punkt_id, 'label'=>$p, 'count'=>1);
        }
        
        $data[$i]['punkt_id'] = $punkt_id;
      }
    }
    
    
    
    $ord = 0;
    foreach( $punkty as $punkt ){
      $punkt_id = $this->DB->selectValue("SELECT id FROM punkty_glosowania WHERE dzien_id='$dzien_id' AND sejm_id='".$punkt['label']."'");
      if( empty($punkt_id) ) {
	      
	      $ord++;
	      $this->DB->insert_assoc('punkty_glosowania', array(
	        'id' => $punkt['id'],
	        'dzien_id' => $dzien_id,
	        'sejm_id' => addslashes($punkt['label']),
	        'ilosc_glosowan' => $punkt['count'],
	        'ord' => $ord,
	      ));
	      
      } else {
        
        $this->DB->update_assoc('punkty_glosowania', array(
	        'ilosc_glosowan' => $punkt['count'],
	        'data_dodania' => 'NOW()',
	      ), $punkt_id);
	      for( $i=0; $i<count($data); $i++ ) {
		      if( $data[$i]['punkt_id']==$punkt['id'] ) { $data[$i]['punkt_id'] = $punkt_id; }
		    }
		    
      }
    }
    
    $this->S('liczniki/nastaw/punkty_glosowania');
    

    
    // ZAPIS  
    
    if( $this->DB->selectCountBoolean("SELECT COUNT(*) FROM glosowania WHERE punkt_id!='' AND dzien_id='$dzien_id'") ) {
      $this->DB->update_assoc('posiedzenia_dni', array('analiza_glosowania'=>'3'), $dzien_id);
      return '3';
    } else {
    
	    for( $i=0; $i<count($data); $i++ ) {
	      $item = $data[$i];	      
        $this->DB->update_assoc('glosowania', array(
          'punkt_id' => $item['punkt_id'],
        ), $item['id']);
	    }
	    
    }
    
    
	  // STATS
    $ids = $this->DB->selectValues("SELECT id FROM punkty_glosowania WHERE dzien_id='$dzien_id'");
	  foreach( $ids as $id ) {
	    list($czas_start, $czas_koniec, $ilosc_glosowan) = $this->DB->selectRow("SELECT MIN(czas), MAX(czas), COUNT(*) FROM glosowania WHERE punkt_id='$id'");
	    $this->DB->q("UPDATE punkty_glosowania SET `czas_start`='$czas_start', `czas_koniec`='$czas_koniec', `ilosc_glosowan`='$ilosc_glosowan' WHERE id='$id'");
	  }
    $this->DB->q("UPDATE punkty_glosowania LEFT JOIN posiedzenia_dni ON punkty_glosowania.dzien_id=posiedzenia_dni.id SET punkty_glosowania.pp='1' WHERE punkty_glosowania.dzien_id='$dzien_id' AND (posiedzenia_dni.data!=DATE(punkty_glosowania.czas_start) OR posiedzenia_dni.data!=DATE(punkty_glosowania.czas_koniec))");
    
    
    
    
    
    $this->DB->update_assoc('posiedzenia_dni', array('analiza_glosowania'=>'4'), $dzien_id);
    return 4;
  } else {
    $this->DB->update_assoc('posiedzenia_dni', array('analiza_glosowania'=>'0'), $dzien_id);
    return 5;
  }
?>