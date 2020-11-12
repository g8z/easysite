<table class=normal width="100%">

{section loop=$rows name=row}
    <tr>
        {section loop=$cols name=col}
            <td align=center>
            {if $images[row][col].id}
               <img {if $settings.imageWidth}width="{$reportSettings.imageWidth}"{/if} {if $settings.imageHeight}height="{$reportSettings.imageHeight}"{/if} style="border: {$reportSettings.imageBorderSize}px solid {$reportSettings.imageBorderColor}" src="{imgsrc table=$smarty.const.FORMSUBMISSIONS_TABLE field=blob_value id=$images[row][col].id}"><br>
               {$images[row][col].file_data_path}
            {else}&nbsp;
            {/if}
            </td>
        {/section}
    </tr>
{sectionelse}
<tr><td>There were no images submitted.</td></tr>
{/section}

</table>
