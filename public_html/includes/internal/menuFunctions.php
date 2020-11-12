<?php






// this is used for tree menu
// so we need only overcolor and out-color
function getlevelsettings($level,$menuId)
{
   global $db, $site;

   $levelsettings=$db->getRow('Select over_color, out_color from '. MENUITEMS_TABLE . " where level='$level'" );
   
   return $levelsettings;
}

function marray_combine($keys, $vals) {
       $i = 0;
       foreach ($keys as $key) {
               $newarray[$key] = $vals[$i++];
               }
       return $newarray;
}



function getborders($clevel,$menuId)
{
   global $db;
   $cssdef=array ('BORDER-TOP','BORDER-RIGHT','BORDER-BOTTOM','BORDER-LEFT');

   $add_fields = array( 'menu_id', 'site_key' );
   $add_values = array( $menuId , $site );
   $menu = new Category( $db, MENUITEMS_TABLE, $add_fields, $add_values );

   $id = $db->getOne( 'select id from ' . MENUITEMS_TABLE ." where menu_id='$menuId' and site_key='$site' " );

   $bordersettings=$db->getOne('Select borders from ' . MENUITEMS_TABLE . ' where id="'.$id.'"');
   if ( $bordersettings && ereg( "([0-9]{1,2}),([0-9]{1,2}),([0-9]{1,2}),([0-9]{1,2})",   $bordersettings ) )
            $borders = $bordersettings;
        else
            $borders = '1,1,1,1';//default
  $borders=split(",",substr($borders,1,-1));
  $borderstyles=marray_combine($cssdef,$borders);
  $str="";
  foreach ($borderstyles as $key=>$value)
  {
      $str.=$key."=".$value."px;".chr(13).chr(10);
  }
  return $str;
}


function getstylesheetlevel( $level, $menuId, $type )
{
   global $db;
   
   $id = $db->getOne( 'select id from ' . MENUITEMS_TABLE . " where level='$level' " );

   $levelsettings=$db->getRow('Select '. STYLES_TABLE .'.bold, '. STYLES_TABLE .'.font,'. STYLES_TABLE .'.size,' . STYLES_TABLE . '.color from '. STYLES_TABLE . ','.MENUITEMS_TABLE.' where '.MENUITEMS_TABLE.'.id='.$id.' and concat(".",'.MENUITEMS_TABLE.'.'.$type.'_style)='. STYLES_TABLE . '.name');
   if ($levelsettings["bold"]==1)
   {
       $levelsettings["font_weight"]="bold";
   }
   return $levelsettings;
}


?>
