<script language="javascript">
<!--

function showImage( src ) {ldelim}
    var popup = MyLaunchCentered('',400,300,'scrollbars,resizable');
    popup.document.write( '<html><body style="margin:0px">' );
    popup.document.write( '<div align="center"><a href="java'+'script: window.close();">Close Window</a></div>' ); 
    popup.document.write( '<img src="'+src+'">' ); 
    popup.document.write( '</body></html>' );
    popup.document.close();
{rdelim}
-->
</script>

   
    <tr><td class=title>Report for {$formName}</td></tr>
    
    {if $numSubmissions gt 0}
    <tr><td class=normal><a onClick="return confirm('Are you sure? This will permanently delete the form data!' );" href=?form_id={$smarty.get.form_id}&clear=1>Reset Submission Data</a>
    |
    <a href="javascript:close();">Close</a>
    </td></tr>
    {/if}

    {if $numSubmissions gt 0}
    
    {if $settings.smarty_date}
    	{assign var=dateFormat value=$settings.smarty_date}
    {else}
    	{assign var=dateFormat value="%m/%d/%y"}
    {/if}
    
    <tr><td>
    
	    <table border=0 cellpadding=3 cellspacing=1 width=100% class=normal>
	    
	    {* print the headers *}
	    
	    <tr>
	    {foreach item=header from=$headers}
	    <td valign=bottom><b>{$header.label}</b></td>
	    {/foreach}
	    </tr>
	    
	    {* print the form submission data *}
	    
	    
	    {foreach key=id name=iterator item=row from=$data}
	    
	    {if $smarty.foreach.iterator.iteration is odd}
	    	 {assign var=rowColor value=$settings.oddRowColor}
	   	 {assign var=defaultRowColor value='#EEEEFF'}
	    {else}
	   	 {assign var=rowColor value=$settings.evenRowColor}
	   	 {assign var=defaultRowColor value='#CCCCCC'}
	    {/if}
	    
	    <tr bgcolor="{$rowColor|default:$defaultRowColor}">
	    
	    {foreach name=column item=cell from=$row}
	    
	    {* check for special cases: files & images *}
	    
	    {math equation="x-1" x=$smarty.foreach.column.iteration assign=index}
	    
	    {if $headers[$index].field_type eq 'file'}
	    
	    	<td>{$cell}</td>
	    	
	    {elseif $headers[$index].field_type eq 'image'}

	    	<td>{$cell}</td>
	    
	    {else}
	    	<td>{$cell|nl2br}</td>
	    
	    {/if}
	    
	    
	    
	    {/foreach}
	    </tr>
	    {/foreach}
	    
	    </table>

    </td></tr>
    {else}
    <tr><td class=normal>There are no submissions yet for this form, or the submissions have been cleared.</td></tr>
    {/if}

