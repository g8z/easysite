var TREE{$menuId}_FORMAT =
[
//0. left position
	{$ms.menu_x},
//1. top position
    {$ms.menu_y},
//2. show +/- buttons
    {$ms.show_images},
//3. couple of button images (collapsed/expanded/blank)
    ["{$docroot}images/menu/{$ms.cimage}", "{$docroot}images/menu/{$ms.eimage}", "{$docroot}images/menu/blank.gif"],
//4. size of images (width, height,ident for nodes w/o children)
    [{$ms.im_width}, {$ms.im_height}, {$ms.node_indent}],
//5. show folder image
    {$ms.show_folder_image},
//6. folder images (closed/opened/document)
   ["{$docroot}images/menu/{$ms.cfolder}", "{$docroot}images/menu/{$ms.ofolder}", "{$docroot}images/menu/{$ms.docimage}"],

//7. size of images (width, height)
    [16,16],
//8. identation for each level [0/*first level*/, 16/*second*/, 32/*third*/,...]
    [0,16,32,48,64,80,96,112,124],
//9. tree background color ("" - transparent)
    "{$ms.tree_bg_color}",
//10. default style for all nodes
    "styleMenu{$menuId}",
//11. styles for each level of menu (default style will be used for undefined levels)
    [{$ms.levelstyles}],
//12. true if only one branch can be opened at same time
    true,
//13. item padding and spacing
    [{$ms.item_padding}, {$ms.item_spacing}],

/************** PRO EXTENSIONS ********************/
//14. draw explorer like tree ( identation will be ignored )
    {$ms.explorer_tree},
//15. Set of explorer images (folder, openfolder, page, minus, minusbottom, plus, plusbottom, line, join, joinbottom)
	["{$docroot}images/menu/{$ms.cfolder}","{$docroot}images/menu/{$ms.ofolder}","{$docroot}images/menu/{$ms.docimage}","{$docroot}images/menu/{$ms.minus}","{$docroot}images/menu/{$ms.minusbottom}","{$docroot}images/menu/{$ms.plus}","{$docroot}images/menu/{$ms.plusbottom}","{$docroot}images/menu/{$ms.line}","{$docroot}images/menu/{$ms.join}","{$docroot}images/menu/{$ms.join_bottom}"],
//16. Explorer images width/height
    [19,16],
//17. if true state will be saved in cookies
    true,
//18. if true - relative position will be used. (tree will be opened in place where init() was called)
    false,
//19. width and height of initial rectangle for relative positioning
    [200,170],
//20. resize background //works only under IE4+, NS6+ for relatiive positioning
    false,
//21. support bgcolor changing for selected node
    {$ms.use_bg_color},
//22. background color for nodes
    ["{$ms.selected_bg_color}", "{$ms.selected_bg_color}", "{$ms.selected_node_class}"]
];


var TREE{$menuId}_NODES = [
{$menuScript}
];