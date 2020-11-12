{foreach from=$images item=image}
        <tr>
            <td style="border-bottom: 1px solid #999999; width:20px;">
            {if $smarty.session.cm_auth.cm_module and $smarty.session.cm_auth.cm_gallery and $smarty.session.cm_auth.cm_gallery_delete_images}
            <input type=checkbox name="ch_image_{$image.id}" value=1>
            {else}
            &nbsp;
            {/if}
            </td>
            <td style="border-bottom: 1px solid #999999;">
                {section loop=$image.level-$minLevel name=space}
                    <img src="{$docroot}modules/gallery/images/arrow.png" border=0>&nbsp;&nbsp;&nbsp;&nbsp;
                {/section}
                <a href="#" onclick="javascript:submitLink( 'edit_image_{$image.id}' );">{$image.title}</a>
            </td>
            <td align=center style="border-bottom: 1px solid #999999;">
            	{foreach from=$image.categories item=cat}
            	<a href="../index.php?category={$cat.id}">{$cat.title}</a><br />
            	{/foreach}
            </td>
            <td align=center style="border-bottom: 1px solid #999999;">

            {if $smarty.session.cm_auth.cm_module and $smarty.session.cm_auth.cm_gallery and $smarty.session.cm_auth.cm_gallery_edit_images}
            <a href="#" onclick="javascript:submitLink( 'edit_image_{$image.id}' );"><img src="{$docroot}modules/gallery/images/edit.png" border=0></a>
            {/if}

            {if $smarty.session.cm_auth.cm_module and $smarty.session.cm_auth.cm_gallery and $smarty.session.cm_auth.cm_gallery_delete_images}
            <a href="#" onclick="javascript:if ( confirm( 'Are you sure you want to delete this image?' ) ) submitLink( 'delete_image_{$image.id}' );"><img src="{$docroot}modules/gallery/images/drop.png" border=0></a>
            {/if}
            </td>
        </tr>
{/foreach}