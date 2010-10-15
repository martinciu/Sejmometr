<?
  require_once(ROOT.'/_lib/jsmin.php');
  
  
  
  // ENGINE CSS
  $stamp = uniqid();   
  $output = @JSMIN::minify_css( file_get_contents(ROOT.'/cssLibs/engine.css') );
  
  $stamp_old = $this->DB->selectValue("SELECT stamp FROM ".DB_TABLE_files_stamps." WHERE `file`='engine' AND `ext`='css'");
  $output_old = @file_get_contents( ROOT.'/cssLibs/engine-'.$stamp_old.'.css' );
  
  if( $output!=$output_old ){
	  $this->DB->q("INSERT INTO ".DB_TABLE_files_stamps." (`file`, `ext`, `stamp`) VALUES ('engine', 'css', '$stamp') ON DUPLICATE KEY UPDATE `stamp`='$stamp'");
	  $result['engine-css'] = force_file_put_contents(ROOT.'/cssLibs/engine-'.$stamp.'.css', $output);
  }
  
  
  
  // ENGINE JS
  $stamp = uniqid();
  $output = '';
  foreach(array('/jsLibs/prototype.js', '/jsLibs/effects.js', '/jsLibs/builder.js', '/jsLibs/controls.js', '/jsLibs/dragdrop.js', '/jsLibs/scribd.js', '/jsLibs/engine.js') as $file) $output .= @JSMIN::minify( file_get_contents(ROOT.$file) );
  
  $stamp_old = $this->DB->selectValue("SELECT stamp FROM ".DB_TABLE_files_stamps." WHERE `file`='engine' AND `ext`='js'");
  $output_old = @file_get_contents( ROOT.'/jsLibs/engine-'.$stamp_old.'.js' );
  
  if( $output!=$output_old ){
	  $this->DB->q("INSERT INTO ".DB_TABLE_files_stamps." (`file`, `ext`, `stamp`) VALUES ('engine', 'js', '$stamp') ON DUPLICATE KEY UPDATE `stamp`='$stamp'");
	  $result['engine-js'] = force_file_put_contents(ROOT.'/jsLibs/engine-'.$stamp.'.js', $output);
  }
  
  
  
  // ENGINE ADMIN CSS
  $stamp = uniqid();   
  $output = @JSMIN::minify_css( file_get_contents(ROOT.'/cssLibs/engine-admin.css') );
  
  $stamp_old = $this->DB->selectValue("SELECT stamp FROM ".DB_TABLE_files_stamps." WHERE `file`='engine_admin' AND `ext`='css'");
  $output_old = @file_get_contents( ROOT.'/cssLibs/engine-admin-'.$stamp_old.'.css' );
  
  if( $output!=$output_old ){
	  $this->DB->q("INSERT INTO ".DB_TABLE_files_stamps." (`file`, `ext`, `stamp`) VALUES ('engine_admin', 'css', '$stamp') ON DUPLICATE KEY UPDATE `stamp`='$stamp'");
	  $result['engine-admin-css'] = force_file_put_contents(ROOT.'/cssLibs/engine-admin-'.$stamp.'.css', $output);
  }
  
  
  
  // ENGINE ADMIN JS
  $stamp = uniqid();   
  $output = @JSMIN::minify( file_get_contents(ROOT.'/jsLibs/engine-admin.js') );
  
  $stamp_old = $this->DB->selectValue("SELECT stamp FROM ".DB_TABLE_files_stamps." WHERE `file`='engine_admin' AND `ext`='js'");
  $output_old = @file_get_contents( ROOT.'/jsLibs/engine-admin-'.$stamp_old.'.js' );
  
  if( $output!=$output_old ){
	  $this->DB->q("INSERT INTO ".DB_TABLE_files_stamps." (`file`, `ext`, `stamp`) VALUES ('engine_admin', 'js', '$stamp') ON DUPLICATE KEY UPDATE `stamp`='$stamp'");
	  $result['engine-admin-js'] = force_file_put_contents(ROOT.'/jsLibs/engine-admin-'.$stamp.'.js', $output);
  }
  
  
  
  return $result;
?>