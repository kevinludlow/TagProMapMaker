<?php

  // Check to make sure that the basic security variable has been set
  // thus ensuring tht the file is not being loaded directly.
  if( ! defined( '_SECURITY_BASIC' ) )
    die( '' );

  // Include the map functionality
  include( 'include/func.map.php' );

  // Create the filemap for the core loading process
  $filemap = array( 'image'   => 'image.php',
                    'view'    => 'view.php',
                    'view'    => 'view.php',
                    'default' => 'view.php' );

  $filename = load_op( $module, $filemap );

  // Include the sub-module file
  include( $filename );

?>
