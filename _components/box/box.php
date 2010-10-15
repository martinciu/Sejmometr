<?
  function sf_box($params, &$smarty){
    $type = $params['type'];
    $title = $params['title'];
    
    $parts = array(
      'header' => 'box_header',
      'left' => 'box_left',
      'right' => 'box_right',
      'top' => 'box_top',
      'bottom' => 'box_bottom',
      'footer' => 'box_footer',
    );
    
    foreach( $parts as $k=>&$v ) {
      $file = $smarty->template_dir.$v.'.tpl';
      $v = file_exists($file) ? $smarty->fetch($file) : '&nbsp;';
    }
    
    if( $title ) $parts['header'] = $title;
    
    if( $type=='vertical' ) {
             
	    echo '<div class="_BOX _VERTICAL"><div class="_BOX_HEADER">'.$parts['header'].'</div>
	<table class="_BOX_TABLE" cellpadding="0" cellspacing="0" border="0">
	  <tr class="_SHADOW">
	    <td class="_SHADOW _GREY_TOP">&nbsp;</td>
	    <td class="_SHADOW _WHITE_TOP">&nbsp;</td>
	  </tr>
	  <tr class="_MAIN_TR">
	    <td class="_LEFT_TD">
	      '.$parts['left'].'
	    </td>
	    <td class="_RIGHT_TD">
	      '.$parts['right'].'
	    </td>
	  </tr>
	  <tr class="_SHADOW">
	    <td class="_SHADOW _GREY_BOTTOM">&nbsp;</td>
	    <td class="_SHADOW _WHITE_BOTTOM">&nbsp;</td>
	  </tr>
	</table>
	<div class="_BOX_FOOTER">
	  '.$parts['footer'].'
	</div></div>';
    
    } elseif( $type=='horizontal' ) {
    
      echo '<div class="_BOX _HORIZONTAL"><h1 class="_BOX_HEADER">'.$parts['header'].'</h1>
	<div class="_LEFT">'.$parts['left'].'</div>
	<div class="_MAIN">
	  <div class="_TOP">'.$parts['top'].'</div>
	  <div class="_BOTTOM">'.$parts['bottom'].'</div>
	</div>
	<div class="_BOX_FOOTER">
	  '.$parts['footer'].'
	</div></div>';
    
    }
  
  }
?>