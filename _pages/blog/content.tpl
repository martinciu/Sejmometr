<div id="content">

	<div id="glowny_post">
		<h1 class="first"><a href="/blog/{$glowny_post.id},{$glowny_post.url_title}">{$glowny_post.tytul}</a></h1>
		{if $can_manage}<p class="_admin_hidden admin_buttons" style="display: none;">
		  <a href="/blog/edytuj/{$glowny_post.id}" class="_BTN orange">Edytuj</a> <a id="btnUsun" href="#" onclick="usun_post({$glowny_post.id}); return false;" class="_BTN orange">Usuń</a>
		</p>{/if}
		<p class="info">{$glowny_post.data_zapisu|data_slowna}</p>
		{if $glowny_post.image}<img class="baner" src="/resources/blog/{$glowny_post.image}.jpg">{/if}
		<div class="tresc">{$glowny_post.tresc}</div>
	</div>
	
	<div id="fb-root"></div><script src="http://connect.facebook.net/en_US/all.js#appId=144885868891893&amp;xfbml=1"></script><fb:comments xid="blog_{$glowny_post.id}" width="550"></fb:comments>

</div>
<div id="side_div">

  <div id="blog_info">
	  <img id="blog_logo" src="/g/logo_55.jpg" />
	  <p class="tytul">To jest oficjalny blog redakcji portalu Sejmometr</p>
	  {if $can_manage}<p class="_admin_hidden nowy_post_p" style="display: none;">
		  <a href="/blog/nowy_post" class="_BTN orange">Nowy post</a>
		</p>{/if}
  </div>
	
	<div id="blog_tools">
	  <a href="#" onclick="return false;">Subskrybuj przez RSS</a>
	</div>
  
  <div id="ostatnie_wpisy">
    <h2 class="first">Ostatnie wpisy:</h2>
    {section name="posty" loop=$posty}{assign var="post" value=$posty[posty]}
      {if $post.id ne $glowny_post.id}
	      <div class="post">
	        <p class="data">{$post.data|data_slowna}</p>
	        <p class="tytul"><a href="/blog/{$post.id},{$post.url_title}">{$post.tytul}</a></p>
	      </div>
      {/if}
    {/section}
  </div>
  
</div>