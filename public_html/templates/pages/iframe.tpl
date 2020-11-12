{* http://www.yourhtmlsource.com/frames/goodorbad.html *}

{* iframe implementation for EasySite 1.4.x *}

{* adjust the vertical spacer image size as the browser window height adjusts *}

<table boder=0 cellpadding=0 cellspacing=0 width=100% height=100%><tr><td>

<iframe src="{$href}" frameborder="0" width="100%" height="100%" marginwidth="0" marginheight="0" hspace="0" vspace="0" scrolling="yes"></iframe>

</td><td><img name=frameAdjust src="{$docroot}images/spacer.gif" width=1 height=1></td></tr></table>

{* adjust the height of the spacer image to the page height *}

<script language="Javascript">
var img = document.images['frameAdjust'];
img.height = screen.height;
</script>

<br />