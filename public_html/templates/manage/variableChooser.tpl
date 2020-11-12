<html>
    <head>

<link rel="stylesheet" type="text/css" href="{$docroot}temp/{$skin_id}_{$smarty.session.site}_systemStyles.css?{$smarty.now}" />

        <title>Select variable to insert</title>
        
        {if $fromHtmlarea}
        <script type="text/javascript" src="htmlarea/popups/popup.js"></script>
		
		<script type="text/javascript">
		
		{literal}
		window.resizeTo(400, 500);
		
		function onOK() {
		  var required = {
		    "resource_type": "You have not choosen a valid resource!"
		  };
		  for (var i in required) {
		    var el = document.getElementById(i);
		    if (!el.value) {
		      alert(required[i]);
		      el.focus();
		      return false;
		    }
		  }
		  // pass data back to the calling window
		  var fields = ["resource_type", "resource_id"];
		  var param = new Object();
		  for (var i in fields) {
		    var id = fields[i];
		    var el = document.getElementById(id);
		    param[id] = el.value;
		  }
		  __dlg_close(param);
		  return false;
		};
		
		function onCancel() {
		  __dlg_close(null);
		  return false;
		};
		{/literal}
		</script>
		{/if}
		

		<link rel="stylesheet" type="text/css" href="{$docroot}manage/popupCat/tree_styles.css" />
        <script type="text/javascript" src="{$docroot}javascript/cooltree.js"></script>
        <script type="text/javascript">
        var TREE_FORMAT =
        [
        //0. left position
            4,
        //1. top position
            100,
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
            "",
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
            ["","",""]
        ];
        </script>
        <script type="text/javascript">

            {literal}
            function writePath( node, resourceType, resourceId ) {
            	
		        var s='';
		        
            	if ( resourceType ) {
	              while( node ) 
	              {
	              	s = ' > ' + node.text + s;
	                node = node.parentNode;
	              }
            	}
            	
                if ( resourceType != 'timestamp' ) {
	            	document.forms.d.title.value = s;
		            document.forms.d.resource_type.value = resourceType;
		            document.forms.d.resource_id.value = resourceId;
                }
                else {
	            	document.forms.d.title.value = ' > ' + ' > ' + 'Timestamp';
		            document.forms.d.resource_type.value = resourceType;
		            writeFormat();
                }
            }
            
            function writeFormat() {
            	var el = document.getElementById( 'rid' );
           		document.forms.d.resource_id.value = el.value;
            }

            function sendValues() {
                
                if ( !document.forms.d.resource_type.value ) {
                    alert( 'You have not choosen a valid resource!' );
                }
                else {
                    opener.field.value += '{internal resource_type=\''+document.forms.d.resource_type.value+'\' resource_id=\''+document.forms.d.resource_id.value+'\'}';
                    window.close();
                }
            }
            {/literal}

            {if $found}
                {$nodes}
            {/if}

        </script>
    </head>

<body bgcolor="{$settings.main.bgcolor|default:$settings.screen.bgcolor|default:$popupBG}">

    <div class=normal>


            <form name=d>

            <table class="normal" cellpadding="0" cellspacing="2" width="100%">
            <tr><td>Insert Internal Variable: </td></tr>
            <tr><td><input type=text size=50 disabled name=title style='border: 1px solid black; font-size:10px; width: 100%;'></td></tr>
            <tr><td>
            <input type=hidden name=resource_type value=''>
            <input type=hidden name=resource_id value=''>
            <input type=button value=' Insert ' onclick="javascript: {if $fromHtmlarea}onOK();{else}sendValues();{/if}">
            <input type=button value=' Cancel ' onclick="javascript: {if $fromHtmlarea}onCancel();{else}window.close();{/if}">
            </td></tr>
            </table>
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
    </div>
    
    <script>
    window.focus();
    </script>


    </body>
</html>