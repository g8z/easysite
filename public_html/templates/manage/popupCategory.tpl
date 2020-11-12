<html>
    <head>

        {* dump the styles onto the page *}
        <style type="text/css">
        {foreach name=cssStyles item=cssStyle from=$cssStyles}

{$cssStyle.name} {ldelim}
{if $cssStyle.font neq '--'}
font-family: {$cssStyle.font};
{/if}
{if $cssStyle.size}
font-size: {$cssStyle.size}pt;
{/if}
color: {$cssStyle.color};
{if $cssStyle.bg_color ne ""}
background-color: {$cssStyle.bg_color};
{/if}
{if $cssStyle.italic ne 0}
font-style: italic;	
{else}
font-style: normal;
{/if}
{if $cssStyle.underline ne 0}
text-decoration: underline;	
{else}
text-decoration: none;
{/if}
{if $cssStyle.bold ne 0}
font-weight: bold;	
{else}
font-weight: none;
{/if}

        {rdelim}

        {/foreach}

        .verticalColorBar {ldelim}
        	background-image: url({imgsrc table=$smarty.const.SETTINGS_TABLE field=value id=$vtImageId});

        	background-repeat: repeat-y;
        	background-position: left;
        {rdelim}

        .horizontalColorBar {ldelim}
        	background-image: url({imgsrc table=$smarty.const.SETTINGS_TABLE field=value id=$hzImageId});

        	background-repeat: repeat-x;
        	background-position: top;
        {rdelim}

        .specialOperations {ldelim}
        	/* background-color: #EEEEEE; */
        {rdelim}

       </style>

        <title>Select module or module category</title>
        <link rel="stylesheet" type="text/css" href="{$docroot}manage/popupCat/tree_styles.css" />
        <script type="text/javascript" src="{$docroot}javascript/cooltree.js"></script>
        <script type="text/javascript">
        var TREE_FORMAT =
        [
        //0. left position
            4,
        //1. top position
            150,
        //2. show +/- buttons
            1,
        //3. couple of button images (collapsed/expanded/blank)
            ["{$docroot}images/menu/blank.gif", "{$docroot}images/menu/blank.gif", "{$docroot}images/menu/blank.gif"],
        //4. size of images (width, height,ident for nodes w/o children)
            [7,7,0],
        //5. show folder image
            1,
        //6. folder images (closed/opened/document)
           ["{$docroot}images/menu/folder.gif", "{$docroot}images/menu/folderopen.gif", "{$docroot}images/menu/docimage.gif"],

        //7. size of images (width, height)
            [5,10],
        //8. identation for each level [0/*first level*/, 16/*second*/, 32/*third*/,...]
            [0,16,32,48,64,80,96,112,124],
        //9. tree background color ("" - transparent)
            "",
        //10. default style for all nodes
            "styleMenu1",
        //11. styles for each level of menu (default style will be used for undefined levels)
            [],
        //12. true if only one branch can be opened at same time
            true,
        //13. item padding and spacing
            [0,1],

        /************** PRO EXTENSIONS ********************/
        //14. draw explorer like tree ( identation will be ignored )
            1,
        //15. Set of explorer images (folder, openfolder, page, minus, minusbottom, plus, plusbottom, line, join, joinbottom)
        ["{$docroot}images/menu/folder.gif","{$docroot}images/menu/folderopen.gif","{$docroot}images/menu/docimage.gif","{$docroot}images/menu/minus.gif","{$docroot}images/menu/minusbottom.gif","{$docroot}images/menu/plus.gif","{$docroot}images/menu/plusbottom.gif","{$docroot}images/menu/blank.gif","{$docroot}images/menu/blank.gif","{$docroot}images/menu/blank.gif"],
        //16. Explorer images width/height
            [16,16],
        //17. if true state will be saved in cookies
            true,
        //18. if true - relative position will be used. (tree will be opened in place where init() was called)
            false,
        //19. width and height of initial rectangle for relative positioning
            [200,170],
        //20. resize background //works only under IE4+, NS6+ for relatiive positioning
            false,
        //21. support bgcolor changing for selected node
            ,
        //22. background color for nodes
            ["","","normal"]
        ];
        </script>
        <script type="text/javascript">

            {literal}
            function writePath( node, cat_id ) {
              var s='';
              while( node ) 
              {
                s = ' > ' + node.text + s;
                node = node.parentNode;
              }
              document.forms.d.title.value=' > ' + {/literal}'{$module_name}'{literal} + s;
              document.forms.d.cat_id.value = cat_id;
            }

            function sendValues() {
                opener.C.value+=document.forms.d.module.value+'_'+document.forms.d.cat_id.value+'_'+(document.forms.d.overwrite.checked ? '1' : '0');
                opener.C.title=document.forms.d.title.value;
                opener.testURL(opener.C.combo);
            }
            {/literal}

            {if $found}
                {$nodes}
            {/if}

        </script>
    </head>
{if $settings.body_color}
<body bgcolor="{$settings.body_color}">
{else}
<body bgcolor="{$settings.main_color}">
{/if}

    <div class=normal>

        {if $count}


            <form name=d>

            Select module:
            <select name=module onchange='javascript: document.forms.d.submit();'>
                {html_options options=$modules selected=$module}
            </select><br /><br />

            Link: <input type=text size=50 name=title value=' > {$module_name} ' style='border: 1px solid black;'><br />
            <input type=checkbox name=overwrite checked>Overwrite sub-menu items with this category structure<br />
            <input type=hidden name=cat_id value=''>
            <input type=button value=' OK ' onclick="javascript: sendValues();window.close();">
            <input type=button value=' Cancel ' onclick="javascript: window.close();">
            </form>

            {if $found}
                <script type="text/javascript">
                    var t0=new COOLjsTreePRO("t0", TREE_NODES, TREE_FORMAT);
                    t0.init();
                </script>

            <script language="Javascript">
                RedrawAllTrees();
            </script>

            {/if}
        {else}
            <div align=center>You have not active modules.</div><br />
            <input type=button value=' Close ' onclick='javascript:window.colse();' ailgn='center'>
        {/if}

    </div>
    
    <script>
    window.focus();
    </script>

    </body>
</html>