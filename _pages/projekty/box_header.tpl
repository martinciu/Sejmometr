<div>
  <h1><a href="/{$smarty.get._TYPE}">{$M.TITLE}</a></h1>
  <h2>Sortowanie:</h2>
  {html_options id="sortowanie_select" name="sortowanie" options=$sortowanie_opcje selected=$sortowanie_wybrane}
</div>
<p class="total">{$paginacja.elementy_start}-{$paginacja.elementy_koniec} z {$paginacja.total}</span>