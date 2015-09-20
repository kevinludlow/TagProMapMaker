<?php

  // Build the navigation menu
  $NAV .= "<h3>Site Navigation</h3>\n";
  $NAV .= "<big>&middot;</big> <a href=\"index.php?module=Account\">About Map Designer</a><br/>\n";
  $NAV .= "<big>&middot;</big> <a href=\"index.php?module=Map&amp;op=list\">Your Maps</a><br/>\n";
  $NAV .= "<big>&middot;</big> <a href=\"index.php?module=Map&amp;op=new\">Create a New Map</a><br/>\n";
  $NAV .= "<big>&middot;</big> <a href=\"index.php?module=Active\">Active Maps</a><br/>\n";

  $BODY .= "<h3>Last 80 Active Maps</h3>\n\n";

  $BODY .= "<p>Listed below are the last 80 active maps in our system that have been edited.  Click on any of the maps to open the map view.  This will allow you to download the various map components and even test the map.  Please keep in mind that many of these maps are works-in-progress and may not be ready to be tested.</p>\n\n";

  // Start by getting the last 20 maps
  $query = "SELECT * FROM "._DB_NAME."."._DB_PREFIX."_Map
            WHERE pk_ID IN ( SELECT DISTINCT fk_MapID FROM "._DB_NAME."."._DB_PREFIX."_Map_Cells )
            ORDER BY dt_Updated DESC
            LIMIT 80";
  $result = mysql_query( $query );
  $numrows = mysql_num_rows( $result );

  for( $i = 0; $i < $numrows; $i++ )
  {
    // Fetch a row of data
    $row = mysql_fetch_assoc( $result );

    // Get the MapCode
    $MapID = $row['vc_MapCode'];

    $BODY .= "<div style=\"float: left; margin-right: 8px; margin-bottom: 8px;\">\n";
    $BODY .= "  <a href=\"index.php?module=Viewer&amp;op=view&amp;MapID=$MapID\">\n";
    $BODY .= "    <img src=\"tools/generatePNG.php?MapID=$MapID\" style=\"width: 100px; height: 100px; border: 1px solid #cccccc; padding: 2px;\" />\n";
    $BODY .= "  </a>\n";
    $BODY .= "</div>\n";

  }

?>
