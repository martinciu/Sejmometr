<div class="_BOX">
	<table class="_BOX_TABLE" cellpadding="0" cellspacing="0" border="0">
	  <tr class="_SHADOW">
	    <td class="_SHADOW _GREY_TOP">&nbsp;</td>
	    <td class="_SHADOW _WHITE_TOP">&nbsp;</td>
	  </tr>
	  <tr class="_MAIN_TR">
	    <td class="_LEFT_TD">
	      
	      <p id="oportalu_menu">
		      <a href="/blog" class="selected">Blog</a>
	        <a href="/faq">FAQ</a>
		      <a href="/blog">Nota prawna</a>
	      </p>
	      
	    </td>
	    <td class="_RIGHT_TD oportalu_main_td">
	    
	    <div id="glowny_post">
				<h2>{$glowny_post.tytul}</h2>
				{if $can_manage}<p class="admin_buttons">
				  <a href="/blog/edytuj/{$glowny_post.id}" class="_BTN orange">Edytuj</a> <a id="btnUsun" href="#" onclick="usun_post({$glowny_post.id}); return false;" class="_BTN orange">Usuń</a>
				</p>{/if}
				<p class="info"><span class="a">{$glowny_post.autor}</span>, {$glowny_post.data_zapisu}</p>
				<div class="opis">{$glowny_post.opis}</div>
				<div class="tresc">{$glowny_post.tresc}</div>
			</div>
	    
	    </td>
	  </tr>
	  <tr class="_SHADOW">
	    <td class="_SHADOW _GREY_BOTTOM">&nbsp;</td>
	    <td class="_SHADOW _WHITE_BOTTOM">&nbsp;</td>
	  </tr>
	</table>
</div>