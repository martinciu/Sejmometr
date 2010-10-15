<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    {section name="META" loop=$M.META}
      <META NAME="{$M.META[META][0]}" CONTENT="{$M.META[META][1]|escape}">
    {/section}
    
    <title>{$M.TITLE|escape} {if $M.TITLE}| {/if}{$M.DEFAULT_PAGE_TITLE}</title>
    <head profile="http://www.w3.org/2005/10/profile">
    <link rel="icon" type="image/png" href="/g/icon.png" />
    
    
    <link rel="stylesheet" href="/csslibs/engine-{$M.STAMPS.engine_css}.css" type="text/css">
    
    {if $M.USER.group eq 2 && $M.LAYOUT eq "general"}
      <link rel="stylesheet" href="/csslibs/engine-admin{if $smarty.request.DONT_MINIFY neq 1}-{$M.STAMPS.engine_admin_css}{/if}.css" type="text/css">
    {/if}
    
	  {section name=sheets loop=$M.CSSLIBS}<link rel="stylesheet" href="/csslibs/{$M.CSSLIBS[sheets]}" type="text/css">{/section}
    {if $M.cssFile}<link rel="stylesheet" href="/css/{$M.ID}-{$M.STAMPS.css}.css" type="text/css">{/if}
    {if $M.cssInline}<style>{include file=$M.NAME|cat:"-inline.css"}</style>{/if}
    
    <!--[if IE 7]>
		<link rel="stylesheet" href="/css/ie7.css" type="text/css" />		
		<![endif]-->
    <!--[if IE 8]>
		<link rel="stylesheet" href="/css/ie8.css" type="text/css" />		
		<![endif]-->
    
    <script type="text/javascript" src="/jslibs/engine-{$M.STAMPS.engine_js}.js"></script>
    {if $smarty.request.dev eq 1}<script type="text/javascript" src="/jslibs/engine-dev.js"></script>{/if}
    
    {if $M.USER.group eq 2}
      <script type="text/javascript" src="/jslibs/engine-admin{if $smarty.request.DONT_MINIFY neq 1}-{$M.STAMPS.engine_admin_js}{/if}.js"></script>
    {/if}
    
    {section name=scripts loop=$M.JSLIBS}<script type="text/javascript" src="/jsLibs/{$M.JSLIBS[scripts]}"></script>{/section}
    {if $M.jsFile}<script type="text/javascript" src="/js/{$M.ID}-{$M.STAMPS.js}.js"></script>{/if}
  </head>
  
  <body>
    {literal}
	  <!--[if IE 6]>
		<script type="text/javascript"> 
			/*Load jQuery if not already loaded*/ if(typeof jQuery == 'undefined'){ document.write("<script type=\"text/javascript\"   src=\"http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js\"></"+"script>"); var __noconflict = true; } 
			var IE6UPDATE_OPTIONS = {
				icons_path: "/ie6update/images/"
			}
		</script>
		<script type="text/javascript" src="/ie6update/ie6update.js"></script>
		<![endif]-->
		{/literal}
    <div id="_OVERLAY" style="display: none;"></div>
    <div id="_LIGHTBOXES"></div>
    
    {include file=$M.ROOT|cat:"/_layout/"|cat:$M.LAYOUT|cat:".tpl"}
        
    <script type="text/javascript">
      var CURRENT_PAGE = '{$M.ID}';
			var _PAGEDATA = {ldelim}'ID':'{$M.ID}', 'NAME':'{$M.NAME}'{rdelim};
			{if $M.jsInline}{include file=$M.NAME|cat:"-inline.js"}{/if}
			{literal}
			var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
			document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
	  </script>
		<script type="text/javascript">try { var pageTracker = _gat._getTracker("UA-3303173-6"); pageTracker._trackPageview(); } catch(err) {}</script>
		  {/literal}
  </body>
</html>    
    
    
    