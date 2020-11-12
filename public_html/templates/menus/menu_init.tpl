{if $coolMenuExists}
<script type='text/javascript' src='{$docroot}javascript/coolmenu.js'></script>
{/if}

{if $treeMenuExists}
<script type='text/javascript' src='{$docroot}javascript/cooltree.js'></script>
{/if}

{foreach item=menu from=$menus}
<link rel="stylesheet" type="text/css" href="{$docroot}temp/menu{$menu.id}_styles.css?{$menu.lastChange}" />
<script type="text/javascript" src="{$docroot}temp/menu{$menu.id}_script.js?{$menu.lastChange}"></script>
{/foreach}
