
BLANK_IMAGE="{$docroot}images/menu/blank.gif";
var code="code";
var url="url";
var sub="sub";

var color = {ldelim}
    "border"   : "{$ms.menu_border}",
    "shadow"   : "{$ms.menu_shadow}",
    "bgON"     : "{$ms.bk_rollout}",
    "bgOVER"   : "{$ms.bk_rollover}",
    "imagebg"  : "{$ms.bk_rollout}",
    "oimagebg" : "{$ms.bk_rollover}"
{rdelim};

var css = {ldelim}
	"ON"       : "menu{$menuId}On", 
	"OVER"     : "menu{$menuId}Over"
{rdelim};

var STYLE = {ldelim}
    "border"   : {$ms.border_size|strval},
    "borders"  : "[1,1,1,1]",
    "shadow"   : {$ms.shadow_size|strval},
    "color"    : color,
    "css"      : css
{rdelim};

//items and formats
var MENU_ITEMS_BORDERSANDSHADOW{$menuId} =
[
    {ldelim}
	"pos"      : [{$ms.menu_x},{$ms.menu_y}],
	"delay"    : {$ms.expand_time}, 
	"leveloff" : [{$leveloff.dy}, {$leveloff.dx}],
	"itemoff"  : [{$itemoff.dy}, {$itemoff.dy}],
	"style"    : STYLE, 
	"size"     : [{$ms.menu_h}, {$ms.menu_w}]
    {rdelim},

    {$menuScript}

];