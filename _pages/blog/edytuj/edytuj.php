<?
  if( is_numeric($_GET['_ID']) ) $id = $_GET['_ID'];
  
  $box_title = $id ? 'Edycja postu' : 'Nowy post';
  
  if( $id ) {
    $this->assignService('post', 'blog/post', $id);
  } 
  
  $this->SMARTY->assign('box_title', $box_title );
  $this->TITLE = $box_title;
?>