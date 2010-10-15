<?
  function sf_docthumbs($params){
    $_ls = array('', 'A', 'B', 'C', 'D');
    $id = $params['id'];
    $size = (int) $params['size'];
    
    $result = '<a class="docthumbs_a '.$_ls[$size].'" href="#" onclick="return false;"><img ';
    if( $params['lfloat'] ) $result .= 'class="lfloat" ';
    $result .= 'src="/d/'.$size.'/'.$id.'.gif" /></a>';
    
    return $result;
  }
?>