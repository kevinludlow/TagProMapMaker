<?php

  function getMapID( $vc_MapCode )
  {
    // Create and execute the query
    $query = "SELECT pk_ID FROM "._DB_NAME."."._DB_PREFIX."_Map
              WHERE vc_MapCode = '$vc_MapCode'";
    $result = mysql_query( $query );
    $row = mysql_fetch_assoc( $result );

    // Get the MapID
    $MapID = $row['pk_ID'];

    // Return the MapID
    return $MapID;

  } // end function getMapID()


  function getImage( $BrushID )
  {
    switch( $BrushID )
    {
      case 1:
        return "theme/"._THEME_NAME."/images/icons/20/20_black.png";
        break;

      case 2:
        return "theme/"._THEME_NAME."/images/icons/20/20_wall.png";
        break;

      case 3:
        return "theme/"._THEME_NAME."/images/icons/20/20_floor_blank.png";
        break;

      case 4:
        return "theme/"._THEME_NAME."/images/icons/20/20_flag_red.png";
        break;

      case 5:
        return "theme/"._THEME_NAME."/images/icons/20/20_flag_blue.png";
        break;

      case 6:
        return "theme/"._THEME_NAME."/images/icons/20/20_floor_speed.png";
        break;

      case 7:
        return "theme/"._THEME_NAME."/images/icons/20/20_button.png";
        break;

      case 8:
        return "theme/"._THEME_NAME."/images/icons/20/20_floor_switch_off.png";
        break;

      case 9:
        return "theme/"._THEME_NAME."/images/icons/20/20_floor_switch_green.png";
        break;

      case 10:
        return "theme/"._THEME_NAME."/images/icons/20/20_mine.png";
        break;

      case 11:
        return "theme/"._THEME_NAME."/images/icons/20/20_bomb.png";
        break;

      case 12:
        return "theme/"._THEME_NAME."/images/icons/20/20_weapon.png";
        break;

      case 13:
        return "theme/"._THEME_NAME."/images/icons/20/20_floor_blue.png";
        break;

      case 14:
        return "theme/"._THEME_NAME."/images/icons/20/20_floor_red.png";
        break;

      default:
        return "theme/"._THEME_NAME."/images/icons/20/20_black.png";
        break;

    } // end switch BrushID

  } // end function getImage()


  function getImageFull( $BrushID )
  {
    switch( $BrushID )
    {
      case 1:
        return "black.png";
        break;

      case 2:
        return "wall.png";
        break;

      case 3:
        return "floor_blank.png";
        break;

      case 4:
        return "flag_red.png";
        break;

      case 5:
        return "flag_blue.png";
        break;

      case 6:
        return "floor_speed.png";
        break;

      case 7:
        return "button.png";
        break;

      case 8:
        return "floor_switch_off.png";
        break;

      case 9:
        return "floor_switch_green.png";
        break;

      case 10:
        return "mine.png";
        break;

      case 11:
        return "bomb.png";
        break;

      case 12:
        return "weapon.png";
        break;

      default:
        return "black.png";
        break;

    } // end switch BrushID

  } // end function getImageFull()


  function getMapDetails( $vc_MapCode )
  {
    // Create and execute the query
    $query = "SELECT * FROM "._DB_NAME."."._DB_PREFIX."_Map
              WHERE vc_MapCode = '$vc_MapCode'";
    $result = mysql_query( $query );
    $row = mysql_fetch_assoc( $result );

    // Return the result set
    return $row;

  } // end function getMapDetails()


  function getFlagCell( $MapID, $BrushID )
  {
    // Create and execute the query
    $query = "SELECT fk_CellID FROM "._DB_NAME."."._DB_PREFIX."_Map_Cells
              WHERE fk_MapID = '$MapID' AND fk_BrushID = '$BrushID'";
    $result = mysql_query( $query );
    $numrows = mysql_num_rows( $result );

    if( $numrows == 0 )
      return 0;
    else
    {
      $row = mysql_fetch_assoc( $result );

      return $row['fk_CellID'];
    }

  } // end function getFlagCell()


  function getColorMap()
  {
    // Initialize the Brushes array
    $Brushes = array();

    // Create and execute the query
    $query = "SELECT * FROM "._DB_NAME."."._DB_PREFIX."_Brush";
    $result = mysql_query( $query );
    $numrows = mysql_num_rows( $result );

    for( $i = 0; $i < $numrows; $i++ )
    {
      $row = mysql_fetch_array( $result );

      $BrushID = $row['pk_ID'];
      $vc_Hex = $row['vc_Hex'];

      $Brushes[$BrushID] = $vc_Hex;

    } // end for i

    return $Brushes;

  }


  function getSafeName( $vc_Name )
  {
    $safeName = preg_replace("/[^a-zA-Z0-9\s]/", "", $vc_Name );
    $safeName = str_replace( ' ', '_', $safeName );

    return $safeName;
  }


  function getSwitchButtons( $MapID )
  {
    // Initialize the buttons return array
    $buttons = array();

    // Get all of the grated switches
    $query = "SELECT "._DB_NAME."."._DB_PREFIX."_Map_Switches.fk_MapID,
                     "._DB_NAME."."._DB_PREFIX."_Map_Switches.fk_ButtonCellID,
                     "._DB_NAME."."._DB_PREFIX."_Map_Switches.fk_CellID,
                     "._DB_NAME."."._DB_PREFIX."_Map_Cells.fk_BrushID
              FROM "._DB_NAME."."._DB_PREFIX."_Map_Switches
              LEFT JOIN "._DB_NAME."."._DB_PREFIX."_Map_Cells
              ON "._DB_NAME."."._DB_PREFIX."_Map_Switches.fk_MapID = "._DB_NAME."."._DB_PREFIX."_Map_Cells.fk_MapID
              AND "._DB_NAME."."._DB_PREFIX."_Map_Switches.fk_CellID = "._DB_NAME."."._DB_PREFIX."_Map_Cells.fk_CellID
              WHERE "._DB_NAME."."._DB_PREFIX."_Map_Switches.fk_MapID = '$MapID'
              AND ( fk_BrushID = 8 OR fk_BrushID = 9 )
              GROUP BY fk_ButtonCellID";

    $result = mysql_query( $query );
    $numrows = mysql_num_rows( $result );

    // Step through the rows
    for( $i = 0; $i < $numrows; $i++ )
    {
      // Fetch a row of data
      $row = mysql_fetch_array( $result );

      $buttons[] = $row['fk_ButtonCellID'];

    } // end for i

    // Return the buttons array to the user
    return $buttons;

  } // end function getSwitchButtons()


  function getSwitches( $MapID, $ButtonID, $switchType = NULL )
  {
    // Initialize the switches return array
    $switches = array();

    // Get all of the grated switches
    $query = "SELECT "._DB_NAME."."._DB_PREFIX."_Map_Switches.fk_MapID,
                     "._DB_NAME."."._DB_PREFIX."_Map_Switches.fk_ButtonCellID,
                     "._DB_NAME."."._DB_PREFIX."_Map_Switches.fk_CellID,
                     "._DB_NAME."."._DB_PREFIX."_Map_Cells.fk_BrushID
              FROM "._DB_NAME."."._DB_PREFIX."_Map_Switches
              LEFT JOIN "._DB_NAME."."._DB_PREFIX."_Map_Cells
              ON "._DB_NAME."."._DB_PREFIX."_Map_Switches.fk_MapID = "._DB_NAME."."._DB_PREFIX."_Map_Cells.fk_MapID
              AND "._DB_NAME."."._DB_PREFIX."_Map_Switches.fk_CellID = "._DB_NAME."."._DB_PREFIX."_Map_Cells.fk_CellID
              WHERE "._DB_NAME."."._DB_PREFIX."_Map_Switches.fk_MapID = '$MapID'";

    // If the switchType has not been provided, then we will assume
    // we're trying to get ALl switch types for this button.  Otherwise
    // we will get the specified switchTypes
    if( $switchType == 'grate' )
      $query .= "              AND ( fk_BrushID = 8 OR fk_BrushID = 9 )";
    else if( $switchType == 'bomb' )
      $query .= "              AND ( fk_BrushID = 11 )";
    else
      $query .= "              AND ( fk_BrushID = 8 OR fk_BrushID = 9 OR fk_BrushID = 11 )";

   // If the ButtonID is set to 0, then we are requesting
   // to get ALL rows rather than just the ones tied to a button
   //
   // This is useful for creating the fields defaultstate
   if( $ButtonID > 0 )
     $query .= "              AND fk_ButtonCellID = '$ButtonID'";

    $result = mysql_query( $query );
    $numrows = mysql_num_rows( $result );

    // Step through the rows
    for( $i = 0; $i < $numrows; $i++ )
    {
      // Fetch a row of data
      $row = mysql_fetch_array( $result );

      // Get the BrushID and CellID
      $fk_BrushID = $row['fk_BrushID'];
      $fk_CellID = $row['fk_CellID'];

      if( $fk_BrushID == 8 )
        $switches[$fk_CellID] = "off";
      else if( $fk_BrushID == 9 )
        $switches[$fk_CellID] = "on";
      else if( $fk_BrushID == 11 )
        $switches[$fk_CellID] = "on";

    } // end for i

    // Return the switches array to the user
    return $switches;

  } // end function getSwitches()



  function getCellXY( $CellID )
  {
    // Calculate the x and y position based on the cell
    // Note that this assumes the grid starts at (0, 0)
    $y = floor( ( $CellID - 1 ) / 50 );
    $x = ( $CellID - 1 ) - ( $y * 50 );

    $pos['y'] = $y;
    $pos['x'] = $x;

    // Return the x/y array
    return $pos;

  } // end function getCellXY()

?>
