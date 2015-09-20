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

  // Set the headers for a text file
  header("Content-type: text/plain");
  header("Content-Disposition: inline; filename=\"$filename.json\"");

  echo "  {\n\n\n";

  // #############################################
  // INFO

  echo "    \"info\":\n";
  echo "    {\n";
  echo "      \"name\": \"".$mapDetails['vc_Name']."\",\n";
  echo "      \"author\": \"".$mapDetails['vc_Author']."\"\n";
  echo "    },\n\n\n";

  // #############################################
  // SWITCHES

  // Get the switch buttonss
  $buttons = getSwitchButtons( $dbMapID );

  // Open the switches
  echo "    \"switches\":\n";
  echo "    {\n\n";

  // Step through the buttons
  foreach( $buttons as $i => $ButtonCellID )
  {
    // Get the switches for this button
    $switches = getSwitches( $dbMapID, $ButtonCellID );

    $buttonXY = getCellXY( $ButtonCellID );

    echo "      \"".$buttonXY['x'].",".$buttonXY['y']."\":\n";
    echo "      {\n";
    echo "        \"toggle\":\n";
    echo "        [\n";

    // Initialize a position counter since we need to
    // differentiate on the last row
    $j = 0;

    // Step through the button switches
    foreach( $switches as $CellID => $DefaultState )
    {
      // Get the position
      $posXY = getCellXY( $CellID, true );

      // Display the positions
      if( $j == count( $switches ) - 1 )
        echo "          { \"pos\": { \"x\": ".$posXY['x'].", \"y\": ".$posXY['y']." } }\n";
      else
        echo "          { \"pos\": { \"x\": ".$posXY['x'].", \"y\": ".$posXY['y']." } },\n";

      // Increment the counter
      $j++;

    } // end foreach switches...

    echo "        ]\n";

    // If this is the last entity, we can't put a comma
    // or JSON will throw an error
    if( $i == count( $buttons ) - 1 )
      echo "      }\n\n";
    else
      echo "      },\n\n";

  } // end foreach buttons...

  // Close the switches
  echo "    },\n\n\n";


  // #############################################


  // #############################################
  // DEFAULT STATES


  echo "    \"fields\":\n";
  echo "    {\n";

  // Get the switches for this button
  $switches = getSwitches( $dbMapID, 0 );

  // Initialize a counter since we have to differentiate
  // commas in certain lines
  $i = 0;

  // Step through the button switches
  foreach( $switches as $CellID => $DefaultState )
  {
    // Get the position
    $posXY = getCellXY( $CellID, true );

      if( $i == count( $switches ) - 1 )
        echo "      \"".$posXY['x'].",".$posXY['y']."\": { \"defaultState\": \"$DefaultState\" }\n";
      else
        echo "      \"".$posXY['x'].",".$posXY['y']."\": { \"defaultState\": \"$DefaultState\" },\n";

    // Increment the counter
    $i++;

  } // end foreach switches...

  echo "    }\n\n\n";


  echo "  }\n";

  // Disconnect from the database
  include( '../db/disconnect.php' );

?>
