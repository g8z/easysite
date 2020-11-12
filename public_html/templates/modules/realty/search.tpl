<table class=normal cellpadding=2 cellspacing=0>
<form action="" method=post>

<tr><td class=subtitle colspan=2>{$realtySettings.search_title|default:"Search the Real Estate Listings"}</td></tr>

<tr><td colspan=2>{$realtySettings.search_desc|trim|nl2br}</td></tr>

<tr><td colspan=2>&nbsp;</td></tr>

<tr><td nowrap align=right>Type of Property: </td><td><select name=catid>{html_options options=$categories}</select></td></tr>

<tr><td colspan=2>&nbsp;</td></tr>

<tr><td align=right>Price Range: </td><td nowrap>{list name=price_min} - {list name=price_max}</td></tr>

<tr><td align=right>Bedrooms: </td><td nowrap>{list name=bedroom_min} - {list name=bedroom_max}</td></tr>

<tr><td align=right>Bathrooms: </td><td nowrap>{list name=bathroom_min} - {list name=bathroom_max}</td></tr>

<tr><td align=right>Days Old: </td><td nowrap>{list name=days_old}</td></tr>

<tr><td align=right>State: </td><td nowrap>{list name=states}</td></tr>

<tr><td align=right>City/Region: </td><td nowrap>{list name=cities}</td></tr>

<tr><td></td><td>

<table class=normal cellpadding=1 cellspacing=0>

<tr>
	<td><input type=checkbox name=fireplace value=1></td><td>Fireplace </td>
	<td><input type=checkbox name=garage value=1></td><td>Garage </td>
</tr>

<tr>
	<td><input type=checkbox name=near_school value=1></td><td>Near School </td>
	<td><input type=checkbox name=near_transit value=1></td><td>Near Transit </td>
</tr>

<tr>
	<td><input type=checkbox name=ocean_view value=1></td><td>Ocean View </td>
	<td><input type=checkbox name=lake_front value=1></td><td>Lake Front </td>
</tr>

<tr>
	<td><input type=checkbox name=balcony value=1></td><td>Balcony </td>
	<td><input type=checkbox name=laundry value=1></td><td>Laundry </td>
</tr>

<tr>
	<td><input type=checkbox name=fitness_center value=1></td><td>Fitness Center </td>
	<td><input type=checkbox name=pool value=1></td><td>Pool </td>
</tr>

<tr>
	<td><input type=checkbox name=jacuzzi value=1></td><td>Jacuzzi </td>
	<td><input type=checkbox name=guest_house value=1></td><td>Guest House </td>
</tr>

</table>

</td></tr>

<tr><td></td><td><input type=submit name=search value="Perform Search"></td></tr>

</form>

</table>