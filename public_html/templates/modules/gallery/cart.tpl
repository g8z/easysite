<script language="JavaScript" src="{$docroot}libs/JSHttpRequest/JSHttpRequest.js"></script>

{literal}

<script language="JavaScript">
<!--

function validate() {
    
    var el = document.editCart.elements;
    var length = el.length;
    var ii;

    for ( ii=0; ii<length; ii++) {
        if ( el[ii].name.substr(0, 6) == 'count_' ) {
            if ( !isNumeric( el[ii].value, '' ) ) {
            	alert( 'Incorrect numerict value' );
            	return false;
            }
        }
    }
    
    return true;
}


function doLoad(cart_id, attr_id, value) {
var div_id = 'cart_contents';
var target = document.getElementById(div_id);

var req = new JSHttpRequest();
if ( target ) {
	var div =  document.createElement( 'DIV' );
	target.appendChild(div);
	div.style.position = 'absolute';
	div.style.left = '5';
	div.style.top = '5';
	div.style.backgroundColor = 'red';
	div.className = 'normal';
	div.innerHTML = ' updating cart ... ';
//	target.innerHTML += div.outerHTML;
}


req.onreadystatechange = function() {
  if (req.readyState == 4) {
    if ( target )
    	target.innerHTML = req.responseJS.output;
    document.getElementById('visible').style.border="1px";
    document.getElementById('visible').style.border='0px';
  }
}
req.caching = false;
req.open('GET', '{/literal}{$docroot}{literal}modules/gallery/helpers/changeCartAttribute.php', true);
req.send({ cart_id: cart_id, attr_id:attr_id, value:value });
}


-->
</script>

{/literal}

<style>
.cartHeader
{ldelim}
	background-color: {$gallery.headerRowColor|default:'#333333'};
	color: {$gallery.headerTextColor|default:'#EEEEEE'};
{rdelim}
</style>


{if $smarty.session.cm_auth.cm_module and $smarty.session.cm_auth.cm_gallery}
<p class=normal>[ <a href="manage/index.php">Gallery Admin Options</a> ] [ <a href="manage/settings.php?mode=shopping_cart">Display Options</a> ]</p>
{/if}

<form action=cart.php method="POST" enctype="multipart/form-data" name="editCart" onsubmit="javascript: return validate();">
<input type=hidden name=formIsSubmitted value=0>
<input type=hidden name=backAction value="{$backAction}">

<p class=subtitle>{$gallery.mainTitle|default:"Your Shopping Cart"}</p>

{include file="modules/gallery/cartContents.tpl"}
	
<br />
<table width=100% cellspacing="0" cellpadding="0">
<tr>
<td align="left"><input type=button name=back value="<< Continue Shopping" onclick="javascript: document.location.href='{$prevLocation}';"></td>
{if $cartContents}
<td align="right"><input type=submit name=action value="Update">&nbsp;<input type=button name=checkout value='{$gallery.Lcheckout|default:"Checkout"}' onclick="javascript: document.location.href='checkout.php'"></td>
{/if}
</tr>
</table>

</form>