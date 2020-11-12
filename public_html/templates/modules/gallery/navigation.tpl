<table border=0 cellpadding=1 cellspacing=0 class=normal>

<tr><td>{$logoutLink} {$pathway}</td></tr>

<tr><td nowrap>

[ <a href="{$docroot}{$smarty.const.MODULES_DIR}/gallery/index.php">View Gallery</a> ] 
[ <a href="{$docroot}{$smarty.const.MODULES_DIR}/gallery/manage/upload.php">Add Items</a> ] 
[ <a href="{$docroot}{$smarty.const.MODULES_DIR}/gallery/manage/items.php">Edit Items</a> ] 
[ <a href="{$docroot}{$smarty.const.MODULES_DIR}/gallery/manage/index.php">Categories</a> ] 
{if $gallery.useEcommerce|default:'no' eq 'yes'}
[ <a href="{$docroot}{$smarty.const.MODULES_DIR}/gallery/manage/ecommerce/index.php">E-Commerce</a> ] 
{/if}
[ <a href="{$docroot}{$smarty.const.MODULES_DIR}/gallery/manage/editDisplayOptions.php">Display Options</a> ] 
[ <a href="{$docroot}{$smarty.const.MODULES_DIR}/gallery/manage/settings.php">Settings</a> ] 
</td></tr>

</table>
