<?php

  // Check to make sure that the basic security variable has been set
  // thus ensuring tht the file is not being loaded directly.
  if( ! defined( '_SECURITY_BASIC' ) )
    die( '' );

  // Get the MapID from the URL
  $MapID = $_GET['MapID'];

  // Get the database MapID
  $dbMapID = getMapID( $MapID );

  // Get the map details
  $mapDetails = getMapDetails( $MapID );

  // Build the starting array
  $query = "SELECT * FROM "._DB_NAME."."._DB_PREFIX."_Map_Cells
            WHERE fk_MapID = '$dbMapID'";
  $result = mysql_query( $query );
  $numrows = mysql_num_rows( $result );

  // Build the PHP array of elements
  for( $i = 0; $i < $numrows; $i++ )
  {
    $row = mysql_fetch_assoc( $result );

    $fk_CellID = $row['fk_CellID'];
    $fk_BrushID = $row['fk_BrushID'];

    $CellBrush[$fk_CellID] = $fk_BrushID;

  }

  // Get the buttons for this map
  $buttons = getSwitchButtons( $dbMapID );

  // Initialize the switch and bomb strings
  // These will be used by Javascript to create the
  // frontend objects
  $switchString = '';
  $bombString = '';

  // Get the switches for this map
  foreach( $buttons as $i => $ButtonID )
  {
    // If there is already text, we need to append commas first
    if( $switchString != '' )
      $switchString .= ", ";

    if( $bombString != '' )
      $bombString .= ", ";

    // Append the switch and bombs string with the button object identifier
    $switchString .= "$ButtonID: [";
    $bombString .= "$ButtonID: [";

    // Get the switches and step through them for this button
    $switches = getSwitches( $dbMapID, $ButtonID, 'grate' );

    // Initialize an identifier to control commas
    $j = 0;

    foreach( $switches as $CellID => $defaultState )
    {
      // If this is the first element, do not inject a comma
      // otherwise we will
      if( $j == 0 )
        $switchString .= "$CellID";
      else
        $switchString .= ",$CellID";

      // Increment the comma controller
      $j++;
    }

    // Get the bombs and step through them for this button
    $bombs = getSwitches( $dbMapID, $ButtonID, 'bomb' );

    // Initialize an identifier to control commas
    $j = 0;

    foreach( $bombs as $CellID => $defaultState )
    {
      // If this is the first element, do not inject a comma
      // otherwise we will
      if( $j == 0 )
        $bombString .= "$CellID";
      else
        $bombString .= ",$CellID";

      // Increment the comma controller
      $j++;
    }

    // Close out this button's object lsting
    $switchString .= "]";
    $bombString .= "]";

  } // end foreach mapButtons


  // Build the navigation menu
  $NAV .= "<h3>Site Navigation</h3>\n";
  $NAV .= "<big>&middot;</big> <a href=\"index.php?module=Account\">About Map Designer</a><br/>\n";
  $NAV .= "<big>&middot;</big> <a href=\"index.php?module=Map&amp;op=list\">Your Maps</a><br/>\n";
  $NAV .= "<big>&middot;</big> <a href=\"index.php?module=Map&amp;op=new\">Create a New Map</a><br/>\n";
  $NAV .= "<big>&middot;</big> <a href=\"index.php?module=Active\">Active Maps</a><br/>\n";

  $NAV .= "    <h3>Map Info</h3>\n";
  $NAV .= "    <strong>Current Position:</strong> <span id=\"map_position_x\">0</span>, <span id=\"map_position_y\">0</span><br/>\n";
  $NAV .= "    <strong>Name:</strong> <span id=\"mapDisplayName\">".$mapDetails['vc_Name']."</span><br/>\n";
  $NAV .= "    <strong>Author:</strong> <span id=\"mapDisplayAuthor\">".$mapDetails['vc_Author']."</span><br/>\n";
  $NAV .= "    <strong>Created:</strong> ".date( 'm/d/Y', strtotime( $mapDetails['dt_Created'] ) )."<br/>\n";

  $NAV .= "    <h3>Editor Options</h3>\n";
  $NAV .= "    <big>&middot;</big> <a href=\"#NULL\" id=\"editMapName\"> Change Map Name</a></span><br/>\n";
  $NAV .= "    <big>&middot;</big> <a href=\"#NULL\" id=\"editMapAuthor\"> Change Map Author</a></span><br/>\n";
  $NAV .= "    <big>&middot;</big> <a href=\"#NULL\" id=\"editSwitches\"> Enter Switch/Bomb Editor</a></span><br/>\n";
  $NAV .= "    <big>&middot;</big> <a href=\"#NULL\" id=\"resetSwitches\"> Reset all Switches/Bombs</a></span><br/>\n";
  $NAV .= "    <span id=\"saveMapText\"><big>&middot;</big> <a href=\"#NULL\" id=\"saveMap\">Save Map Now</a></span>\n";
  $NAV .= "    <span id=\"saveMapStatus\" style=\"display: none;\"><em>...saving map</em></span>\n";
  $NAV .= "    <br/>\n";

  $NAV .= "    <h3>Testing Options</h3>\n";
  $NAV .= "    <big>&middot;</big> <a href=\"index.php?module=Viewer&amp;op=view&amp;MapID=$MapID\" target=\"_BLANK\">View/Share Map</a><br/>\n";
  $NAV .= "    <big>&middot;</big> <a href=\"tools/generatePNG.php?MapID=$MapID\"> Download Map PNG</a><br/>\n";
  $NAV .= "    <big>&middot;</big> <a href=\"tools/generateJSON.php?MapID=$MapID\"> Download Map JSON</a><br/>\n";
  $NAV .= "    <big>&middot;</big> <a href=\"tools/loadTestMap.php?MapID=$MapID\" target=\"_BLANK\"> Test Map</a>\n";


  // Display all of the available brushes
  $brushTiles = getBrushTiles();

  $NAV .= "<h3>Brush Selections</h3>\n";

  $NAV .= "<div class=\"brushes\">\n";

  foreach( $brushTiles as $BrushID => $BrushProperties )
  {
    $NAV .= "<div class=\"brush\">\n";
    $NAV .= "  <a class=\"brush\" href=\"#NULL\" rel=\"".$BrushProperties['pk_ID']."\" rev=\"".$BrushProperties['vc_Reference']."\">\n";
    $NAV .= "    <img src=\"theme/"._THEME_NAME."/images/icons/40/".$BrushProperties['vc_Reference'].".png\" />\n";
    $NAV .= "  </a>\n";
    $NAV .= "</div>\n";
  }

  $NAV .= "</div>\n";


  $NAV .= "    <h3>Brush Size</h3>\n";

  $NAV .= "    Note that Brush Sizes only affect blanks, walls, and floors.<br/><br/>\n\n";

  $NAV .= "    <a href=\"#NULL\" class=\"brushRadius\" rel=\"1\"><img id=\"brushSize1\" src=\"theme/"._THEME_NAME."/images/icons/brushsize/brush_1_on.png\" /></a> &nbsp; \n";
  $NAV .= "    <a href=\"#NULL\" class=\"brushRadius\" rel=\"2\"><img id=\"brushSize2\" src=\"theme/"._THEME_NAME."/images/icons/brushsize/brush_2_off.png\" /></a> &nbsp; \n";
  $NAV .= "    <a href=\"#NULL\" class=\"brushRadius\" rel=\"3\"><img id=\"brushSize3\" src=\"theme/"._THEME_NAME."/images/icons/brushsize/brush_3_off.png\" /></a> &nbsp; \n";
  $NAV .= "    <a href=\"#NULL\" class=\"brushRadius\" rel=\"4\"><img id=\"brushSize4\" src=\"theme/"._THEME_NAME."/images/icons/brushsize/brush_4_off.png\" /></a> &nbsp; \n";

  // Since we can only have one red and blue flag
  // Get the location of each.  This will be used in
  // conjunction with jQuery to make sure that anytime
  // a new flag is put into place, the old one is removed
  $FlagRed = getFlagCell( $dbMapID, 4 );
  $FlagBlue = getFlagCell( $dbMapID, 5 );

  // Build the Body
  $BODY .= "<input type=\"hidden\" id=\"EditMode\" value=\"1\" />\n";
  $BODY .= "<input type=\"hidden\" id=\"MapName\" value=\"".$mapDetails['vc_Name']."\" />\n";
  $BODY .= "<input type=\"hidden\" id=\"MapAuthor\" value=\"".$mapDetails['vc_Author']."\" />\n";
  $BODY .= "<input type=\"hidden\" id=\"MapID\" value=\"$MapID\" />\n";
  $BODY .= "<input type=\"hidden\" id=\"BrushID\" value=\"1\" />\n";
  $BODY .= "<input type=\"hidden\" id=\"BrushImage\" value=\"images/20_black.png\" />\n";
  $BODY .= "<input type=\"hidden\" id=\"BrushRadius\" value=\"1\" />\n";
  $BODY .= "<input type=\"hidden\" id=\"FlagRed\" value=\"$FlagRed\" />\n";
  $BODY .= "<input type=\"hidden\" id=\"FlagBlue\" value=\"$FlagBlue\" />\n";
  $BODY .= "<input type=\"hidden\" id=\"ButtonCellID\" value=\"0\" />\n";
  $BODY .= "<input type=\"hidden\" id=\"switchString\" value=\"{".$switchString."}\" />\n";
  $BODY .= "<input type=\"hidden\" id=\"bombString\" value=\"{".$bombString."}\" />\n";

  $BODY .= "<table class=\"map\">\n";

  // Step over the rows
  for( $y = 1; $y <= 50; $y++ )
  {
     "<tr>";

    // Step over the columns
    for( $x = 1; $x <= 50; $x++ )
    {
      // Calculate the cell position
      $cellPos = ( ( $y - 1 ) * 50 ) + $x;

      // Check to see if we already have something for this
      if( array_key_exists( $cellPos, $CellBrush ) )
      {
        $cell_brush = $CellBrush[$cellPos];
        $cell_image = getImage( $cell_brush );
      }
      else
      {
        $cell_brush = 1;
        $cell_image = "theme/"._THEME_NAME."/images/icons/15/background.png";
      }

      // Note that indentation has been removed to minimize rendered space
      $BODY .= "<td>";
      $BODY .= "<img class=\"image_$cellPos\" src=\"$cell_image\" />";
      $BODY .= "<input type=\"hidden\" name=\"CellID[$cellPos]\" class=\"cell_id\" value=\"$cellPos\" />";
      $BODY .= "<input type=\"hidden\" id=\"BrushID_$cellPos\" name=\"BrushID[$cellPos]\" class=\"cell_brush\" value=\"$cell_brush\" />";
      //$BODY .= "<input type=\"hidden\" name=\"CellX[$cellPos]\" class=\"cell_x\" value=\"$x\" />";
      //$BODY .= "<input type=\"hidden\" name=\"CellY[$cellPos]\" class=\"cell_y\" value=\"$y\" />";
      $BODY .= "</td>";
    }

     $BODY .="</tr>";

  }

  $BODY .= "</table>\n\n";


?>
