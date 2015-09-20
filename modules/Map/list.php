<?php

  // Check to make sure that the basic security variable has been set
  // thus ensuring tht the file is not being loaded directly.
  if( ! defined( '_SECURITY_BASIC' ) )
    die( '' );

  // Build the navigation menu
  $NAV .= "<h3>Site Navigation</h3>\n";
  $NAV .= "<big>&middot;</big> <a href=\"index.php?module=Account\">About Map Designer</a><br/>\n";
  $NAV .= "<big>&middot;</big> <a href=\"index.php?module=Map&amp;op=list\">Your Maps</a><br/>\n";
  $NAV .= "<big>&middot;</big> <a href=\"index.php?module=Map&amp;op=new\">Create a New Map</a><br/>\n";
  $NAV .= "<big>&middot;</big> <a href=\"index.php?module=Active\">Active Maps</a><br/>\n";

  $BODY .= "<h3>Your Maps</h3>\n";

  // Build the starting array
  $query = "SELECT * FROM "._DB_NAME."."._DB_PREFIX."_Map
            WHERE fk_UserID = '$UserID'";
  $result = mysql_query( $query );
  $numrows = mysql_num_rows( $result );

  $BODY .= "<table class=\"mapListing\">\n";
  $BODY .= "<tr>\n";
  $BODY .= "  <th>Map Name</th>\n";
  $BODY .= "  <th>Map ID</th>\n";
  $BODY .= "  <th>Created</th>\n";
  $BODY .= "  <th>Last Updated</th>\n";
  $BODY .= "</tr>\n";

  // Build the PHP array of elements
  if( $numrows > 0 )
  {
    for( $i = 0; $i < $numrows; $i++ )
    {
      $row = mysql_fetch_assoc( $result );

      $BODY .= "<tr>\n";
      $BODY .= "  <td>".$row['vc_Name']."</td>\n";
      $BODY .= "  <td><a href=\"index.php?module=Map&amp;op=edit&amp;MapID=".$row['vc_MapCode']."\" style=\"display: block;\" >".$row['vc_MapCode']."</a></td>\n";
      $BODY .= "  <td>".$row['dt_Created']."</td>\n";
      $BODY .= "  <td>".$row['dt_Updated']."</td>\n";
      $BODY .= "</tr>\n";

    } // end for i = 0...
  }
  else
  {
    $BODY .= "<tr>\n";
    $BODY .= "  <td colspan=\"4\" class=\"empty\">\n";
    $BODY .= "    You have not created any maps<br/><br/>\n";
    $BODY .= "    <a href=\"index.php?module=Map&amp;op=new\">Create a New Map</a>\n";
    $BODY .= "  </td>\n";
    $BODY .= "</tr>\n";
  }

  $BODY .= "</table>\n\n";

?>
