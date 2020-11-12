<tr><td>&nbsp;</td><td>

<input type=button onClick="javascript:doSubmit();" 
value="{if $data.id}Submit Updates{else}Submit New Listing{/if}">

{if $data.id and $smarty.session.cm_auth.cm_module and $smarty.session.cm_auth.cm_realty and $smarty.session.cm_auth.cm_realty_delete_listings}<input type=button onClick="javascript:deleteItem();" value="Delete Listing">
{/if}

</td></tr>