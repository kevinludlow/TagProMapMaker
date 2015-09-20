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

  // Build the navigation menu
  $NAV .= "    <h3>Site Navigation</h3>\n";
  $NAV .= "    <big>&middot;</big> <a href=\"index.php?module=Account\">About Map Designer</a><br/>\n";
  $NAV .= "    <big>&middot;</big> <a href=\"index.php?module=Map&amp;op=list\">Your Map Listing</a><br/>\n";
  $NAV .= "    <big>&middot;</big> <a href=\"index.php?module=Active\">Active Maps</a><br/>\n";

  $NAV .= "    <h3>Map Info</h3>\n";
  $NAV .= "    <strong>Name:</strong> ".$mapDetails['vc_Name']."<br/>\n";
  $NAV .= "    <strong>Created:</strong> ".date( 'm/d/Y', strtotime( $mapDetails['dt_Created'] ) )."<br/>\n";
  $NAV .= "    <strong>Last Updated:</strong> ".date( 'm/d/Y', strtotime( $mapDetails['dt_Updated'] ) )."<br/>\n";

  $NAV .= "    <h3>Testing Options</h3>\n";
  $NAV .= "    <big>&middot;</big> <a href=\"tools/generatePNG.php?MapID=$MapID\"> Download Map PNG</a><br/>\n";
  $NAV .= "    <big>&middot;</big> <a href=\"tools/generateJSON.php?MapID=$MapID\"> Download Map JSON</a><br/>\n";
  $NAV .= "    <big>&middot;</big> <a href=\"tools/loadTestMap.php?MapID=$MapID\" target=\"_BLANK\"> Test Map</a>\n";

  // TODO Add a statistics component


  // Build the Body
  $BODY .= "<table class=\"mapview\">\n";

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
        $cell_image = "images/20_black.png";
      }

      // Note that indentation has been removed to minimize rendered space
      $BODY .= "<td>";
      $BODY .= "<img class=\"image_$cellPos\" src=\"$cell_image\" />";
      $BODY .= "</td>";
    }

     $BODY .="</tr>";

  }

  $BODY .= "</table>\n\n";


?>
