<?
  $id = $_GET['dzien_id'];
  if( strlen($id)==5 ) {
    
    
    
    
    $txt = file_get_contents( ROOT.'/graber_cache/dni/'.$id.'.json' );
    $txt = str_replace('\u0142', 'ł', $txt);
    $txt = str_replace('\u0105', 'ą', $txt);
    $txt = str_replace('\u0144', 'ń', $txt);
    $txt = str_replace('\u0119', 'ę', $txt);
    $txt = str_replace('\u00f3', 'ó', $txt);
    $txt = str_replace('\u0106', 'Ć', $txt);
    $txt = str_replace('\u0107', 'ć', $txt);
    $txt = str_replace('\u017c', 'ż', $txt);
    $txt = str_replace('\u017b', 'Ż', $txt);
    $txt = str_replace('\u015b', 'ś', $txt);
    $txt = str_replace('\u015a', 'Ś', $txt);
    $txt = str_replace('\u0141', 'Ł', $txt);
    $txt = str_replace('\u017a', 'ź', $txt);
    $txt = str_replace('\u0179', 'Ź', $txt);
    
    $txt = str_replace('\u00a0', '&nbsp;', $txt);
    
    
    $txt = str_replace('\u00a7', '§', $txt);    
    $txt = str_replace('\u02dd', "˝", $txt);
    
    
    
    

    
    $model_a = $this->DB->selectAssocs("SELECT typ, autor, text FROM dni_modele WHERE dzien_id='$id' ORDER BY ord ASC");
    $model_b = params_decode( $txt );
    
 
    $this->SMARTY->assign('model_a', $model_a);
    $this->SMARTY->assign('model_b', $model_b);
  
  }   
?>