<?php

  // Check to make sure that the basic security variable has been set
  // thus ensuring tht the file is not being loaded directly.
  if( ! defined( '_SECURITY_BASIC' ) )
    die( '' );

  // Create the filemap for the core loading process
  $filemap = array( 'login'    => 'google_authenticate.php',
                    'logout'   => 'google_authenticate.php',
                    'redirect' => 'redirect.php',
                    'default'  => 'google_authenticate.php' );

  $filename = load_op( $module, $filemap );

  // Include the sub-module file
  include( $filename );

?>
