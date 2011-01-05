<?
  function sf_dokument($params){
    $_sizes = array('a', 'b', 'c', 'd', 'e', 'f');
    $_mode = $params['mode'] ? $params['mode'] : 'scribd';
    
    $size = $params['size'];
    $_size = array_search($size, $_sizes);
    
    if( is_array($params['_params']) ) $params = $params['_params'];
    
    if( !$params['dokument_id'] ) return '';
    
    switch( $_mode ) {
      case 'scribd': {
        $result = '<a doc_id="'.$params['scribd_doc_id'].'" doc_key="'.$params['scribd_access_key'].'" title="'.$params['title'].'" class="dokument reader '.$size.'" href="#" onclick="return false;"><img class="d" src="/d/'.$_size.'/'.$params['dokument_id'].'.gif" /><p class="overlay_bg">&nbsp;</p><div class="overlay"><img class="lupa" src="/g/p.gif" />';
        if( $size!='e' ) $result .= '<p class="str">'.$params['ilosc_stron'].' str</p>';
        $result .= '</div></a>';
        break;
      }
      case 'file': {
        $result = '<a class="dokument '.$size.'" href="'.$params['download_url'].'" target="_blank"><img class="d" src="/d/'.$_size.'/'.$params['dokument_id'].'.gif" /><p class="overlay_bg">&nbsp;</p><div class="overlay"><img class="lupa" src="/g/p.gif" /><p class="str">'.$params['ilosc_stron'].' str</p></div></a>';
      }
    }
    
    
    return $result;
  }
?>