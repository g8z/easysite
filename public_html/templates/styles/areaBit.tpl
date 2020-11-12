z-index:{$s.zindex|default:0};
{if $s.bgcolor}background-color: {$s.bgcolor};{/if}
{if $s.bgimage}
background-image: url('{imgsrc table=$smarty.const.SETTINGS_TABLE field=value id=$s.bgimage}');
background-position: {$s.bgimage_vertical_align|default:"top"} {$s.bgimage_horiz_align|default:"left"};
background-repeat: {if $s.bgimage_repeat_x eq "yes" and $s.bgimage_repeat_y eq "yes"}repeat {elseif $s.bgimage_repeat_x eq "yes"}repeat-x
{elseif $s.bgimage_repeat_y eq "yes"}repeat-y{else}no-repeat{/if};
{/if}
