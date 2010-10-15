{section name="MENUGROUPS" loop=$M.MENUGROUPSIDS}
  
  {assign var="GROUPID" value=$M.MENUGROUPSIDS[MENUGROUPS]}
  {assign var="MENU" value=$M.MENU[$GROUPID]}
  
  <div class="_MENU_BLOCK {$M.MENUGROUPS[$GROUPID].1}">
    <h3>{$M.MENUGROUPS[$GROUPID].0}</h3>
        
    <ul>
    {section name="MENUITEM" loop=$MENU}
      <li class="_">
        <a href="/{$MENU[MENUITEM].id}" {if $MENU[MENUITEM].id eq $M.ID}class="selected" {/if}>{$MENU[MENUITEM].label}</a>
        {assign var="SUBMENU" value=$MENU[MENUITEM].SUBMENU}
        {if $SUBMENU}
		      <div class="_SUBMENU {$MENU[MENUITEM].id}" {if $MENU[MENUITEM].id neq $M.ID && $MENU[MENUITEM].id neq $M.SUBMENUS_TABLE[$M.ID]}style="display:none;"{/if}>
		        {section name="SUBMENU" loop=$SUBMENU}
		          <p><a href="/{$SUBMENU[SUBMENU].id}" {if $SUBMENU[SUBMENU].id eq $M.ID}class="selected" {/if}>{$SUBMENU[SUBMENU].label}</a></p>
		        {/section}
		      </div>
        {/if}
      </li>
    {/section}
    </ul>
    
  </div>
{/section}