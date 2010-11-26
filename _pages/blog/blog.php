<?
  $this->addLib('google');
  $posty = $this->S('blog/lista');
  
  $glowny_post_id = is_numeric($_GET['_ID']) ? $_GET['_ID'] : $posty[0]['id'];  
  $post = $this->S('blog/post', $glowny_post_id);
    
  $this->SMARTY->assign('glowny_post', $post);
  $this->SMARTY->assign('posty', $posty);
  $this->SMARTY->assign('can_manage', ($this->USER['group']==2 || $this->USER['group']==3));
  
  $this->TITLE = $post['tytul'];
  $this->FRONT_MENU_SELECTED = 'oportalu';
?>