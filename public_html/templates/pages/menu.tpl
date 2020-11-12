<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

<head>

<title>Test Menu Template</title>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

{$script1}

</head>



<body>
{foreach item=menu from=$menus}
{$menus.data}
{/foreach}
</body>
</html>

