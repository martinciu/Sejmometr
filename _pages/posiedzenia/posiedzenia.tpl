{assign var="month" value=$cal_data.month}

<div class="_BOX _HORIZONTAL">
  <h1 class="_BOX_HEADER"><a href="/posiedzenia">Posiedzenia Sejmu</a></h1>
	<div class="_MAIN">
	  <div class="_TOP">
	  
	    <div id="cal">
			  <h1>{$_miesiace.$month} {$cal_data.year}</h1>
			  <div class="nav">
			    <p class="left"><a id="cal_link_prev" href="#" onclick="return false;">&laquo;</a></p>
			    <p class="right"><a id="cal_link_next" href="#" onclick="return false;">&raquo;</a></p>
			  </div>
			  <table cellpadding="0" cellspacing="0"></table>
			  <div id="_t"></div>
			</div>
	  
	  </div>
	  <div class="_BOTTOM">
	    <h2>Posiedzenie {$posiedzenie.numer}</h2>
	    {include file="plan.tpl"}
	  </div>
	</div>
	<div class="_BOX_FOOTER">&nbsp;</div>
</div>


