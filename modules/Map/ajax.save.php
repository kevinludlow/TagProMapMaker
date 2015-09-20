<?php

  // Include the configuration
  include( '../../config/config.php' );

  // Connect with the database
  include( '../../db/connect.php' );

  // Include any necessary functionality
  include( '../../include/func.common.php' );

  // Start or continue the session
  session_start();

  // Kill the script if the user is not logged in
  if( ! isUser() )
    die();

  // Get the UserID
  $UserID = getUser();

  // Get the MapCode (displayed to the user as the MapID)
  $vc_MapCode = $_POST["MapID"];

  // Start by getting the actual MapID
  $query = "SELECT pk_ID FROM "._DB_NAME."."._DB_PREFIX."_Map
            WHERE fk_UserID = '$UserID' AND vc_MapCode = '$vc_MapCode'";
  $result = mysql_query( $query );
  $row = mysql_fetch_assoc( $result );

  // Get the MapID
  $MapID = $row['pk_ID'];

  // Now turn the BrushIDString into an array
  $Cells = explode( ',', $_POST["BrushIDString"] );

  // Start by deleting all of the records for this map
  $query = "DELETE FROM "._DB_NAME."."._DB_PREFIX."_Map_Cells
            WHERE fk_MapID = '$MapID'";

  $result = mysql_query( $query );

  // Step through the records and insert them
  foreach( $Cells as $i => $BrushID )
  {
    $CellID = $i + 1;

    if( $BrushID > 1 )
    {
      $query = "INSERT INTO "._DB_NAME."."._DB_PREFIX."_Map_Cells
                  ( fk_MapID,
                    fk_CellID,
                    fk_BrushID )
                VALUES
                  ( '$MapID',
                    '$CellID',
                    '$BrushID' )";

      $result = mysql_query( $query );
    }
  }



  // Process all of the grated switches

  // Start by deleting all of the button records for this map
  $query = "DELETE FROM "._DB_NAME."."._DB_PREFIX."_Map_Switches
            WHERE fk_MapID = '$MapID'";

  $result = mysql_query( $query );

  // Get all of the Switches
  // Note that the groups are separated by hyphens with
  // the first entity being the button
  $outerSwitches = explode( '-', $_POST["SwitchString"] );

  // Step through all of these groups of switches
  foreach( $outerSwitches as $i => $innerSwitch )
  {
    // Now that we have a single button group, we need to
    // separate the components
    $switches = explode( ",", $innerSwitch );

    // We only care if there is more than one entity
    if( count( $switches ) > 1 )
    {
      // The first element contains the ButtonID
      $ButtonCellID = $switches[0];

      // Step through the remaining entities and insert them
      for( $j = 1; $j < count( $switches ); $j++ )
      {
        // Get the CellID
        $CellID = $switches[$j];

        // Create and execute the query
        $query = "INSERT INTO "._DB_NAME."."._DB_PREFIX."_Map_Switches
                    ( fk_MapID,
                      fk_ButtonCellID,
                      fk_CellID )
                  VALUES
                    ( '$MapID',
                      '$ButtonCellID',
                      '$CellID' )";

        $result = mysql_query( $query );

      } // end for j

    } // end if count switches > 1

  } // end foreach()




  // Process all of the bombs

  // Get all of the bombs
  // Note that the groups are separated by hyphens with
  // the first entity being the button
  $outerBombs = explode( '-', $_POST["BombString"] );

  // Step through all of these groups of bombs
  foreach( $outerBombs as $i => $innerBomb )
  {
    // Now that we have a single button group, we need to
    // separate the components
    $bombs = explode( ",", $innerBomb );

    // We only care if there is more than one entity
    if( count( $bombs ) > 1 )
    {
      // The first element contains the ButtonID
      $ButtonCellID = $bombs[0];

      // Step through the remaining entities and insert them
      for( $j = 1; $j < count( $bombs ); $j++ )
      {
        // Get the CellID
        $CellID = $bombs[$j];

        // Create and execute the query
        $query = "INSERT INTO "._DB_NAME."."._DB_PREFIX."_Map_Switches
                    ( fk_MapID,
                      fk_ButtonCellID,
                      fk_CellID )
                  VALUES
                    ( '$MapID',
                      '$ButtonCellID',
                      '$CellID' )";

        $result = mysql_query( $query );

      } // end for j

    } // end if count bombs > 1

  } // end foreach()



  // Finally we need to update the map name and when it was
  // last updated
  $query = "UPDATE "._DB_NAME."."._DB_PREFIX."_Map
            SET vc_Name = '".addslashes( $_POST["MapName"] )."',
                vc_Author = '".addslashes( $_POST["MapAuthor"] )."',
                dt_Updated = NOW()
            WHERE vc_MapCode = '$vc_MapCode'";
  $result = mysql_query( $query );

  // Disconnect from the database
  include( '../../db/disconnect.php' );

?>
