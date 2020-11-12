{literal}
<script language="Javascript">
<!--
function deleteItem() {
    if ( confirm( "Are you sure? This will permanently delete the property listing." ) ) {
        if ( isValid() ) {
            var theForm = document.mainForm;
            theForm.deleteId.value = theForm.listing_id.value;
            submitForm();
        }
    }
}
function addListing() {
    if ( isValid() ) {
        var theForm = document.mainForm;
        theForm.addListing.value = 1;
        submitForm();
    }
}

function bumpImage( id ) {
    if ( isValid() ) {
        var theForm = document.mainForm;
        theForm.bumpId.value = id;
        submitForm();
    }
}

function removeImage( id ) {
    if ( isValid() ) {
        var theForm = document.mainForm;
        theForm.deleteImage.value = id;
        submitForm();
    }
}

function submitForm() {
    var theForm = document.mainForm;
    theForm.formIsSubmitted.value = '1';
    theForm.submit();
}

function doSubmit() {
    if ( isValid() ) {
        submitForm();
    }
}

function isValid() {
    var theForm = document.mainForm;
    
    // check for required fields
    if ( theForm.title.value.trim() == '' ) {
        alert( 'Please input a title for this real estate listing.' );
        theForm.title.focus();
        return false;
    }
    
    // list price and sell price must be integers (so that we can search on them)
    
    if ( !isNumeric( theForm.list_price.value ) ) {
        alert( 'List price must be an integral value.' );
        theForm.list_price.focus();
        return false;
    }
    if ( !isNumeric( theForm.sell_price.value ) ) {
        alert( 'Sell price must be an integral value.' );
        theForm.sell_price.focus();
        return false;
    }
    
    // the user must specify a category
    
    if ( theForm.cat_id.value == '' ) {
        alert( 'Please choose a category.' );
        theForm.cat_id.focus();
        return false;
    }
    
    // the user must specify that the listing is posted for at least 1 day
    
    if ( theForm.post_days.value == '' ) {
        if ( confirm( 'You have not specified a value for "Post Days". The listing will not be searchable (because it will be immediately expired). Are you sure that you want to do this?' ) ) {
            return true;
        }
        else {
            return false;
        }
    }
    
    return true;
}
function switchForm() {
    if ( document.mainForm.listing_id.value == '' )
        document.location.href = 'listings.php';// if no id, then add new
    else
        document.mainForm.submit();
}

//-->
</script>
{/literal}

<a name=top></a>

{include file="modules/realty/navigation.tpl"}

<table border=0 width=100% cellpadding=2 cellspacing=0 class=normal>
    
<form name=mainForm method=post action=listings.php enctype="multipart/form-data">
    
    <tr><td>&nbsp;</td></tr>
    
    <tr><td class=subtitle colspan=2>Real Estate Listings</td></tr>
   

    <tr><td colspan=2>Add and update real estate property listings in any existing category. <a href=#imageUpload>Upload images for this real estate listing.</a></td></tr>
    
    <tr><td colspan=2>&nbsp;</td></tr>

    
    <tr><td>
    
    {* the form for a single listing *}
    <table border=0 cellpadding=1 cellspacing=0 class=normal>

    <tr><td width=20% nowrap>Switch To: </td><td>
    
    {* show all listings that have been posted by this person *}
    <select name=listing_id onChange="javascript:switchForm();">
    <option value=''> - Create a New Listing - </option>
    {html_options options=$listings selected=$data.id}
    </select>
    
    </td></tr>

    <tr><td>Category: </td><td>
    
    <select name=cat_id>
    <option value=''> - Select a Category - </option>
    {html_options options=$categories selected=$data.cat_id}
    </select>
    
    </td></tr>
   
    
    <tr><td>Title: </td><td><input type=text size=30 name=title value="{$data.title}"></td></tr>
    
    <tr><td valign=top>Brief Description: </td><td><textarea cols=30 rows=3 name=brief_description>{$data.brief_description}</textarea></td></tr>
    
    <tr><td colspan=2>Long Description: </td></tr>
    
    <tr><td colspan=2><textarea cols=60 rows=5 name=long_description>{$data.long_description}</textarea></td></tr>
  
    <!-- date that the listing was created -->
    <tr><td>Listing Date: </td><td>{html_select_date time=$data.listing_date prefix="listing_date_" start_year=-1}</td></tr>
    
    <tr><td>Closing Date: </td><td>{html_select_date time=$data.closing_date prefix="closing_date_" start_year=+1}</td></tr>
    
    <tr><td nowrap>Post Days:<br /><small>(number of days the listing<br />should remain visible)</small></td>
    <td>{list name=post_days selected=$data.post_days}</td></tr>
    
    <tr><td>Address, Line 1: </td><td><input type=text size=30 name=address_1 value="{$data.address_1}"></td></tr>
    
    <tr><td>Address, Line 2: </td><td><input type=text size=30 name=address_2 value="{$data.address_2}"></td></tr>
    
    <tr><td>City: </td><td><input type=text size=30 name=city value="{$data.city}"></td></tr>
    
    <tr><td>District: </td><td><input type=text size=30 name=district value="{$data.district}"></td></tr>
    
    <tr><td>County: </td><td><input type=text size=30 name=county value="{$data.county}"></td></tr>
    
    <tr><td>State: </td><td>{list key=states selected=$data.state}</td></tr>
    
    <tr><td>Country: </td><td>{list key=countries selected=$data.country}</td></tr>
    
    <tr><td>List Price: </td><td><input type=text size=20 name=list_price value="{$data.list_price}"> (input an integer)</td></tr>
    
    <tr><td>Sell Price: </td><td><input type=text size=20 name=sell_price value="{$data.sell_price}"> (input an integer)</td></tr>
    
    <tr><td>Bathrooms: </td><td>{list name=bathrooms selected=$data.bathrooms}</td></tr>
    
    <tr><td>Bedrooms: </td><td>{list name=bedrooms selected=$data.bedrooms}</td></tr>
    
    <tr><td>Fireplace: </td><td>{html_radios name=fireplace options=$yesno selected=$data.fireplace}</td></tr>
    
    <tr><td>Garage: </td><td>{html_radios name=garage options=$yesno selected=$data.garage}</td></tr>
    
    <tr><td>Floor Size: </td><td><input type=text size=10 name=floorsize value="{$data.floorsize}"> (sq. ft.)</td></tr>
    
    <tr><td>Lot Size: </td><td><input type=text size=10 name=lotsize value="{$data.lotsize}"> (sq. ft.)</td></tr>
    
    <tr><td>Num Stories: </td><td>{list key=num_stories selected=$data.num_stories}</td></tr>
    
    <tr><td>Home Age (years): </td><td>{list key=home_age selected=$data.home_age}</td></tr>

    <tr><td>Near School: </td><td>{html_radios name=near_school options=$yesno selected=$data.near_school}</td></tr>
    
    <tr><td>Near Transit: </td><td>{html_radios name=near_transit options=$yesno selected=$data.near_transit}</td></tr>
    
    <tr><td>Ocean View: </td><td>{html_radios name=ocean_view options=$yesno selected=$data.ocean_view}</td></tr>
    
    <tr><td>Lake Front: </td><td>{html_radios name=lake_front options=$yesno selected=$data.lake_front}</td></tr>
    
    <tr><td>Balcony: </td><td>{html_radios name=balcony options=$yesno selected=$data.balcony}</td></tr>
    
    <tr><td>Laundry: </td><td>{html_radios name=laundry options=$yesno selected=$data.laundry}</td></tr>
    
    <tr><td>Fitness Center: </td><td>{html_radios name=fitness_center options=$yesno selected=$data.fitness_center}</td></tr>
    
    <tr><td>Pool: </td><td>{html_radios name=pool options=$yesno selected=$data.pool}</td></tr>
    
    <tr><td>Jacuzzi: </td><td>{html_radios name=jacuzzi options=$yesno selected=$data.jacuzzi}</td></tr>
    
    <tr><td>Guest House: </td><td>{html_radios name=guest_house options=$yesno selected=$data.guest_house}</td></tr>
    
    {include file="modules/realty/manage/listingButtons.tpl"}
    
    
    {* list all of the images associated with this item *}
    
    <tr><td colspan=2><a name=imageUpload></a>&nbsp;</td></tr>
    
    <tr><td colspan=2>The following images have been uploaded for this listing. The first image will be displayed as the default image for the listing.</td></tr>
    
    <tr><td colspan=2>&nbsp;</td></tr>
    
    {assign var=count value=0}
    
    {foreach name=iterator item=image from=$imageData}
     
        {if $count gt 0}
    <tr><td valign=top>Upload Image #{$count}:</td>
    {else}
    <tr><td valign=top><b>New Image:</b></td>
    {/if}

    <td>
        <input type=file name="image_{$image.id}"> 
        {if $count gt 0}
        <input type=button onClick="javascript:removeImage({$image.id});" value="Remove">
        {if $count gt 1}
        <input type=button onClick="javascript:bumpImage({$image.id});" value="Bump Up">
        {/if}
        {/if}
    
    </td></tr>

    {if $count gt 0}
        <tr><td valign=top>Current Image:
        
        {if $count eq 1}
        <br /><small>(this is the default<br />image for this listing)</small>
        {/if}
        
        </td><td>

        {if $image.data}
            <img src="{imgsrc field=data table=$smarty.const.MODULEOBJECTS_TABLE id=$image.id}">
        {else}
            There is no data for this image yet.
        {/if}

        </td></tr>
    {/if}
    
    {* print the horizontal line *}
    
    <tr><td colspan=2><hr noshade size=1 style='width:100%'></td></tr>

        {* compute the iteration # *}
        {math equation="y + 1" y=$count assign=count}

    {/foreach}
    
    {include file="modules/realty/manage/listingButtons.tpl"}
    
    <tr><td colspan=2><a href=#top>Back to Top</a></td></tr>
    
    </table>
    </td></tr>

<input type=hidden name=formIsSubmitted value="">
<input type=hidden name=deleteId value="">
<input type=hidden name=deleteImage value="">
<input type=hidden name=bumpId value="">

</form>

</table>