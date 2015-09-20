<?php

  // Include the configuration file
  include( '../config/config.php' );

  // Connect to the database
  include( '../db/connect.php' );

  // Include any required global function files
  include( '../include/func.common.php' );
  include( '../include/func.map.php' );

  // Get the MapID from the URL
  $MapID = $_GET['MapID'];

  // Get the database MapID
  $dbMapID = getMapID( $MapID );

  if( ! $dbMapID )
    die();

  // Get the map details
  $mapDetails = getMapDetails( $MapID );

  // Get a safely formatted filename
  $filename = getSafeName( $mapDetails['vc_Name'] );

  $colorMap = getColorMap();

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

  // Initialize the previous color so we don't have to frequently
  // switch back and forth
  $lastHex = "";

  $command = _PATH_CONVERT." -size 50x50 xc:black ";

  // Step over the rows
  for( $y = 1; $y <= 50; $y++ )
  {
    // Step over the columns
    for( $x = 1; $x <= 50; $x++ )
    {
      // Calculate the cell position
      $cellPos = ( ( $y - 1 ) * 50 ) + $x;

      // Check to see if we already have something for this
      if( array_key_exists( $cellPos, $CellBrush ) )
      {
        $cell_brush = $CellBrush[$cellPos];
        $color_map = $colorMap[$cell_brush];
      }
      else
      {
        $cell_brush = 1;
        $color_map = '000000';
      }

      // Since we are using a human-logical x,y grid,
      // we need to change the X and Y pos to 0,0 based
      $XPos = $x - 1;
      $YPos = $y - 1;

      // Only change the color if it is different from
      // the previously used color
      if( $color_map != $lastHex )
      {
        $command .= " -fill \"#$color_map\" ";
        $lastHex = $color_map;
      }

      $command .= " -draw 'point $XPos,$YPos' ";

    } // end for x...

  } // end for y...

  $command .= " PNG24:fd:1";

  // Set the headers for a png
  header("Content-type: image/png");
  header("Content-Disposition: inline; filename=\"$filename.png\"");

  // Execute the imageMagick command
  passthru( $command );

  // Disconnect from the database
  include( '../db/disconnect.php' );

?>
