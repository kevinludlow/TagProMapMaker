<?php

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

  echo "convert ";

  // Step over the rows
  for( $y = 1; $y <= 50; $y++ )
  {

    // Let imageMagick know that we are starting
    // a new row here
    echo " \( ";

    // Step over the columns
    for( $x = 1; $x <= 50; $x++ )
    {
      // Calculate the cell position
      $cellPos = ( ( $y - 1 ) * 50 ) + $x;

      // Check to see if we already have something for this
      if( array_key_exists( $cellPos, $CellBrush ) )
      {
        $cell_brush = $CellBrush[$cellPos];
        $cell_image = getImageFull( $cell_brush );
      }
      else
      {
        $cell_brush = 1;
        $cell_image = "black.png";
      }

      echo "$cell_image ";

    } // end stepping through the column

    // We'll close off the column letting imageMagick
    // know that we are appending horizontally
    echo "+append \) ";

  }

  echo " -append sampleMap.png";



?>
