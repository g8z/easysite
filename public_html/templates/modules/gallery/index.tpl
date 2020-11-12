{if $smarty.session.cm_auth.cm_module}
{literal}
<script language="javascript">
<!--

function submitLink( action ) {
    document.mainForm.galleryAction.value = action;   
    document.mainForm.submit();
    return false;
}

function selectAll( bool ) {
    
    var el = document.mainForm.elements;
    var length = el.length;

    for ( i=0; i<length; i++) {
        if ( el[i].name.substr(0, 2) == 'ch' ) {
            el[i].checked = bool;
        }
    }
}

-->
</script>
{/literal}

{/if}


{if $smarty.session.cm_auth.cm_module and $smarty.session.cm_auth.cm_gallery}
<p class=normal>
[ <a href="manage/index.php">Gallery Management</a> ] 
[ <a href='manage/editDisplayOptions.php'" title="">Display Options</a> ] <br />
[ <a href='#' onclick="javascript: selectAll( true ); return false;" title="Select All">Select All</a> ] 
[ <a href='#' onclick="javascript: selectAll( false ); return false;" title="Un-select All">Un-select All</a> ] 
[ <a href='#' onclick="javascript: if ( confirm( 'Do you really want to delete the selected items?') ) submitLink( 'delete_selected' ); else return false;" title="Delete Selected">Delete Selected</a> ] 
</p>
{/if}

{if $gallery.showHeader|default:'yes' eq 'yes' || $gallery.useEcommerce eq 'yes'}
<p>
<table class="normal" width="100%" cellpadding="0" cellspacing="0">
<tr><td align="left" class="subtitle">{if $gallery.showHeader|default:'yes' eq 'yes'}{$pageHeader|default:"Image Gallery"}{else}&nbsp;{/if}</td>
{if $gallery.useEcommerce eq 'yes'}
	<td align="right">{$cartLabel}</td>
{/if}
</tr>
</table>
</p>

{/if}

{if $gallery.showPath|default:"yes" eq 'yes'}
<p class="normal">{$galleryPath}</p>
{/if}


{if $gallery.showPagination|default:"yes" eq 'yes'}<span class=normal>{$navigation}</span>{/if}


{if $smarty.session.cm_auth.cm_module and $smarty.session.cm_auth.cm_gallery}
    <form method=post name=mainForm>

    {foreach from=$prevPost item=post}
    <input type=hidden name="{$post.name}" value="{$post.value}">
    {/foreach}
    
    <input type=hidden name=galleryAction value=''>
{/if} 

{if $empty}
    <p class=normal>{$gallery.emptyCategoryMessage|default:"There are currently no items or categories in the store."}<br /><br />

    {if $smarty.session.cm_auth.cm_module and $smarty.session.cm_auth.cm_gallery}
    To add items, login to the content-management tools, and choose the <a href='{$docroot}modules/gallery/manage/index.php'>gallery module</a>
    {/if}

    </p>
    </span>
{/if}
    {* Displying thumbnail table *}
    <table align=center cellspacing="{$gallery.tableBetweenSize|default:'1'}" cellpadding=2 width="{$gallery.gridTableWidth|default:'100%'}" style="border: {$gallery.tableBorderSize}px solid {$gallery.tableBorderColor}" {if $gallery.tableBetweenColor}bgcolor="{$gallery.tableBetweenColor}"{/if}>
    {section loop=$rows name=row}
        <tr>
            {section loop=$cols name=col}
                <td align=center {if $gallery.tableBGColor}bgcolor="{$gallery.tableBGColor}"{/if} width="{$equalWidth}%" valign="{$gallery.thumbVAlign|default:'middle'}">
                	
            		<table class=normal border=0 width="{$gallery.thumbTableWidth|default:'100%'}" align="center">
            		
					{foreach item=row from=$images[row][col].fields.top}
					<tr><td>
						<table class=normal width="100%" height="100%"><tr>
						{foreach item=field from=$row}
						{if $field|is_array}
							<td align="{$field.align}" class="{$field.style|default:'normal'}">
							{$field.title}
							</td>
						{/if}
						{/foreach}
						</tr></table>
					</td></tr>
					{/foreach}          

            		
            		<tr><td align="center" width="100%">
	                	{if $images[row][col].path}
						<table class="normal" border=0 width="100%">
						<tr>
							{if $images[row][col].fields.left}
							<td>
							<table class="normal">
							{foreach item=row from=$images[row][col].fields.left}
							<tr><td>
								<table class=normal width="100%" height="100%"><tr>
								{foreach item=field from=$row}
								{if $field|is_array}
									<td align="{$field.align}" class="{$field.style|default:'normal'}">
									{$field.title}
									</td>
								{/if}
								{/foreach}
								</tr></table>
							</td></tr>
							{/foreach}
		            		</table></td>
		            		{/if}
						<td align="{if $images[row][col].type ne 'album'}{$gallery.tImgAlign|default:'center'}{else}{$gallery.cImgAlign|default:'center'}{/if}">
						
	                    <a href={$images[row][col].href}>
	
	                    {if $gallery.titlePosition|default:"bottom" eq 'top' and $gallery.showTitle|default:"yes" eq 'yes'}
	                    	{$images[row][col].title}<br />
	                    {/if}
	                    
	                    <img src="
							{if $images[row][col].type eq 'empty'}
								{imgsrc table=$smarty.const.MODULESETTINGS_TABLE field='value' id=$setImages.noImageThumb}"
							{elseif $images[row][col].type ne 'album'}
								{imgsrc table=$smarty.const.IMAGES_TABLE field=img_thumb id=$images[row][col].id}" 
								{if $createThumb eq 'no' and $gallery.thumbnailHeight gt 0} height="{$gallery.thumbnailHeight}"
								{/if}
								{if $createThumb eq 'no' and $gallery.thumbnailWidth gt 0} width="{$gallery.thumbnailWidth}"
								{/if}
							{else}
								{$images[row][col].path}"
								{assign var=albumsExist value=1}
								{if $gallery.catImageWidth}
									width='{$gallery.catImageWidth}' 
								{/if}
								{if $gallery.catImageHeight}
									height='{$gallery.catImageHeight}'
								{/if}
							{/if} 
							{if $images[row][col].type ne 'album'}
								 style='border: {$gallery.tImgBorderSize|default:"0"}px solid {$gallery.tImgBorderColor|default:"#000000"};' 
							{else} 
								 style='border: {$gallery.cImgBorderSize|default:"0"}px solid {$gallery.cImgBorderColor|default:"#000000"};' 
							{/if}></a>
					</td>
					
							{if $images[row][col].fields.right}
							<td>
							<table class="normal">
					{foreach item=row from=$images[row][col].fields.right}
					<tr><td>
						<table class=normal width="100%" height="100%"><tr>
						{foreach item=field from=$row}
						{if $field|is_array}
							<td align="{$field.align}" class="{$field.style|default:'normal'}">
							{$field.title}
							</td>
						{/if}
						{/foreach}
						</tr></table>
					</td></tr>
					{/foreach}
		            		</table>
		            		</td>
		            		{/if}
		            		
					</tr></table>
                    </td></tr>
                    
					{foreach item=row from=$images[row][col].fields.bottom}
					<tr><td>
						<table class=normal width="100%" height="100%"><tr>
						{foreach item=field from=$row}
						{if $field|is_array}
							<td align="{$field.align}" class="{$field.style|default:'normal'}">
							{$field.title}
							</td>
						{/if}
						{/foreach}
						</tr></table>
					</td></tr>
					{/foreach}
            		
            		<tr><td align="center">

                        
                        
			        {if $smarty.session.cm_auth.cm_module and $smarty.session.cm_auth.cm_gallery}
			        <table border=0 cellpadding=1 cellspacing=0>
			        	<tr>
			            <td align=center>
			            {if $smarty.session.cm_auth.cm_module and $smarty.session.cm_auth.cm_gallery and $smarty.session.cm_auth.cm_gallery_delete_images}
			            <input type=checkbox name="ch_{$images[row][col].type}_{$images[row][col].id}" value=1>
			            {/if}
			            {if !$images[row][col].first}
			                {if $smarty.session.cm_auth.cm_module and $smarty.session.cm_auth.cm_gallery and $smarty.session.cm_auth.cm_gallery_edit_images}
			                <a href="#" onclick="javascript: submitLink( 'up_{$images[row][col].type}_{$images[row][col].id}' ); return false;"><img src="{$docroot}modules/gallery/images/leftarrow.png" border=0></a>
			                {/if}
			            {/if}
			            {if !$images[row][col].last}
			                {if $smarty.session.cm_auth.cm_module and $smarty.session.cm_auth.cm_gallery and $smarty.session.cm_auth.cm_gallery_edit_images}
			                <a href="#" onclick="javascript: submitLink( 'down_{$images[row][col].type}_{$images[row][col].id}' ); return false;"><img src="{$docroot}modules/gallery/images/rightarrow.png" border=0></a>
			                {/if}
			            {/if}
			            {if $images[row][col].type ne 'album'}
			                {if $smarty.session.cm_auth.cm_module and $smarty.session.cm_auth.cm_gallery and $smarty.session.cm_auth.cm_gallery_edit_images}
			                <a href="#" onclick="javascript: submitLink( 'edit_{$images[row][col].type}_{$images[row][col].id}' ); return false;"><img src="{$docroot}modules/gallery/images/edit.png" border=0></a>
			                {/if}
			            {/if}
			            {if $smarty.session.cm_auth.cm_module and $smarty.session.cm_auth.cm_gallery and $smarty.session.cm_auth.cm_gallery_delete_images}
			            <a href="#" onclick="javascript: if ( confirm( 'Do you really want to delete this {$images[row][col].type}?' ) ) submitLink( 'delete_{$images[row][col].type}_{$images[row][col].id}' );"><img src="{$docroot}modules/gallery/images/drop.png" border=0></a>
			            {/if}
			            </td>
			        	</tr>
			        </table>
			        {/if}
        
                    {else}&nbsp;
                    {/if}
                    </td></tr>
                    </table>
				</td>
            {/section}
        </tr>
    {/section}
    </table>

{if $smarty.session.cm_auth.cm_module and $smarty.session.cm_auth.cm_gallery}
</form>
{/if}