
// Auto-Centering script for EasySite body

{if $settings.auto_center eq 'yes' or $settings.centerHorizantalColor eq 'yes' or $settings.centerBgImage eq 'yes' or $settings.centerVerticalColor eq 'yes'}

var dx;
var overallWidth;
var bodyX;

{literal}
function getDx() {

    var theWidth;

    if (window.innerWidth) {
	   theWidth = window.innerWidth-15;
    }
    else if (document.documentElement && document.documentElement.clientWidth) {
        theWidth = document.documentElement.clientWidth
    }
    else if (document.documentElement.offsetWidth) {
        // 20 is for scrollbars
        theWidth = document.documentElement.offsetWidth - 20;
    }
    else if (document.body) {
        theWidth = document.body.clientWidth
    }

{/literal}


    {if $bodyXPercent}
        bodyX = Math.round( ( theWidth ) * parseFloat( {$percentXValue} ) );
    {else}
    	bodyX = parseInt({$settings.body_x});
    {/if}
        
    {if $bodyWPercent}
        // determine the overall widht in case if doby with is percentage value
        var bodyW = Math.round( ( theWidth ) * parseFloat( {$percentWValue} ) );
        bodyW = ( bodyW > 100 ) ? bodyW : 200;
    {else}
    	var bodyW = parseInt({$settings.body_w});
    {/if}
    
    var maxBodyX = bodyW + bodyX;
    
    var minLayerX = {$minLayerX};
    var maxLayerX = {$maxLayerX};
    
    minX = ( bodyX < minLayerX ) ? bodyX : minLayerX;
    maxX = ( maxBodyX > maxLayerX ) ? maxBodyX : maxLayerX;
    
    overallWidth = maxX - minX;

    if ( overallWidth < {$bgImageWidth} )
        overallWidth = {$bgImageWidth};

    
    {* Opera does not work correct with the real values *}
    dx = Math.round(( theWidth - overallWidth ) / 2);
    
{literal}

    dx = ( dx > 0 ) ? dx : 0;
    
}

function onResizeHandler() {
	getDx();
	centerContent();
}

getDx();

{/literal}

{* centers all the layes including main body layer *}
{* by adding dx to the x coord of the layer *}
{* menus are already centered in menu.php *}

function centerContent() {ldelim}
    var width = window.width;
    var b;

    b = document.getElementById( 'container' );
    b.style.left = dx ;
    
    {if $settings.centerBgImage eq 'yes' and $settings.bg_image}
    b = document.getElementById( 'bgImageDiv' );
    b.style.left = dx + 'px';
    {/if}
    
    {if $settings.centerVerticalColor eq 'yes'}
    // center the horizontal color bar
    document.body.style.backgroundPosition = dx + 'px';
    //document.body.style.backgroundPosition = 0 + 'px';
    {/if}

{rdelim}

{else} 
    {* auto-center feature is disabled *}
    var dx = 0;
{/if}

