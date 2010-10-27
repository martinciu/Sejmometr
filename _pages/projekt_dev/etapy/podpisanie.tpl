<p class="tytul">Prezydent podpisał projekt</p>
{if $etap.dokument_id}
<div class="cc">
  {dokument size='d' _params=$etap}
  <div class="ccc">
    {if $etap.data_ogloszenia ne '0000-00-00'}<p>Data opublikowania w Dzienniku Ustaw: {$etap.data_ogloszenia}</p>{/if}
    {if $etap.data_wejscia_w_zycie ne '0000-00-00'}<p>Data wejścia w życie: {$etap.data_wejscia_w_zycie}</p>{/if}
  </div>
</div>
{/if}