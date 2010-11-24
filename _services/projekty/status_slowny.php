<?
  $status = $_PARAMS['status'];
  $substatus = $_PARAMS['substatus'];
	$data_ostatniego_procedowania = $_PARAMS['data_ostatniego_procedowania'];
	$data_przyjecia = $_PARAMS['data_przyjecia'];
	$typ = $_PARAMS['typ'];
	$_typy_schematy = array('1'=>'1', '2'=>'2', '3'=>'3', '4'=>'1', '5'=>'3', '7'=>'2', '8'=>'3', '10'=>'3', '11'=>'3', '12'=>'2');
  $schemat = $_typy_schematy[$typ];
	
	if($schemat=='1') {
	  
		switch( $status ) {
		  case '1': {
		    if( $substatus=='1' ) return $data_ostatniego_procedowania.' skierowano do pierwszego czytania.';
		    if( $substatus=='2' ) return $data_ostatniego_procedowania.' skierowano do pierwszego czytania w komisjach.';
		    if( $substatus=='3' ) return $data_ostatniego_procedowania.' projekt wpłynął, ale nie skierowano go do dalszych prac.';
		    break;
		  }
		  case '2': {
		    if( $substatus=='1' ) return $data_ostatniego_procedowania.' skierowano do komisji.';
		    if( $substatus=='2' ) return $data_ostatniego_procedowania.' komisja przedstawiła sprawozdanie.';
		    if( $substatus=='3' ) return $data_ostatniego_procedowania.' czytano w komisjach.';
		    if( $substatus=='5' ) return $data_ostatniego_procedowania.' zgłoszono autopoprawkę.';
		    if( $substatus=='6' ) return $data_ostatniego_procedowania.' przeprowadzono drugie czytanie.';
		    if( $substatus=='7' ) return $data_przyjecia.' przyjęto w Sejmie. '.$data_ostatniego_procedowania.' rozpatrywano poprawki Senatu.';
		    if( $substatus=='8' ) return $data_przyjecia.' przyjęto w Sejmie.';
		    if( $substatus=='9' ) return $data_ostatniego_procedowania.' przeprowadzono pierwsze czytanie.';
		    if( $substatus=='10' ) return $data_ostatniego_procedowania.' Senat przedstawił poprawki.';
		    break;
		  }
		  case '3': {
		    if( $substatus=='1' ) return $data_przyjecia.' przyjęto w Sejmie. '.$data_ostatniego_procedowania.' skierowano do Senatu.';
		  }
		  case '4': {
		    if( $substatus=='1' ) return $data_ostatniego_procedowania.' skierowano do Prezydenta, po wyroku Trybunału Konstytucyjnego.';
		    if( $substatus=='2' ) return $data_przyjecia.' przyjęto w Sejmie. '.$data_ostatniego_procedowania.' skierowano do Prezydenta.';
		    break;
		  }
		  case '5': {
		    if( $substatus=='1' ) return $data_przyjecia.' przyjęto w Sejmie. '.$data_ostatniego_procedowania.' opublikowano w Dzienniku Ustaw.';
		    if( $substatus=='2' ) return $data_przyjecia.' przyjęto w Sejmie. '.$data_ostatniego_procedowania.' Prezydent podpisał.';
		    break;
		  }
		  case '6': {
		    if( $substatus=='1' ) return $data_ostatniego_procedowania.' odrzucono w pierwszym czytaniu.';
		    if( $substatus=='2' ) return $data_ostatniego_procedowania.' odrzucono w drugim czytaniu.';
		    if( $substatus=='3' ) return $data_ostatniego_procedowania.' odrzucono w trzecim czytaniu.';
		    if( $substatus=='9' ) return $data_ostatniego_procedowania.' odrzucono na wniosek Senatu.';
		    if( $substatus=='10' ) return $data_ostatniego_procedowania.' wycofano.';
		    if( $substatus=='11' ) return $data_ostatniego_procedowania.' odrzucono po wecie Prezydenta.';
		    if( $substatus=='12' ) return $data_ostatniego_procedowania.' odrzucono z powodu niezgodności z Konstytucją.';
		    if( $substatus=='13' ) return $data_ostatniego_procedowania.' przyjęto w Sejmie. Prezydent zgłosił weto, ale Marszałek Sejmu zdecydował o jego nierozpatrywaniu. Projekt został odrzucony.';
		    break;
		  }
		  case '7': {
		    if( $substatus=='4' ) return $data_przyjecia.' przyjęto w Sejmie. '.$data_ostatniego_procedowania.' skierowano do Trybunału Konstytucyjnego.';
		  }
		}
	
	} elseif($schemat=='2') {
	
	  switch( $status ) {
	    case '1': {
	      if( $substatus=='1' ) return $data_przyjecia.' przyjęto w głosowaniu.';
	      if( $substatus=='2' ) return $data_przyjecia.' przyjęto przez aklamację.';
	      break;
	    }
	    case '2': {
	      if( $substatus=='1' ) return $data_ostatniego_procedowania.' czytano w komisjach.';
	      if( $substatus=='2' ) return $data_ostatniego_procedowania.' komisja przedstawiła sprawozdanie.';
	      if( $substatus=='3' ) return $data_ostatniego_procedowania.' skierowano do pierwszego czytania.';
		    if( $substatus=='4' ) return $data_ostatniego_procedowania.' skierowano do pierwszego czytania w komisjach.';
		    if( $substatus=='5' ) return $data_ostatniego_procedowania.' projekt wpłynął, ale nie skierowano go do dalszych prac.';
		    if( $substatus=='6' ) return $data_ostatniego_procedowania.' przeprowadzono pierwsze czytanie.';
		    if( $substatus=='7' ) return $data_ostatniego_procedowania.' przeprowadzono drugie czytanie.';
		    if( $substatus=='8' ) return $data_ostatniego_procedowania.' przeprowadzono trzecie czytanie.';
		    if( $substatus=='9' ) return $data_ostatniego_procedowania.' zgłoszono autopoprawkę.';
	      break;
	    }
	    case '3': {
	      if( $substatus=='1' ) return $data_ostatniego_procedowania.' projekt stał się nieaktualny.';
	      if( $substatus=='2' ) return $data_ostatniego_procedowania.' wycofano.';
	      if( $substatus=='3' ) return $data_ostatniego_procedowania.' odrzucono w pierwszym czytaniu.';
		    if( $substatus=='4' ) return $data_ostatniego_procedowania.' odrzucono w drugim czytaniu.';
		    if( $substatus=='5' ) return $data_ostatniego_procedowania.' odrzucono w trzecim czytaniu.';
	      break;
	    }
	  }
	
	} elseif($schemat=='3') {
	
	  switch( $status ) {
	    case '1': {
        if( $substatus=='1' ) return $data_przyjecia.' przyjęto bez sprzeciwu.';
        if( $substatus=='2' ) return $data_przyjecia.' Sejm wysłuchał informacji.';
        if( $substatus=='3' ) {
          if( $typ=='8' ) { return $data_przyjecia.' przyjęto.'; } else {
            return $data_przyjecia.' przyjęto w głosowaniu.';
          }
        }
        if( $substatus=='4' ) return $data_przyjecia.' wykorzystano w pracach nad ustawą budżetową.';
        if( $substatus=='5' ) return $data_przyjecia.' wykorzystano w pracach nad sprawozdaniem z wykonania budżetu.';
        if( $substatus=='6' ) return $data_przyjecia.' zakończono rozpatrywanie.';
	      break;
	    }
	    case '2': {
	      if( $substatus=='1' ) return $data_ostatniego_procedowania.' czytano w komisjach.';
	      if( $substatus=='2' ) return $data_ostatniego_procedowania.' komisja przedstawiła sprawozdanie.';
	      if( $substatus=='3' ) return $data_ostatniego_procedowania.' skierowano do rozpatrywania.';
		    if( $substatus=='4' ) return $data_ostatniego_procedowania.' skierowano do rozpatrywania w komisjach.';
		    if( $substatus=='5' ) return $data_ostatniego_procedowania.' dokument wpłynął, ale nie skierowano go do dalszych prac.';
		    if( $substatus=='6' ) return $data_ostatniego_procedowania.' komisja przedstawiła opinię.';
	      break;
	    }
	    case '3': {
	      if( $substatus=='1' ) return $data_ostatniego_procedowania.' projekt stał się nieaktualny.';
	      if( $substatus=='2' ) return $data_ostatniego_procedowania.' wycofano.';
	      if( $substatus=='3' ) return $data_ostatniego_procedowania.' odrzucono.';
	      break;
	    }
	  }
	
	}
?>