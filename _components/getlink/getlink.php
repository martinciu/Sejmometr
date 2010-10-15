<?
  function sf_getlink($params){
    $vars = array();
    $mastervars = array_unique( array_merge($_GET, $params) );
    
    $reset_vars = explode(',', $params['_reset']);
    
    if( is_array($mastervars) ) foreach( $mastervars as $key=>$value ) {
      if( $key[0]!='_' && !in_array($key, $reset_vars) ) $vars[] = $key.'='.$value;
    }
    
    $result = $_SERVER['SCRIPT_URL'];
    if( !empty($vars) ) $result .= '?'.implode('&', $vars);
    
    return $result;   
  }
?>