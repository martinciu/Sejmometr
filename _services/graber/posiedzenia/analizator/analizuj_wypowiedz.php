<?
  /* return codes
    
    2 - identify errors
    3 - dzień był już wcześniej pobierany
    4 - OK
    5 - statusy error
    
  */
  
  $dzien_id = $_PARAMS;
  if( strlen($dzien_id)!=5 ) return false;
  $_funkcje = $this->DB->selectAssocs("SELECT id, fraza FROM wypowiedzi_funkcje ORDER BY LENGTH(fraza) DESC");
  
  $this->DB->q("INSERT INTO analizator (dzien_id, typ) VALUES ('$dzien_id', '0')");
  
  $statusy = $this->DB->selectValues("SELECT DISTINCT(status) FROM `dni_modele` WHERE `dzien_id`='$dzien_id' AND `typ`='1'");  
  if( count($statusy)==1 && $statusy[0]=='2' ) {
    
    $this->DB->update_assoc('posiedzenia_dni', array('analiza_wystapienia'=>'1'), $dzien_id);
    
    $data = $this->DB->selectAssocs("SELECT id, sejm_id, typ, autor, punkty, text FROM dni_modele WHERE dzien_id='$dzien_id' ORDER BY ord ASC");
    
    // GŁOSOWANIA
    $glosowania = array();
    $temp = array();
    $lastAutor = '';
    foreach( $data as $item ) {
      switch( $item['typ'] ) {
        case '4': {$lastAutor=$item['text']; break;}
        case '3': {$item['autor'] = $lastAutor; $temp[]=$item; break;}
        case '1': {$temp[]=$item; break;}
        case '2': {
          $url = $item['sejm_id'];
          $temp[count($temp)-1]['glosowanie_id'] = $this->S('graber/glosowania/dodaj', array($url, $dzien_id));                   
          break;
        }
      }     
    }
    $data = $temp;
    unset($temp);


    // PARSER STAMPS 
    $count = count( $data );
    while( list($i, $item)=each($data) ) {
      if($item['typ']=='1') {
        $data[$i]['sejm_id'] = array( $data[$i]['sejm_id'] );
      } else {
        unset($data[$i]['sejm_id']);
      }
      if( ($item['typ']=='1') && ($i<$count-3) && ($data[$i+1]['typ']=='3') && ($data[$i+2]['typ']=='1') && ($data[$i+2]['autor']==$data[$i]['autor']) ) {
        $data[$i]['_parser_stamp'] = true;
        $data[$i+1]['_parser_stamp'] = true;
        $data[$i+2]['_parser_stamp'] = true;
      }
    }
    
              
    
    // MERGE
	  $result = array();
	  for( $i=0; $i<count($data); $i++ ) {
	    $item = $data[$i];
	    if( $item['typ']=='1' ) $item['typ']='0';
	    $parserControl = $item['_parser_stamp'];
	    unset($item['_parser_stamp']);
	    if( ($parserControl) && ($lastParserControl)  ) {
	      if( $item['typ']=='3' ) { $result[count($result)-1]['text'] .= '<p class="wMarszalek">'.$item['funkcja'].': '.$item['text'].'</p>'; }
	      if( $item['typ']=='0' )  { $result[count($result)-1]['text'] .= $item['text']; }
	      if($item['sejm_id'][0]) $result[count($result)-1]['sejm_id'][] = $item['sejm_id'][0];
	    } else { $result[] = $item; }  
	        
	    $lastParserControl = $parserControl;
	  }
	  
	  if( ($result[0]['typ']=='3') && ($result[1]['typ']=='3') ) {
	    reset($result);
	    $pierwszy = array_shift($result);
	    $result[0]['text'] = $pierwszy['text'].$result[0]['text'];
	  }
	  unset($data);
	  
	  
	  
	  // IDENTIFY AUTHORS
	  foreach( $result as &$item ) {
	    $item['status'] = '0';
	    if( $item['typ']=='0' ) {
	      $autor = trim($item['autor']);
	      unset( $item['autor'] );
	      if( $autor=='' ) { $item['status']='1'; } else {
	        
	        $funkcja_id = false;
			    reset($_funkcje);
			    
			    foreach( $_funkcje as $_funkcja ) {
			      if( stripos($autor, $_funkcja['fraza'])===0 ) {
			        $funkcja_id = $_funkcja['id'];
			        break;
			      }
			    }
			   
			    
			    if( $funkcja_id!==false ) {
			    
			      $autor_fraza = trim(str_replace($_funkcja['fraza'], '', $autor));
			      $autor_id = $this->DB->selectValue("SELECT id FROM ludzie WHERE fraza='$autor_fraza'");
			      if( $autor_id ) {
			        
			        $item['autor_id'] = $autor_id;
			        $item['funkcja_id'] = $funkcja_id;
			        $item['status'] = '4';
			        
			      } else {
			        
			        //  NIE ROZPOZNANO AUTORA
			        $autor_id = $this->DB->selectValue("SELECT id FROM wypowiedzi_nierozpoznani_autorzy WHERE autor='$autor'");
				      if( !$autor_id ) {
				        $this->DB->insert_ignore_assoc('wypowiedzi_nierozpoznani_autorzy', array('autor' => $autor));
				        $autor_id = $this->DB->insert_id;
				      }
				      $this->DB->insert_ignore_assoc('wypowiedzi_id-nierozpoznani_autorzy', array(
			          'wyp_id' => $wyp_id,
			          'autor_id' => $autor_id,
			        ));
			        $item['status'] = '3';
			        
			      }
			    } else {
			      
			      // NIE ROZPONANO FUNKCJI
			      $autor_id = $this->DB->selectValue("SELECT id FROM wypowiedzi_nierozpoznani_autorzy WHERE autor='$autor'");
			      if( !$autor_id ) {
			        $this->DB->insert_ignore_assoc('wypowiedzi_nierozpoznani_autorzy', array('autor' => $autor));
			        $autor_id = $this->DB->insert_id;
			      }
			      $this->DB->insert_ignore_assoc('wypowiedzi_id-nierozpoznani_autorzy', array(
		          'wyp_id' => $wyp_id,
		          'autor_id' => $autor_id,
		        ));
			      $item['status'] = '2';
			      
			    }
	        
	      }
	    }
	  }
    
    $data = $result;
    
    // PUNKTY
    $punkty = array();
    for( $i=0; $i<count($data); $i++ ) {
      if( $data[$i]['typ']=='0' ) {
        $p = trim($data[$i]['punkty']);
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
            $punkt_id = $this->DB->generate_id('punkty_wypowiedzi');
            $punkty[] = array('id'=>$punkt_id, 'label'=>$p, 'count'=>1);
          }
          
          $data[$i]['punkt_id'] = $punkt_id;
        }
      }
    }
    
    
    $ord = 0;
    foreach( $punkty as $punkt ){
      $punkt_id = $this->DB->selectValue("SELECT id FROM punkty_wypowiedzi WHERE dzien_id='$dzien_id' AND sejm_id='".$punkt['label']."'");
      if( empty($punkt_id) ) {
	      
	      $ord++;
	      $this->DB->insert_assoc('punkty_wypowiedzi', array(
	        'id' => $punkt['id'],
	        'dzien_id' => $dzien_id,
	        'sejm_id' => addslashes($punkt['label']),
	        'ilosc_wypowiedzi' => $punkt['count'],
	        'ord' => $ord,
	      ));
	      
      } else {
        
        $this->DB->update_assoc('punkty_wypowiedzi', array(
	        'ilosc_wypowiedzi' => $punkt['count'],
	        'data_dodania' => 'NOW()',
	      ), $punkt_id);
	      for( $i=0; $i<count($data); $i++ ) {
		      if( $data[$i]['typ']=='0' && isset($data[$i]['punkt_id']) && $data[$i]['punkt_id']==$punkt['id'] ) { $data[$i]['punkt_id'] = $punkt_id; }
		    }
		    
      }
    }
    
    $this->S('liczniki/nastaw/punkty_wypowiedzi');
    
    // ZAPIS  
    
    if( $this->DB->selectCountBoolean("SELECT COUNT(*) FROM wypowiedzi WHERE dzien_id='$dzien_id'") ) {
      $this->DB->update_assoc('posiedzenia_dni', array('analiza_wystapienia'=>'3'), $dzien_id);
      $this->S('liczniki/nastaw/dni');
      return 3;
    } else {
    
	    for( $i=0; $i<count($data); $i++ ) {
	      $iterator = $i+1;
	      $item = $data[$i];
	            
	      $wypowiedz_id = $this->DB->insert_assoc_create_id('wypowiedzi', array(
	        'typ' => $item['typ'],
	        'dzien_id' => $dzien_id,
	        'punkt_id' => $item['punkt_id'],
	        'autor_id' => $item['autor_id'],
	        'funkcja_id' => $item['funkcja_id'],
					'status' => $item['status'],
	        'glosowanie_id' => $item['glosowanie_id'],
	        'text' => addslashes($item['text']),
	        'ord' => $iterator,
	        'ilosc_slow' => $this->S('utils/policz_slowa', $item['text']),
	      ));
	      
	      $sejm_ids = $item['sejm_id'];
	      if( is_array($sejm_ids) ) foreach( $sejm_ids as $sejm_id ) {
	        $this->DB->insert_ignore_assoc('wypowiedzi_sejm_id', array(
	          'wypowiedz_id' => $wypowiedz_id,
	          'sejm_id' => $sejm_id,
	        ));
	      }
	    }
	    
    }
    
    $this->DB->q("UPDATE wypowiedzi LEFT JOIN punkty_wypowiedzi ON wypowiedzi.punkt_id=punkty_wypowiedzi.id SET wypowiedzi.typ='2' WHERE wypowiedzi.punkt_id!='' AND punkty_wypowiedzi.sejm_id='Oświadczenia'");
    $this->DB->update_assoc('posiedzenia_dni', array('analiza_wystapienia'=>'4'), $dzien_id);
    $this->S('liczniki/nastaw/dni');
    $this->S('liczniki/nastaw/funkcje');

    return 4;
  } else {
    $this->DB->update_assoc('posiedzenia_dni', array('analiza_wystapienia'=>'5'), $dzien_id);
    $this->S('liczniki/nastaw/dni');
    return 5;
  }
  
  
?>