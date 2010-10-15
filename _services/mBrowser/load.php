<?
  $table = $_PARAMS['table'];
  $loader = $_PARAMS['loader'];
  $category = $_PARAMS['category'];
	$controll_data = $_PARAMS['controll_data'];
	$controll_data[2] = 0;
  list($page, $per_page) = $controll_data;
  $limit_start = $page*$per_page;
	$data = array();
	
  if(!empty($loader)) {
  
    // LOADER LOAD
    @include( ROOT.'/_services/mBrowser/loaders/'.$loader.'.php' );
    if( !is_array($data) ) $data = array();
    
  } else if(!empty($table)) {
    
    // TABLE LOAD
    $fields = $_PARAMS['fields'];
	  $where = $_PARAMS['where'];  
	  $fields = is_array($fields) ? "`id`, `".implode("`, `", $fields)."`" : '*';
	  $where = empty($where) ? '1' : $where;
	  
	  $q = 'SELECT SQL_CALC_FOUND_ROWS '.$fields.' FROM `'.$table.'` WHERE '.$where.' LIMIT '.$limit_start.', '.$per_page;
	  $data = $this->DB->selectAssocs($q);	  
  }
  
  $controll_data[2] = $this->DB->selectCount("SELECT FOUND_ROWS()");
  return array($controll_data, $data);
?>