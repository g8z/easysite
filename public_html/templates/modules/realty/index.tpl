<table border=0 cellpadding=2 cellspacing=0 class=normal width=100%>

{if $smarty.session.cm_auth.cm_realty}
<tr><td>{$adminReturnLink} {$logoutLink} {$userGuideLink}</td></tr>
{/if}

{* display a few navigation options *}

<tr><td>[ <a href=index.php>Perform a New Search</a> ] 

{* if a real estate admin is logged in, give the user some admin options *}

{if $smarty.session.cm_auth.cm_realty}
[ <a href="manage/index.php">Real Estate Admin Options</a> ] 
{/if}

</td></tr>

<tr><td>&nbsp;</td></tr>

{if $isNavigable}
	<tr>
		<td>{$navigation}</td>
	</tr>

	<tr>
    <td>

    {* display the actual data! *}


   	<table class=normal cellpadding=1 cellspacing=1 border=0 width=100%>
	{foreach item=listing from=$listingData}

    {capture name=propertyData}

	{if $listing.title}
	<span class=subtitle>{$listing.title}</span><br />
	{/if}
	
	{if $realtySettings.showPopertyId|default:"yes" eq 'yes'}<b>{$realtySettings.popertyIdTemplate|default:"Preperty ID:"} {$listing.id}</b><br />
    {/if}
	
	{if $listing.city}
		{$listing.city} {if $listing.state}, {$state}{/if}<br />
	{/if}
	
	{if $listing.address_1}
		{$listing.address_1}<br />
	{/if}
	
	{if $listing.address_2}
		{$listing.address_2}<br />
	{/if}
	
	{if $listing.district}
		District: {$listing.district}<br />
	{/if}
	
	{if $listing.county}
		County: {$listing.county}<br />
	{/if}
	
	{if $listing.country}
		Country: {$listing.country}<br />
	{/if}
	
	Bedrooms: {$listing.bedrooms|default:0}<br />
	Bathrooms: {$listing.bathrooms|default:0}<br />
	
	{if $listing.home_age}
	Home Age: {$listing.home_age} Years<br />
	{/if}
	
	{if $listing.floorsize}
	Floor Size: {$listing.floorsize} {$realtySettings.spaceUnit|default:"ft. st."}<br />
	{/if}
	
	{if $listing.lotsize}
	Lot Size: {$listing.lotsize} {$realtySettings.spaceUnit|default:"ft. st."}<br />
	{/if}
	
	{if $listing.fireplace}
	Fireplace<br />
	{/if}
	
	{if $listing.garage}
	Garage<br />
	{/if}
	
	{if $listing.near_school}
	Near School<br />
	{/if}
	
	{if $listing.near_transit}
	Near Transit<br />
	{/if}
	
	{if $listing.ocean_view}
	Ocean View<br />
	{/if}
	
	{if $listing.lake_view}
	Lake View<br />
	{/if}
	
	{if $listing.mountain_view}
	Mountain View<br />
	{/if}
	
	{if $listing.ocean_front}
	Ocean Front<br />
	{/if}
	
	{if $listing.lake_front}
	Lake Front<br />
	{/if}
	
	{if $listing.river_front}
	River Front<br />
	{/if}
	
	{if $listing.balcony}
	Balcony<br />
	{/if}
	
	{if $listing.fitness_center}
	Fitness Center<br />
	{/if}
	
	{if $listing.pool}
	Pool<br />
	{/if}
	
	{if $listing.guest_house}
	Guest House<br />
	{/if}

	{if $listing.jacuzzi}
	Jacuzzi
	{/if}

    {/capture}

	{capture name=propertyPriceImage}

	{* right column *}

		{if $listing.list_price}
		<p><b>Price: ${$listing.list_price|number_format:"$format_dec":"$format_point":"$format_th"}</b></p>
		{/if}

        {if $realtySettings.showPopertyImages|default:"yes" eq 'yes' }
        {if $listing.objid }
			<p><img src="{imgsrc field=data table=$smarty.const.MODULEOBJECTS_TABLE id=$listing.objid}" style="border: {$realtySettings.imgBorderSize|default:"0"}px solid {$realtySettings.borderColor|default:"#000000"}"></p>
		{else}
			<img src="images/no_image.gif">
		{/if}
        {/if}

		{if $listing.numAddedImages gt 1 and $realtySettings.showMoreImagesLink|default:"yes" eq 'yes'}
		<p><a href="javascript:launchCentered( 'imageViewer.php?id={$listing.id}', {$realtySettings.imageWindowWidth|default:"400"}, {$realtySettings.imageWindowHeight|default:"400"}, 'scrollbars,resizable' );">More Images ({$listing.numAddedImages})</a></p>
		{/if}

        {/capture}



  	<tr><td colspan="2">&nbsp;</td></tr>
    {if $realtySettings.imagePosition|default:"left" eq 'left'}
    	<tr><td valign=top align=center>
        {$smarty.capture.propertyPriceImage}
        </td>

    	<td valign=top>
        {$smarty.capture.propertyData}
        </td></tr>
    {else}
    	<tr><td valign=top>
        {$smarty.capture.propertyData}
        </td>

    	<td valign=top align=center>
        {$smarty.capture.propertyPriceImage}
        </td></tr>
    {/if}


	{foreachelse}
	There were no properties matching your search criteria.
	{/foreach}

	<tr><td colspan=2>&nbsp;</td></tr>
	
	</table>
	
	
	</td>
	</tr>

{else}

	<tr>

		<td valign=top>

			{include file=modules/realty/search.tpl}

		</td>

	</tr>

{/if}

</table>