<?php

  ///
  /// @file    index.php
  ///
  /// @author  Kevin Ludlow (ludlow@gmail.com)
  ///

  error_reporting( E_ALL );
  ini_set( 'display_errors', 0 );

  // Include the configuration file
  include( 'config/config.php' );

  // Connect to the database
  include( 'db/connect.php' );

  // Start the output buffer
  ob_start();

  // Start or continue the session
  session_start();

  // Include any required global function files
  include( "include/func.common.php" );

  // Get the UserID so that we can use it throughout
  // the entire program scope
  $UserID = getUser();

  // Get the requested module
  if( isset( $_GET['module'] ) )
    $module = $_GET['module'];
  else
    $module = NULL;

  // Define the available modules for security purposes
  $modules = array( 'Account', 'Active', 'Home', 'Map', 'Viewer' );

  if( ! in_array( $module, $modules ) )
    $module = "Account";

  // Load the appropriate template file
  include( "theme/"._THEME_NAME."/layout/TagProMapMaker.php" );

  // Load the appropriate module file
  include( "modules/$module/index.php" );

  // Once all of the pieces have been handled, determine
  // if the user is logged in or not.  We do this last in
  // case the user has logged in and the page has rendered
  // without the previous knowledge of a SESSION
  if( isUser() )
    $display_login = "logged in as <em>".getEmail( $UserID )."</em> ( <a href=\"index.php?module=Account&amp;reset=1\">logout</a> )";
  else
    $display_login = "<em>not logged in</em>\n";

  // Begin handling all of the post processing
  // Start by getting the buffer and flushing it
  $buffer = ob_get_contents();
  ob_end_clean();

  // Modify the contents of various page components
  $buffer = str_replace( '<%NAV%>', $NAV, $buffer );
  $buffer = str_replace( '<%BODY%>', $BODY, $buffer );
  $buffer = str_replace( '<%STATUS%>', $display_login, $buffer );

  // Finally output the buffer
  echo $buffer;

  include( 'db/disconnect.php' );

?>
