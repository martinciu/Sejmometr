<div id="formDiv"><form id="form" onsubmit="return false">
  
  {if $post.id}<input type="hidden" name="id" value="{$post.id}"/>{/if}
  
  <div class="f first">
    <h2 class="first">Tytuł:</h2>
    <textarea rows="1" name="tytul">{$post.tytul}</textarea>
  </div>
  
  <div class="f">
    <h2>Opis:</h2>
    <textarea rows="3" name="opis">{$post.opis}</textarea>
  </div>
  
  <div class="f">
    <h2>Treść:</h2>
	  <textarea class="ckeditor" id="editor" name="tresc" rows="10">{$post.tresc}</textarea>
  </div>
  
</form></div>

<p class="admin_buttons">
  <a id="btnAnuluj" href="#" onclick="return false;" class="_BTN orange">Anuluj</a><a id="btnPublikuj" href="#" onclick="return false;" class="_BTN orange">Publikuj</a>
</p>
