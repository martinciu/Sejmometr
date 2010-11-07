<?
  function params_decode($p){
    $p = json_decode( stripslashes($p) );
    convert_objects_to_arrays($p);
    return $p;
  }
  
  function convert_objects_to_arrays(&$p){
    if( is_object($p) ) $p = get_object_vars($p);
    if( is_array($p) ) foreach( $p as $k=>&$v ) { convert_objects_to_arrays($v); }
  }
  
  function filename($path){
    $pathparts = pathinfo($path);
    return $pathparts['filename'];
  }
  
  function sm_dopelniacz($count=0,$formA='',$formB='',$formC='',$formD=''){
	  if( $count==0) {return $formD;}
	  elseif( $count==1 ) {
	    $r = $formA;
	  } elseif( $count<5 ) {
	    $r = $formB;
	  } elseif( $count<22) {
	    $r = $formC;
	  } else {
	    $d = $count % 10;
	    if( $d<2 ) { $r = $formC; }
	    elseif( $d<5 ) { $r = $formB; }
	    else { $r = $formC; }
	  }
	  return $count.'&nbsp;'.$r;
	}
	
	function sm_dataWzglednaEngine($data, $params=null){
		if( !empty($data) ) {
	    $params = (int) $params;
	    list($y,$m,$d) = explode('-',$data);
	    $dni = round( ( mktime(0, 0, 0, date('n'), date('j'), date('Y')) - mktime(0, 0, 0, $m, $d, $y) ) / 86400 );
	    if( $dni>30 && $params!=1) {
	      $miesiace = array(
	        '01'=>'stycznia',
	        '02'=>'lutego',
	        '03'=>'marca',
	        '04'=>'kwietnia',
	        '05'=>'maja',
	        '06'=>'czerwca',
	        '07'=>'lipca',
	        '08'=>'sierpnia',
	        '09'=>'września',
	        '10'=>'października',
	        '11'=>'listopada',
	        '12'=>'grudnia',
	      );
	      if( $d[0]=='0' ) { $d=$d[1]; }
	      return array($d.' '.$miesiace[$m].' '.$y, false);
	    }
	    elseif ( $dni==0 ) { return array('dzisiaj', false); }
	    elseif ( $dni==1 ) { return array('wczoraj', false); }
	    else { return array($dni.' dni', true); }
	    /*
	    elseif ( $dni<30 ) {
	      $r = floor($dni/7);
	      if( $r==1 ) { return 'tydzień temu'; }
	      else { return $r.' tygodnie temu'; }
	    } elseif ( $dni<45 ) { return 'miesiąc temu'; }
	    else{ return '2 miesiące temu'; }
	    */
	  }
	}
	
	function array_search_assoc_value($array, $keyname){
	  if(is_array($array)) foreach( $array as $item ) {
	    if( $item['name']==$keyname ) { echo $item['value']; }
	  }
	}
	
	function minify_html($html){
	  $search = array("/\/\/ .*/", "/(\s){2,}/");
	  $replace = array('', '$1');
	  return preg_replace($search, $replace, $html);
	}
	
	function assoc_search($array, $key, $value, $field=null){
	  foreach($array as $i=>$a){
		  if( $a[$key]==$value ){
		    return $field ? $a[$field] : $a;
		  }
		}
	}
	
	function assoc_search_key($array, $key, $value){
	  foreach($array as $i=>$a){
		  if( $a[$key]==$value ){
		    return $i;
		  }
		}
	}
	
	function dir_exists($dir) {
	  return file_exists($dir) && is_dir($dir);
	}
	
	function _urlencode($url){
	  return str_replace(' ', '%20', $url);
	}
	
	function add_include_path($path){
	  set_include_path(get_include_path().PATH_SEPARATOR.$path);
	}
	
	function force_mkdir($path){	
	  $folders = explode('/', $path);
	  $_path = '';
	  if( is_array($folders) ) foreach($folders as $f) {
	    if( $f!='' && $f!='/' ) {
	      $_path .= '/'.$f;
	      if( !dir_exists($_path) ) { mkdir($_path); }
	    }
	  }	  
	}
	
	function recurse_copy($src, $dst) {
	  $iterator++;
    $dir = opendir($src);
    @mkdir($dst);
    while(false !== ( $file = readdir($dir)) ) {
      if (( $file != '.' ) && ( $file != '..' )) {
        if ( is_dir($src . '/' . $file) ) {
          recurse_copy($src.'/'.$file, $dst.'/'.$file);
        } else {
          @copy($src.'/'.$file, $dst.'/'.$file);
        }
      }
    }
    closedir($dir);
  }
  
  function recurse_rmdir($path){
    $dir = opendir($path);
    while(false !== ( $file = readdir($dir)) ) {
	    if (( $file != '.') && ($file!='..')) {
	      if( is_dir($path.'/'.$file) ) { recurse_rmdir($path.'/'.$file); } else { @unlink($path.'/'.$file); }
	    }
    }
    rmdir($path);
  }
  
  function force_file_put_contents($filename, $content){
    // @unlink($filename);
    $pathinfo = pathinfo( $filename );
    force_mkdir( $pathinfo['dirname'] );
    file_put_contents($filename, $content);
  }
  
  function arrays_equal( $array_A, $array_B ) {
    if( is_array($array_A) && is_array($array_B) && count($array_A)==count($array_B) ) {
      
      asort($array_A);
      asort($array_B);
      $array_A = array_values($array_A);
      $array_B = array_values($array_B);
      
      for( $i=0; $i<count($array_A); $i++ ) {
        if( $array_A[$i]!=$array_B[$i] ) return false;
      }
      return true;
      
    } else return false;
  }
  
  function translate_polish_letters($s){
    return strtr($s, array(
      'ę' => 'e',
      'ó' => 'o',
      'ą' => 'a',
      'ś' => 's',
      'ł' => 'l',
      'ż' => 'z',
      'ź' => 'z',
      'ć' => 'c',
      'ń' => 'n',
      'Ę' => 'E',
      'Ó' => 'O',
      'Ą' => 'A',
      'Ś' => 'S',
      'Ł' => 'L',
      'Ż' => 'Z',
      'Ź' => 'Z',
      'Ć' => 'C',
      'Ń' => 'N',
    ));
  }
?>