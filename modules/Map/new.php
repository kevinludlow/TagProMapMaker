<?php

  // Check to make sure that the basic security variable has been set
  // thus ensuring tht the file is not being loaded directly.
  if( ! defined( '_SECURITY_BASIC' ) )
    die( '' );

  // Create the map code
  $vc_MapCode = create_random_string( 32 );

  // Create the new record
  $query = "INSERT INTO "._DB_NAME."."._DB_PREFIX."_Map
              ( pk_ID,
                fk_UserID,
                vc_MapCode,
                vc_Name,
                dt_Created,
                dt_Updated )
            VALUES
              ( '',
                '$UserID',
                '$vc_MapCode',
                'Some Map',
                NOW(),
                NOW() )";

  $result = mysql_query( $query );

  // Redirect the user to the new map
  header( "Location: index.php?module=Map&op=edit&MapID=$vc_MapCode" );

?>
