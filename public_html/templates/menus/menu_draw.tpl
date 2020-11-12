{* display all DHTML menus in this site *}
{foreach item=menu from=$menus}
{$menu.links}
<script type="text/javascript">
{$menu.data}
</script>
{/foreach}
{* end of DHTML menu display *}