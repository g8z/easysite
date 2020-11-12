{literal}
<script language="javascript">
<!--

function checkAll( state ) {
    var theForm = document.forms[0];
    
    for ( i=0; i<theForm.elements.length; i++ ) {
        if ( theForm.elements[i].name.match(/^cm_/) && theForm.elements[i].type=='checkbox' ) {
            theForm.elements[i].checked = state;
        }
    }
}

-->
</script>
{/literal}

<table border=0 cellpadding=2 cellspacing=0 class=normal>
<input type=hidden name="start_permissions" value=1>

{foreach key=type item=resource from=$resources}

<tr><td colspan=1 nowrap><br />

{if $type eq 'form'}
    <b>Forms</b>
{elseif $type eq 'form_section'}
    <b>Form Sections and Fields</b>
{elseif $type eq 'page'}
    <b>Pages</b>
{elseif $type eq 'page_section'}
    <b>Page Sections or Paragraphs</b>
{elseif $type eq 'layer'}
    <b>Viewing Layers</b>
{elseif $type eq 'menu'}
    <b>Whole Menus</b>
{elseif $type eq 'menu_item'}
    <b>Single Menu Items</b>
{elseif $type eq 'file'}
    <b>Uploaded Files</b>
{else}
    <b>Content Management Tools</b><br />
    [ <a href="#" onclick="javascript: checkAll(true); return false;">check all</a> ]    [ <a href="#" onclick="javascript: checkAll(false); return false;">uncheck all</a> ]{/if}

</td></tr>

    {assign var=resourceExists value=false}

    {foreach item=resourceItem from=$resource}
        <tr>

        <td valign=top align=left>
        {section name=indent loop=$resourceItem.indent}
        &nbsp;&nbsp;&nbsp;&nbsp;
        {/section}

        {if $resourceItem.id ne 'comment'}
        <input type=checkbox value=1 name={$type}_{$resourceItem.id}
        {if $resourceItem.restricted eq 1}checked{/if}>
        {/if}

            {$resourceItem.title|default:"(no title)"}
        </td>
        </tr>

        {assign var=resourceExists value=true}
    {/foreach}

    {if $resourceExists eq false}
    <tr><td colspan=2>(none currently available)</td></tr>
    {/if}

{/foreach}

</table>
<input type=hidden name="end_permissions" value=1>

