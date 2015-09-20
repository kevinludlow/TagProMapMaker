<?php

  // Include the configuration file
  include( '../config/config.php' );

  // Connect to the database
  include( '../db/connect.php' );

  // Include any required global function files
  include( '../include/func.common.php' );
  include( '../include/func.map.php' );

  // Get the MapID from the URL
  $MapID = $argv[1];

  // Get the database MapID
  $dbMapID = getMapID( $MapID );

  if( ! $dbMapID )
    die();

  // Define the landing URL
  //$url = "http://tagpro-maptest.koalabeast.com/testmap";
  $url = _PATH_TESTMAP;

  // Get both of the requested maps
  $png = file_get_contents( _PATH_WEB."/tools/generatePNG.php?MapID=$MapID");
  $json = file_get_contents( _PATH_WEB."/tools/generateJSON.php?MapID=$MapID");

  // Create the form field separator manually
  $delimiter = '-------------' . uniqid();

  // Manually create the file upload fields
  $fileFields = array( 'logic'  => array( 'type'    => 'text/plain',
                                          'content' => $json ),

                       'layout' => array( 'type' => 'image/png',
                                          'content' => $png ) );

  // Initialize the data that will be posted
  $data = '';

  /*
  // populate normal fields first (simpler)
  foreach ($postFields as $name => $content) {
     $data .= "--" . $delimiter . "\r\n";
      $data .= 'Content-Disposition: form-data; name="' . $name . '"';
      // note: double endline
      $data .= "\r\n\r\n";
  }
  */

  // populate file fields
  foreach( $fileFields as $name => $file )
  {
    $data .= "--" . $delimiter . "\r\n";

    // "filename" attribute is not essential; server-side scripts may use it
    $data .= 'Content-Disposition: form-data; name="' . $name . '";' .
             ' filename="' . $name . '"' . "\r\n";
    // this is, again, informative only; good practice to include though
    $data .= 'Content-Type: ' . $file['type'] . "\r\n";
    // this endline must be here to indicate end of headers
    $data .= "\r\n";
    // the file itself (note: there's no encoding of any kind)
    $data .= $file['content'] . "\r\n";
  }

  // Create the final delimiter
  $data .= "--" . $delimiter . "--\r\n";

  // Once everything has been setup we need to
  // run the cURL process
  $handle = curl_init($url);
  curl_setopt($handle, CURLOPT_POST, true);
  curl_setopt($handle, CURLOPT_HTTPHEADER , array( 'Content-Type: multipart/form-data; boundary=' . $delimiter,
                                                   'Content-Length: ' . strlen($data)));  
  curl_setopt($handle, CURLOPT_POSTFIELDS, $data);

  // Suppress the output from automaticlly appearing on the screen
  curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);

  // Get the response back from the user
  $response = curl_exec( $handle );

  // Once we get the response back from the TagPro server
  // we need to parse out the return feed URL
  preg_match( '#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#', $response, $match );

  // Get the redirection URL and send the user there
  $redirect = $match[0];

  // Display the return text
  echo $redirect;

  // Disconnect from the database
  include( '../db/connect.php' );

?>
