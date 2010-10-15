<?
  $id = $_PARAMS['id'];
  $category = $_PARAMS['c'];

  $dokument_problem = $this->DB->selectAssoc("SELECT typ, gid, download_url, plik FROM dokumenty_problemy WHERE id='$id'");
  if( empty($dokument_problem) ) {return array('error'=>'deleted');}
  
  $typ = $dokument_problem['typ'];

  $dokumenty_stare = $this->DB->selectAssocs("SELECT id, gid, scribd_doc_id, scribd_access_key, ilosc_stron FROM dokumenty WHERE typ='".$dokument_problem['typ']."' AND plik='".$dokument_problem['plik']."'");
  if( count($dokumenty_stare)!=1 ) return array('error'=>'1', 'dokumenty_stare'=>$dokumenty_stare);
  
  $dokument_stary = $dokumenty_stare[0];
  
  
  
  $result = array(
    'typ' => $typ,
    'id' => $id,
    'dokument_A' => $dokument_problem,
    'dokument_B' => $dokument_stary,
  );  
    
  
  
  if( $typ=='bas' ) {
  
	  $bas_A = $this->DB->selectAssoc("SELECT dokument_id, tytul FROM bas WHERE id='".$dokument_problem['gid']."'");
	  if( empty($bas_A) ) {
	     
	    $projekty_ids = $this->DB->selectValues("SELECT projekt_id FROM projekty_bas WHERE bas_id='".$dokument_stary['gid']."'");
	    $result['projekty_ids'] = $projekty_ids;
	    
	    $bas_ids = $this->DB->selectRows("SELECT bas.id, bas.dokument_id FROM projekty_bas LEFT JOIN bas ON projekty_bas.bas_id=bas.id WHERE projekty_bas.projekt_id='".$projekty_ids[0]."'");
	    $result['bas_ids'] = $bas_ids;
	  
	  } else {
		  $projekty_A = $this->DB->selectAssocs("SELECT projekty.tytul, druki.numer, projekty.sejm_id FROM projekty_bas LEFT JOIN projekty ON projekty_bas.projekt_id=projekty.id LEFT JOIN druki ON projekty.druk_id=druki.id WHERE projekty_bas.bas_id='".$dokument_problem['gid']."'");
	    $result['bas_A'] = $bas_A;
	    $result['projekty_A'] = $projekty_A;
    }
  }
  
  
  
  return $result;
?>