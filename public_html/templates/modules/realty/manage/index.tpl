{include file="modules/realty/navigation.tpl"}

<table border=0 width=100% cellpadding=1 cellspacing=0 class=normal>
    
<tr><td>&nbsp;</td></tr>

<tr><td class=subtitle colspan=2>Real Estate Options</td></tr>

<tr><td>&nbsp;</td></tr>

<tr><td class=normal>
<a href="{$docroot}{$smarty.const.MODULES_DIR}/realty/manage/categories.php">Add/Edit Real Estate Categories</a><br />
Create new categories and sub-categories for your real estate listings. For example, create a "townhouses" category with "1-bedroom" and "2-bedroom" sub-categories. You may also associate your category structure with a menu, which you can edit using the menu tool.
</td></tr>

<tr><td>&nbsp;</td></tr>

<tr><td class=normal>
<a href="{$docroot}{$smarty.const.MODULES_DIR}/realty/manage/listings.php">Add/Edit Real Estate Listings</a><br />
Add, edit, and remove "listings", which provide detailed information about homes and properties in each category. If you have administrative privileges for this module, then you may also change the properties of the listing form.
</td></tr>

<tr><td>&nbsp;</td></tr>

<tr><td class=normal>
<a href="{$docroot}{$smarty.const.MODULES_DIR}/realty/manage/settings.php">Settings & Search Options</a><br />
Change the look and layout of the search and display pages for this module. In other words, change the properties of how the module behaves to end-users.
</td></tr>

<tr><td>&nbsp;</td></tr>

<tr><td class=normal>

{*
<a href="{$docroot}{$smarty.const.MODULES_DIR}/realty/manage/report.php">Real Estate Reports</a><br />
Statistics about the properties that are listed in your Real Estate database.
*}

<b>Real Estate Reports</b><br />
The following reports are available:<br />

<ol>
<li><a target=_blank href=report.php?type=summary&sort=status>Membership Summary - by Membership Status</a>
<li><a target=_blank href=report.php?type=detail&sort=last_name>Membership Detail - by Last Name</a>
<li><a target=_blank href=report.php?type=detail&sort=company>Membership Detail - by Company</a>
<li><a target=_blank href=report.php?type=detail&sort=group>Membership Detail - by Group</a>
<li><a target=_blank href=report.php?type=detail&sort=status>Membership Detail - by Status</a>
<li><a target=_blank href=report.php?type=roster>Membership Roster (Active Members Only)</a>
</ol>

</td></tr>

<tr><td>&nbsp;</td></tr>

</table>
