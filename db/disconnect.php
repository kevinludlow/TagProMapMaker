<?php

  ///
  /// @file    disconnect.php
  ///
  /// @brief   Destroy the active database connection.
  ///
  /// @details The sole purpose of this script is to allow us to destroy the
  ///          active database connection.
  ///
  ///          If no active connection exists, this function will still attempt
  ///          to destroy a connection.  No error message will be returned.
  ///

  ///
  /// Check to make sure that the basic security variable has been set
  /// thus ensuring tht the file is not being loaded directly.
  ///

  if( ! defined( '_SECURITY_BASIC' ) )
    die( '' );

  ///
  /// Disconnect from the database.
  ///

  mysql_close( $dbh );


?>
