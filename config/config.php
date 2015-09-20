<?php

  ///
  /// @file    config.php
  ///
  /// @brief   Define the configuration variables for the application
  ///
  /// @details The config.php script creates standard PHP definition
  ///          variables (constants) that can be used throughout the entire system.
  ///          user class, and check on basic security measures.
  ///
  /// @author  Kevin Ludlow (ludlow@gmail.com)
  ///

  // Override the general settings and automatically log
  // the user in.  If you are running the development portion
  // on an IP address or simply do not want to authenticate
  // with Google, you will need to set these components as true
  // and ensure the UserID exists in the _User database
  define( '_OVERRIDE', 'true' );
  define( '_OVERRIDE_USER_ID', '1' );

  // Define the database connection variables

  define( '_DB_HOST', '' );
  define( '_DB_USER', '' );
  define( '_DB_PASSWORD', '' );
  define( '_DB_NAME', '' ); 
  define( '_DB_PREFIX', '' );
  define( '_DB_ERROR_MESSAGE', 'There was a problem connecting to the database.' );

  // Define the path variables

  define( '_PATH_WEB', 'http://www.pathto/application/' );
  define( '_PATH_BASE', '/var/www/html/path/to/application/' );

  define( '_PATH_CONVERT', '/usr/local/path/to/ImageMagick/ImageMagick-6.8.6-3/utilities/convert' );
  define( '_PATH_TESTMAP', 'http://173.230.145.241/testmap' );

  // Define the Google Authentication variables

  define( '_GOOGLE_CLIENT_ID', 'your google developer client id' );
  define( '_GOOGLE_CLIENT_SECRET', 'your google client secret' );
  define( '_GOOGLE_REDIRECT_URL', 'http://www.pathto/application/index.php?module=Account' );
  define( '_GOOGLE_DEVELOPER_KEY', 'your google developer key' );

  // Define the theme variables

  define( '_THEME_TITLE', 'TagPro Map Designer (v2.00.00)' );
  define( '_THEME_NAME', 'Default' );

  // Define the security variables

  define( '_SECURITY_BASIC', 'TagProMapMaker' );


?>
