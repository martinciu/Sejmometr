<?
  require_once(ROOT.'/_lib/jsmin.php');
  
  $page_id = $_PARAMS;
  $page_name = filename($page_id);
  
  $jsOutput = '';
  $cssOutput = '';
  
  // Dołączamy pliki komponentów przypisanych do strony
  $components = $this->DB->selectValues("SELECT ".DB_TABLE_components.".id FROM ".DB_TABLE_pages_components." LEFT JOIN ".DB_TABLE_components." ON ".DB_TABLE_pages_components.".component=".DB_TABLE_components.".id WHERE (".DB_TABLE_pages_components.".page='$page_id' AND (".DB_TABLE_components.".css='1' OR ".DB_TABLE_components.".js='1'))");
  
  foreach($components as $file) {
    $name = filename($file);
    @$cssOutput .= file_get_contents(ROOT.'/_components/'.$file.'/'.$name.'.css');
    @$jsOutput .= file_get_contents(ROOT.'/_components/'.$file.'/'.$name.'.js');
  }
    
  
  // Dołączamy pliki własne stron  
  $jsFile = ROOT.'/_pages/'.$page_id.'/'.$page_name.'.js';
  $cssFile = ROOT.'/_pages/'.$page_id.'/'.$page_name.'.css';
  
  if( file_exists($jsFile) ) { $jsOutput .= file_get_contents($jsFile); }
  if( file_exists($cssFile) ) { $cssOutput .= file_get_contents($cssFile); }
  
  $jsOutput = JSMin::minify($jsOutput);
  $cssOutput = JSMIN::minify_css($cssOutput);
  
  
  list($js_stamp_old, $css_stamp_old) = $this->DB->selectRow("SELECT js_stamp, css_stamp FROM ".DB_TABLE_pages." WHERE id='$page_id'");
  $jsOutput_old = file_get_contents( ROOT.'/js/'.$page_id.'-'.$js_stamp_old.'.js' );
  $cssOutput_old = file_get_contents( ROOT.'/css/'.$page_id.'-'.$css_stamp_old.'.css' );
  
  
  if( $jsOutput!=$jsOutput_old ) {
    echo 'nowy';
    $js_stamp = uniqid();
    force_file_put_contents(ROOT.'/js/'.$page_id.'-'.$js_stamp.'.js', $jsOutput);
    $this->DB->update_assoc( DB_TABLE_pages, array('js_stamp' => $js_stamp), $page_id );
    @unlink(ROOT.'/js/'.$page_id.'-'.$js_stamp_old.'.js');
  } else { echo 'stary'; }
  
  if( $cssOutput!=$cssOutput_old ) {
    $css_stamp = uniqid();
    force_file_put_contents(ROOT.'/css/'.$page_id.'-'.$css_stamp.'.css', $cssOutput);
    $this->DB->update_assoc( DB_TABLE_pages, array('css_stamp' => $css_stamp), $page_id );
    @unlink(ROOT.'/css/'.$page_id.'-'.$css_stamp_old.'.css');
  }
 
?>