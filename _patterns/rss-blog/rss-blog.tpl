<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0">
	<channel>

		<title>Sejmometr.pl - Blog</title>
		<description>To jest oficjalny blog redakcji portalu Sejmometr</description>
		<link>http://sejmometr.pl/rss/blog</link>
		<language>pl</language>
		<copyright>Fundacja ePaństwo</copyright>
		
		<image>
		   <url>http://sejmometr.pl/g/logo_55.jpg</url>
		   <width>68</width>
		   <height>55</height>
		   <link>http://www.sejmometr.pl/</link>
		   <title>Sejmometr</title>
		</image>
		
		{section name="items" loop=$items}{assign var="item" value=$items[items]}
		<item>
		  <title>{$item.tytul|cdata}</title>
		  <link>http://sejmometr.pl/blog/{$item.id},{$item.url_title}</link>
		  <description>{$item.opis|cdata}</description>
		  <guid>http://sejmometr.pl/blog/{$item.id},{$item.url_title}</guid>
	  </item>
    {/section}
      
  </channel>
</rss>