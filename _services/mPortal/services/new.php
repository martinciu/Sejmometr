<?
  /*
  return codes
  
  1 - service already exists
  2 - service created
  
  */

  if( is_array($_PARAMS) ) {
    $id = $_PARAMS['id'];
    $page = $_PARAMS['page'];
    $access = $_PARAMS['access'];
  } else {
    $id = $_PARAMS;
  }
    
  if( $this->DB->selectCount("SELECT COUNT(*) FROM ".DB_TABLE_services." WHERE (`id`='$id' AND `page`='$page')")===0 ) {
	  $this->DB->insert_ignore_assoc(DB_TABLE_services, array(
	    'id' => $id,
	    'page' => $page,
	  ));  
  }
  
  if( !empty($access) ) {
	  if( $this->DB->selectCount("SELECT COUNT(*) FROM ".DB_TABLE_services_access." WHERE (`service`='$id' AND `page`='$page')")===0 ) {
	    $this->DB->insert_ignore_assoc(DB_TABLE_services_access, array(
		    'service' => $id,
		    'page' => $page,
		    'group' => $access,
	    ));
	  }  
  }
  
  $file = ROOT.'/_services/'.$id.'.php';
  if( !file_exists($file) ) {
    force_file_put_contents($file, file_get_contents(ROOT.'/_lib/mPortal/resources/emptyService.tpl'));
  }
?>