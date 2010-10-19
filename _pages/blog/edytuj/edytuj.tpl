<div class="_BOX">
	<h1 class="_BOX_HEADER">{$box_title}</h1>
	<p class="_SHADOW _WHITE_TOP">&nbsp;</p>
	<div class="_INNER">
	
	  <div id="formDiv"><form id="form" onsubmit="return false">
	    
	    {if $post.id}<input type="hidden" name="id" value="{$post.id}"/>{/if}
	    
	    <div class="f">
		    <h2>Tytuł:</h2>
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
	
	</div>
	<p class="_SHADOW _WHITE_BOTTOM">&nbsp;</p>
	<p class="_BOX_FOOTER">
	  <a id="btnAnuluj" href="#" onclick="return false;" class="_BTN orange">Anuluj</a><a id="btnPublikuj" href="#" onclick="return false;" class="_BTN orange">Publikuj</a>
	</p>
</div>