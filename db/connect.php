<?php

  ///
  /// @file    connect.php
  ///
  /// @brief   Establish a connection with the database.
  ///
  /// @details The sole purpose of this script is to allow us to connect
  ///          to the database.
  ///
  ///          In the event that the connection can not be established with the 
  ///          database, the project will fail with the error message
  ///          provided in the function.  
  ///


  ///
  /// Check to make sure that the basic security variable has been set
  /// thus ensuring tht the file is not being loaded directly.
  ///

  if( ! defined( '_SECURITY_BASIC' ) )
    die( '' );

  ///
  /// The $dbh variable is used as the connector to the database.  
  ///
  /// Once the connection is made, it will remain throughout the script
  /// without the need to call a specific reference to the $dbh variable.
  ///

  $dbh = mysql_pconnect( _DB_HOST, _DB_USER, _DB_PASSWORD )
         or die( _DB_ERROR_MESSAGE );

  mysql_select_db( _DB_NAME );


?>
