<?
  class SejmParser {
    
    function getData($s, $use_cache=false) {
      if( $use_cache && isset( $_SESSION['_SEJMPARSER_'.$s] ) ) { return $_SESSION['_SEJMPARSER_'.$s]; } else {
        $txt = @file_get_contents( $s );
        preg_match('/HTTP\/(.*?) (.*?) (.*?)$/i', $http_response_header[0], $matches);
			  $this->response_status = (int) $matches[2];
        
        if( $txt!==false ) {
          // $size=mb_strlen($txt, '8bit');
          
          $txt = iconv( 'ISO-8859-2', 'UTF-8', $txt );
		      $txt = str_replace('&#260;', 'Ą', $txt);
		      $txt = str_replace('&#261;', 'ą', $txt);
		      $txt = str_replace('&#262;', 'Ć', $txt);
		      $txt = str_replace('&#263;', 'ć', $txt);
		      $txt = str_replace('&#281;', 'ę', $txt);
		      $txt = str_replace('&#321;', 'Ł', $txt);
		      $txt = str_replace('&#322;', 'ł', $txt);
		      $txt = str_replace('&#323;', 'Ń', $txt);
		      $txt = str_replace('&#324;', 'ń', $txt);
		      $txt = str_replace('&#211;', 'Ó', $txt);
		      $txt = str_replace('&#243;', 'ó', $txt);
		      $txt = str_replace('&#346;', 'Ś', $txt);
		      $txt = str_replace('&#347;', 'ś', $txt);
		      $txt = str_replace('&#377;', 'Ź', $txt);
		      $txt = str_replace('&#378;', 'ź', $txt);
		      $txt = str_replace('&#379;', 'Ż', $txt);
		      $txt = str_replace('&#380;', 'ż', $txt);
		      $txt = str_replace('&#733;', '"', $txt);
		      $txt = str_replace("\n", '', $txt);
          
          $_SESSION['_SEJMPARSER_'.$s] = $txt;
        }
        return $txt;
      }
    }
    
    function getDataUTF($s, $use_cache=true) {
      if( $use_cache && isset( $_SESSION['_SEJMPARSER_'.$s] ) ) { return $_SESSION['_SEJMPARSER_'.$s]; } else {
        $txt = @file_get_contents( $s );
        preg_match('/HTTP\/(.*?) (.*?) (.*?)$/i', $http_response_header[0], $matches);
			  $this->response_status = (int) $matches[2];

        if( $txt!==false ) $_SESSION['_SEJMPARSER_'.$s] = $txt;
        
        return $txt;
      }
    }
    
    function druki_lista_id_wszystko(){
      $result = array();
      $start_iterator = 0;
      do{
        $start_params = 1000*$start_iterator+1;
        $base_url = 'http://orka.sejm.gov.pl/Druki6ka.nsf/WWW-wszystkie?OpenView&Start='.$start_params.'&Count=1000';
        preg_match_all( "/Druki6ka.nsf\/([0-9a-zA-Z]+)\/([0-9a-zA-Z]+)?/i", $this->getData($base_url), $matches);
        for( $i=0; $i<count($matches[0]); $i++ ){ $result[] = $matches[1][$i].'/'.$matches[2][$i]; }
        $start_iterator++;
      } while( count($matches[0])==1000 );
      return array_unique($result);
    }
    
    function druki_lista_id($limit=null){
      $result = array();
      $base_url = 'http://orka.sejm.gov.pl/Druki6ka.nsf/WWW-wszystkie?OpenView&';
      $limit = (int) empty($limit) ? 999 : $limit;
      
      if( $limit>0 ) {
        preg_match_all( "/Druki6ka.nsf\/([0-9a-zA-Z]+)\/([0-9a-zA-Z]+)?/i", $this->getData($base_url.'Start=1&Count='.$limit), $matches);
	      for( $i=0; $i<count($matches[0]); $i++ ){
	        $result[] = $matches[1][$i].'/'.$matches[2][$i];
	      }
      }
          
      return array_unique($result);
    }
    
    function druki_info_html($id){
      $html = $this->getData('http://orka.sejm.gov.pl/Druki6ka.nsf/'.$id.'?OpenDocument');
      
      // known errors
      switch( $id ) {
        case '0/766A064C5F682C0AC12575A20041812E': {
		      $html = str_ireplace('1831.pdf', '1931.pdf', $html);
          break;
        }
      }
      
      return $html;
    }
    
    function druki_info($id){
      if( empty($id) ) { return false; }
      $txt = $this->druki_info_html($id);
      $result = array('id'=>$id);
      
		  preg_match( '/\<b\>(.*?)\<\/b\>/i', $txt, $matches );
		  $result['numer'] = trim( strip_tags( str_ireplace('Druk nr ', '', $matches[1]) ) );
		    		  
		  preg_match( '/([0-9]{4})\-([0-9]{2})\-([0-9]{2})/i', $txt, $matches );
		  $result['data'] = $matches[0];
		
		  $txt = substr( $txt, strpos($txt, $result['data'])+strlen($result['data']) );
		  preg_match( '/\<b\>(.*?)\<\/b\>/i', $txt, $matches );
		  $result['tytul'] = $matches[1];
		  
		  $result['pliki'] = array();
		  preg_match_all( '/\<a href=\/Druki6ka.nsf\/'.str_replace('/', '\/', $id).'\/\$file\/(.*?) target=\_blank\>(.*?)\<\/A\>/i', $txt, $matches );
		  if( is_array($matches[1]) ) foreach($matches[1] as $file) {
		    $pathparts = pathinfo($file);
		    if( strtolower($pathparts['extension'])=='pdf' ) { $result['pliki'][]= array(urldecode($file), urldecode('http://orka.sejm.gov.pl/Druki6ka.nsf/'.$id.'/$file/'.$file)); }
		  }
		  
		  switch( $id ) {
        case '0/892788F049011787C125755C00317CB4': {
		      if( is_array($result['pliki']) ) foreach( $result['pliki'] as &$plik ) {
		        if( $plik[0]=='1481.pdf' ) $plik[0] = '1481_SP1532.pdf';
		      }
          break;
        }
        case '0/E7B9E3FE0ED0F7A6C12574360036FCB4': {
		      if( is_array($result['pliki']) ) foreach( $result['pliki'] as &$plik ) {
		        if( $plik[0]=='290-002.pdf' ) $plik[0] = '290-002_SP6352.pdf';
		      }
          break;
        }
        case '0/3F68C9D5B9877976C125765D00332FA6': {
		      if( is_array($result['pliki']) ) foreach( $result['pliki'] as &$plik ) {
		        if( $plik[0]=='2332.pdf' ) $plik[0] = '2432.pdf';
		      }
          break;
        }
      }
		  return $result;
    }
    
    function posiedzenia_lista(){
      $txt = $this->getData('http://orka2.sejm.gov.pl/Debata6.nsf/chronWWW?OpenView');
      preg_match_all('/\?OpenView\&amp;Start=(.*?)\&amp;Count=(.*?)\&amp;Expand=(.*?)#(.*?)\<B\>(.*?)\<\/B\>/i', $txt, $matches);
      $result = array();
      for( $i=0; $i<count($matches[0]); $i++ ) {
        $result[] = array('temp_id'=>$matches[3][$i], 'numer'=>$matches[5][$i]);
      }
      return $result;
    }
    
    function posiedzenia_dni_lista($posiedzenie_id){
		  $txt = $this->getData('http://orka2.sejm.gov.pl/Debata6.nsf/chronWWW?OpenView&Start=1&Count=999&Expand='.$posiedzenie_id);
		  preg_match_all('/Expand='.$posiedzenie_id.'\.(.*?)#(.*?)\<B\>(.*?)([0-9]{2})-([0-9]{2})-([0-9]{4})\<\/B\>/i', $txt, $matches);
      
      $result = array();
      
      for( $i=0; $i<count($matches[1]); $i++ ) {
        $id = $matches[1][$i];
        $data = $matches[6][$i].'-'.$matches[5][$i].'-'.$matches[4][$i];
        
        $txt = $this->getData( 'http://orka2.sejm.gov.pl/Debata6.nsf/chronWWW?OpenView&Start=1&Count=999&Expand='.$posiedzenie_id.'.'.$id );
        preg_match('/Debata6.nsf\/([A-Za-z0-9]*)\/([A-Za-z0-9]*)\?OpenDocument(.*?)Przebieg posiedzenia(.*?)\<\/a\>/i', $txt, $_matches);
	      $dzien_id = $_matches[1].'/'.$_matches[2];
	      
        $result[] = array(
          'posiedzenie_temp_id' => $posiedzenie_id,
          'data' => $data,
          'dzien_id' => $dzien_id,
        );
      }
      
      return $result;
    }
    
    function posiedzenia_model_dnia($dzien_id){
      $txt = $this->getData( 'http://orka2.sejm.gov.pl/Debata6.nsf/'.$dzien_id.'?OpenDocument' );      
      $p = strpos($txt, '<P class="nagl">');
      $k = strpos($txt, '</BODY>');
      $txt = substr( $txt, $p, $k-$p );
      $txt = str_ireplace('<BLOCKQUOTE>', '<P>', $txt);
      $txt = str_ireplace('</BLOCKQUOTE>', '</P>', $txt);      
      $model = explode('</P>', $txt);
      array_shift($model);   
      
      $temp = array();
      if( is_array($model) ) foreach($model as $item) {
        $item = trim( str_ireplace('<br>', '', str_ireplace('</p>', '', str_ireplace('<p>', '', $item))) );
        
        if( $item!='' ) {
          
          /*
            0 - niewiadomo
            1 - wystąpienie
            2 - głosowanie
            3 - porządek tekst
            4 - porządek autor
          */
            
          if ( preg_match( '/\<A HREF=\"http\:\/\/orka.sejm.gov.pl\/SQL.nsf\/glosowania(.*?)\?OpenAgent(.*)\" TARGET=\"_BLANK\"\>/i', $item, $matches ) ) {
            $temp[] = array(
              'typ' => 2,
              'id' => 'glosowania'.$matches[1].'?OpenAgent'.$matches[2]
            );
            $ostatni_typ = 2;
          } elseif ( preg_match( '/\<A HREF=\"\/Debata6.nsf\/main\/(.*)">(.*)\<\/A\>/i', $item, $matches ) ) {
            $temp[] = array(
              'typ' => 1,
              'autor' => $matches[2],
              'id' => $matches[1]
            );
            $ostatni_typ = 1;
          } elseif ( preg_match( '/\<B\>\<FONT SIZE=\"\+1\"\>(.*)\:\<\/FONT\>\<\/B\>/i', $item, $matches ) ) {
            $temp[] = array(
              'typ' => 4,
              'text' => $matches[1]
            );
            $ostatni_typ = 4;
          } else {
            $item = substr($item, 7);
            if( $ostatni_typ==3 ) { $temp[count($temp)-1]['text'] = str_ireplace( ') (', '. ', $temp[count($temp)-1]['text'].' '.$item ); }
            else { $temp[] = array('typ'=>3, 'text'=>$item); }
            $ostatni_typ = 3;
          }
        }  
      }
      
      return $temp;
    }
    
    function wypowiedz_info_html($id){
      return $this->getData( 'http://orka2.sejm.gov.pl/Debata6.nsf/main/'.$id );
    }
    
    function wypowiedzi_info($id){
      $txt = $this->wypowiedz_info_html($id);
            
      preg_match('/\<SPAN class=\"punkt\"\>(.*?)\<\/P\>/i', $txt, $matches);
      $wypowiedz['punkty'] = trim( $matches[1] );
      
      $p = stripos($txt, '<P class="mowca">');
      if( stripos(substr($txt, 0, $p), 'Oświadczenia.')!==false ) { $wypowiedz['punkty']='Oświadczenia'; }
      
      $txt = substr($txt, $p);    
      $p = stripos($txt, '</P>');
      $mowca = substr($txt, 0, $p-1);
      $txt = '<P>'.substr($txt, $p+7);
      $p = stripos($txt, '<A');
      $txt = substr( $txt, 0, $p );
      $txt = str_ireplace('<br>', '', $txt);
      $txt = str_ireplace("\n", '', $txt);
      $txt = str_ireplace("</p>", '', $txt);
      $czesci = explode('<P>', $txt);
      $txt = '';
      for( $j=0; $j<count($czesci); $j++ ){
        $c = $czesci[$j];
        if( trim($c)!='' ) { $txt .= '<p>'.trim(substr($c, 7)); }
      }
      $wypowiedz['text'] = $txt;
      return $wypowiedz;
    }
    
    function glosowanie_info_html($id){
      return $this->getData( 'http://orka.sejm.gov.pl/SQL.nsf/'.$id );
    }
    
    function glosowanie_info($id){
      $html = $this->glosowanie_info_html($id);
      
      preg_match( '/\<TD(.*)class\="nazc"\>(.*?)Nr(.*?)-(.*?)\<B\>([0-9]{2})-([0-9]{2})-([0-9]{4})\<\/B\>(.*?)\<B\>([0-9]{2})\:([0-9]{2})\<(.*?)\<\/TD\>/i', $html, $matches );    
	    preg_match('/POSIEDZENIE (.*?)\./i', $matches[4], $mmm);
	    $result['posiedzenie_numer'] = trim( $mmm[1] );
	    
	    $result['numer'] = trim( $matches[3] );
	    $result['czas'] = trim( $matches[7].'-'.$matches[6].'-'.$matches[5].' '.$matches[9].':'.$matches[10].':00' );
	    
	    preg_match( '/<FONT color\="#990000"\>(.*?)\<\/FONT\>/i', $html, $matches );
	    $result['tytul'] = trim( $matches[1] );
	    
	    preg_match( '/class\="naz"\>\<B\>(.*?)\<\/B\>\<\/TD\>/i', $html, $matches );
	    $result['punkt'] = trim( $matches[1] );
	        
      return $result;
    }
    
    
    
    // PROJEKTY
    
    function projekty_ustawy_lista_id(){
      $result = array();    
		  $it = 0;
		  do {
		    $start = 1000*$it+1;
		    $txt =  $this->getData("http://orka.sejm.gov.pl/proc6.nsf/w-opisy-ust?OpenView&Start=$start&Count=1000");
		   
		    preg_match_all( '/\<HR(.*?)\<A HREF=\/proc6\.nsf\/(.*?)\?OpenDocument (.*?)\>/i', $txt, $matches );
		    $result = array_merge( $result, $matches[2] );
		    $it++;
		  } while ( count($matches[2])==1000 );
		  return $result;
    }
    
    function projekty_uchwaly_lista_id(){
      $result = array();    
		  $it = 0;
		  do {
		    $start = 1000*$it+1;
		    $txt =  $this->getData("http://orka.sejm.gov.pl/proc6.nsf/w-opisy-uch?OpenView&Start=$start&Count=1000");
		   
		    preg_match_all( '/\<HR(.*?)\<A HREF=\/proc6\.nsf\/(.*?)\?OpenDocument (.*?)\>/i', $txt, $matches );
		    $result = array_merge( $result, $matches[2] );
		    $it++;
		  } while ( count($matches[2])==1000 );
		  return $result;
    }
    
    function projekty_inne_lista_id(){
      $result = array();    
		  $it = 0;
		  do {
		    $start = 1000*$it+1;
		    $txt =  $this->getData("http://orka.sejm.gov.pl/proc6.nsf/w-opisy-inn?OpenView&Start=$start&Count=1000");
		   
		    preg_match_all( '/\<HR(.*?)\<A HREF=\/proc6\.nsf\/(.*?)\?OpenDocument (.*?)\>/i', $txt, $matches );
		    $result = array_merge( $result, $matches[2] );
		    $it++;
		  } while ( count($matches[2])==1000 );
		  return $result;
    }
    
    function projekt_info_html($id){
      return trim( $this->getData("http://orka.sejm.gov.pl/proc6.nsf/$id?OpenDocument") );
    }
        
    function projekt_info($id, $txt=false){
      $txt = $txt ? $txt : $this->projekt_info_html($id);
      $result = array();
      
      preg_match('/\<BODY(.*?)\<\/body\>/i', $txt, $matches);
		  $txt = $matches[0];
		  
		  if( preg_match('/wpłynął ([0-9]{2})-([0-9]{2})-([0-9]{4})/i', $txt, $matches) ) {
		    $result['data_wplynal'] = $matches[3].'-'.$matches[2].'-'.$matches[1];
		  }
		  
		  if( preg_match('/^(.*?)\>OPIS PROJEKTU\:(.*?)\<font(.*?)\>(.*?)\<\/font\>(.*?)\<\/body\>/i', $txt, $matches) ) {
		    $opis = addslashes( $matches[4] );
		    $opis[0] = strtoupper( $opis[0] );
		    $result['opis'] = $opis;
		  } else { unset($opis); }
		      
		  preg_match('/B\>(.*?)\</i', $txt, $matches);    
		  $result['tytul'] = addslashes( $matches[1] );

		  
		  // OPINIE BAS
		  $result['BAS'] = array();
		  $bas_strona_id = preg_match('/rexdomk6\.nsf\/Opdodr\?OpenPage\&nr\=(.*?)\"/i', $txt, $matches) ? $matches[1] : false;
		  if( $bas_strona_id ) {
		    $bas_html = $this->getData('http://orka.sejm.gov.pl/rexdomk6.nsf/Opdodr?OpenPage&nr='.$bas_strona_id);

    	  if( preg_match_all('/\<A HREF\=\/RexDomk6\.nsf\/(.*?)\/(.*?)\/\$file\/(.*?) (.*?)\<TD valign\=top\>(.*?)\</i', $bas_html, $matches) ) {
			    for( $i=0; $i<count($matches[0]); $i++ ) {
				    $_id = $matches[1][$i].'/'.$matches[2][$i];
				    $result['BAS'][] = array(
				     'id' => $_id,
				     'tytul' => addslashes($matches[5][$i]),
 			       'plik' => urldecode( $matches[3][$i] ),
 			       'download_url' => urldecode( 'http://orka.sejm.gov.pl/rexdomk6.nsf/'.$_id.'/$file/'.$matches[3][$i] ),
            );
		      }
		    }
		  }
		  
			// ISAP			
			$result['ISAP'] = preg_match_all('/DetailsServlet\?id\=(.*?)([ \"\<\>]+)/i', $txt, $matches) ? $matches[1] : array();
			foreach( $result['ISAP'] as &$r ) {
			  if( $r=='WDU20082061278' ) $r = 'WDU20082061287';
			} 
		  
		  
		  // DRUKI
		  $result['DRUKI'] = array();
			if( preg_match_all('/\<a href\="http\:\/\/orka\.sejm\.gov\.pl\/Druki6ka\.nsf\/druk\?OpenAgent&(.*?)"\>/i', $txt, $matches) ) {
			  $result['DRUKI'] = $matches[1];
			}
			
			
			
		  $result['txt'] = $txt;
		  
      return $result;
    }
    
    
    function isap_info($id) {
      $_statusy = array(
        '1' => 'obowiązujący', 
	      '2' => 'akt objęty tekstem jednolitym',
	      '3' => 'wygaśnięcie aktu',
	      '4' => 'uchylony',
	    );
	    $_pola = array(
				'tekst_ogloszony' => 'Tekst ogłoszony', 
				'tekst_aktu' => 'Tekst aktu', 
				'tekst_ujednolicony' => 'Tekst ujednolicony', 
				'data_obowiazywania' => 'Data obowiązywania', 
				'data_ogloszenia' => 'Data ogłoszenia', 
				'data_uchylenia' => 'Data uchylenia',
				'data_wejscia_w_zycie' => 'Data wejścia w życie', 
				'data_wydania' => 'Data wydania', 
				'data_wygasniecia' => 'Data wygaśnięcia', 
				'organ_uprawniony' => 'Organ uprawniony', 
				'organ_wydajacy' => 'Organ wydający', 
				'organ_zobowiazany' => 'Organ zobowiązany', 
				'status_aktu_prawnego' => 'Status aktu prawnego', 
				'uwagi' => 'Uwagi', 
			);
      
      $txt = $this->getDataUTF('http://isap.sejm.gov.pl/DetailsServlet?id='.$id);
      $result = array();
      
      preg_match('/class=\"h1\"(.*?)\>(.*?)\<\/span/i', $txt, $matches);
      $result['numer'] = addslashes(trim(strip_tags($matches[2])));
      
      preg_match('/class=\"h2\"(.*?)\>(.*?)\<\/span/i', $txt, $matches);
      $result['tytul'] = addslashes(trim(strip_tags($matches[2])));
      
      preg_match_all('/\<th(.*?)\>(.*?)\<\/th\>/i', $txt, $matches);
      
      $keys = array_keys($_pola);
      for( $i=0; $i<count($matches[0]); $i++ ) {
        preg_match('/\<td(.*?)\>(.*?)\<\/td\>/i', substr($txt, strpos($txt, $matches[0][$i])), $m);
        $_pole_i = array_search(str_replace(':', '', $matches[2][$i]), array_values($_pola));
        if( $_pole_i!==false ) $result[$keys[$_pole_i]] = addslashes(preg_replace('/\<img(.*?)\/\>/i', '', str_replace('&nbsp;', '', $m[2])));
      }
      
      $keys = array_keys($_statusy);
      $_status_i = array_search($result['status_aktu_prawnego'], array_values($_statusy));
      $result['status_aktu_prawnego'] = ($_status_i===false) ? 0 : $keys[$_status_i];
      
      return $result;
    }
    
    function glosowania_dni_lista(){
      $txt = $this->getData('http://orka.sejm.gov.pl/SQL.nsf/posglos?OpenAgent&6');
      $txt = substr($txt, strripos($txt, '<TABLE'));
      
      $result = array();
      preg_match_all('/\<TR\>\<TD(.*?)\>(.*?)\<\/TD\>\<TD(.*?)\>\<A HREF=\"\.\/listaglos\?OpenAgent&(.*?)\"\>(.*?)\<\/A\>\<\/TD\>\<TD(.*?)\>(.*?)\<\/TD\><\/TR\>/i', $txt, $matches);
      
      for( $i=0; $i<count($matches[0]); $i++ ) {
        $pos_nr = $matches[2][$i];
        if( $pos_nr=='&nbsp;' && $last_pos_nr ) $pos_nr = $last_pos_nr;
        $data_parts = explode('-', $matches[5][$i]);
        $result[] = array(
          'posiedzenie_nr' => $pos_nr,
          'sejm_id' => strip_tags($matches[4][$i]),
          'data' => $data_parts[2].'-'.$data_parts[1].'-'.$data_parts[0],
        );
        $last_pos_nr = $pos_nr;
      }
      
      return array_reverse($result);
    }
    
    function glosowania_lista($id){
      if( empty($id) ) return false;
      $txt = $this->getData('http://orka.sejm.gov.pl/SQL.nsf/listaglos?OpenAgent&'.$id);
            
      preg_match_all('/glosowania(.*?)\?OpenAgent&([0-9]+)&([0-9]+)&([0-9]+)/i', $txt, $matches);
      return array_unique($matches[0]);
    }
    
    
    
    
    
    
    
    // POSŁOWIE
    
    function poslowie_lista(){
      $txt = $this->getData('http://sejm.gov.pl/poslowie/lista6.htm');
      $txt .= "/n".$this->getData('http://sejm.gov.pl/poslowie/kluby/pos_arch.htm');
      $txt .= "/n".$this->getData('http://sejm.gov.pl/poslowie/kluby/pos_nowi.htm');
      
      preg_match_all('/posel6\/(.*?)\.htm/i', $txt, $matches);
       
      return array_values( array_unique( $matches[1] ) );
    }
    
    
    
    
    
    function posel_info($id){  
      $_fields = array(
        'Wybrany dnia' => 'data_wybrania',
        'Wybrana dnia' => 'data_wybrania',
        'lista' => 'lista',
        'okręg wyborczy' => 'okreg_nr',
        'liczba głosów' => 'liczba_glosow',
        'Staż parlamentarny' => 'staz',
        'Data urodzenia' => 'data_urodzenia',
        'Miejsce urodzenia' => 'miejsce_urodzenia',
        'Stan cywilny' => 'stan_cywilny',
        'Wykształcenie' => 'wyksztalcenie',
        'Ukończona szkoła' => 'szkola',
        'Zawód' => 'zawod',
        'Ślubował dnia' => 'data_slubowania',
        'Ślubowała dnia' => 'data_slubowania',
        'Tytuł/stopień naukowy' => 'tytul',
        'Wygaśnięcie mandatu' => 'data_wygasniecia',
      );
    
      $txt = $this->getData('http://sejm.gov.pl/poslowie/posel6/'.$id.'.htm');
      preg_match_all('/\<b\>(.*?)\<\/b\>(.*?)\</i', $txt, $matches);
      
      
      $result = array();
      $pola = array();
      
      for( $i=0; $i<count($matches[0]); $i++ ){
        if( strpos($matches[1][$i], ':')!==false ) {
        
	        $key = trim( str_replace(':', '', $matches[1][$i]) );
	        $val = trim( $matches[2][$i] );
	        if ( $key=='okręg wyborczy' ) {
	          preg_match('/([0-9]+)/i', $val, $m);
	          $val = $m[1];
	        }
	        
	        if ( $key=='Data i miejsce urodzenia' ) {
	          $parts = explode(',', $val);
	          $pola['Data urodzenia'] = trim( $parts[0] );
	          $pola['Miejsce urodzenia'] = trim( $parts[1] );
	        } elseif( $key!='Aktywność poselska' ) $pola[$key] = $val;
        
        }
      }
      
      foreach( $pola as $k=>$v ) {
        $key = $_fields[$k]=='' ? $k : $_fields[$k];
        $result[$key] = trim($v);
      }
      
      
      
      // NAZWISKO
      preg_match('/\<p (.*?)class=\"naz\"\>(.*?)\<\//i', $txt, $matches);
      $result['nazwisko'] = trim( strip_tags( $matches[2] ) );
      
      
      // KLUB 
      preg_match('/class=\"naz2\"\>\<a href=\"\.\.\/kluby\/(.*?)\.htm/i', $txt, $matches);
      $result['klub'] = trim( str_replace('kont_', '', $matches[1]));
      
      
      // OBRAZEK
      preg_match('/\/jpg\/posel6\/(.*?)\.jpg/i', $txt, $matches);
      $result['image_url'] = 'http://sejm.gov.pl/jpg/posel6/'.$matches[1].'.jpg';
      $result['image_md5'] = md5( file_get_contents( $result['image_url'] ) );
      
      return $result;
    }
    
  }
?>