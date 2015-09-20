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

  // Initialize the error flag so we can compile a list of
  // errors for the user in one swoop
  $ErrorFlag = false;

  // We need to make sure that both of the flags have
  // been set somewhere on the map.  If they have not
  // been, the map test will throw an error
  $FlagRed = getFlagCell( $dbMapID, 4 );
  $FlagBlue = getFlagCell( $dbMapID, 5 );

  if( $FlagRed == 0 )
  {
    $ErrorFlag = true;
    echo "<p>Error: Your map MUST contain a Red Flag in order to generate.<p>";
  }

  if( $FlagBlue == 0 )
  {
    $ErrorFlag = true;
    echo "<p>Error: Your map MUST contain a Blue Flag in order to generate.</p>";
  }

  // If we have detected any errors, kill the script
  if( $ErrorFlag )
    die();

  // Get the header redirection 
  $redirect = shell_exec( "php loadExternal.php $MapID" );

  // Send the user to the test map
  header( "Location: $redirect" );

  // Disconnect from the database
  include( '../db/connect.php' );

?>
