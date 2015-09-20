<?php

  // Check to make sure that the basic security variable has been set
  // thus ensuring tht the file is not being loaded directly.
  if( ! defined( '_SECURITY_BASIC' ) )
    die( '' );

  // Make sure the user is logged in to access this section
  if( ! isUser() )
  {
    header( "Location: index.php?module=Account" );
    die();
  }

  // Include the map functionality
  include( 'include/func.map.php' );

  // Create the filemap for the core loading process
  $filemap = array( 'edit'             => 'edit.php',
                    'list'             => 'list.php',
                    'new'              => 'new.php',
                    'save'             => 'save.php',
                    'default'          => 'list.php' );

  $filename = load_op( $module, $filemap );

  // Include the sub-module file
  include( $filename );

?>
