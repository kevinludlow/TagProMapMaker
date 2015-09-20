<?php

  // Check to make sure that the UserID has been set by our
  // system and that the User is logged into their Google Account
  function isUser()
  {
    // First check to see if the override has been enabled
    if( _OVERRIDE )
    {
      return true;
    }
    else
    {

      if( isset( $_SESSION["token"] ) && isset( $_SESSION["UserID"] ) )
      {
        if( $_SESSION["UserID"] > 0 )
          return true;
        else
          return false;
      }
      else
      {
        return false;
      }

    } // end else

  } // end function isUser()


  function getUser()
  {
    // Check to see if the override has been 
    // turned on.  If it has return that userID
    if( _OVERRIDE )
    {
      return _OVERRIDE_USER_ID;
    }
    else
    {
      return $_SESSION["UserID"];
    }

  } // end function getUser()


  function getEmail( $UserID = NULL )
  {

    if( $UserID == NULL )
      $UserID = getUser();

    $query = "SELECT vc_Email FROM "._DB_NAME."."._DB_PREFIX."_User
              WHERE pk_ID = '$UserID'";
    $result = mysql_query( $query );
    $row = mysql_fetch_assoc( $result );

    return $row['vc_Email'];

  } // end function getEmail()


  function countUsers()
  {
    $query = "SELECT COUNT(pk_ID) FROM "._DB_NAME."."._DB_PREFIX."_User";
    $result = mysql_query( $query );
    $row = mysql_fetch_row( $result );

    return $row[0];
  }

  function countMaps()
  {
    $query = "SELECT COUNT(pk_ID) FROM "._DB_NAME."."._DB_PREFIX."_Map";
    $result = mysql_query( $query );
    $row = mysql_fetch_row( $result );

    return $row[0];
  }

  function get_op()
  {

    // Get the sub-op selection from the URL
    if( isset( $_GET['op'] ) )
      $op = $_GET['op'];
    else
      $op = NULL;

    // Return the op
    return $op;

  } // end of function get_op()



  function load_op( $module, $filemap )
  {

    // Get the op
    $op = get_op();

    // Check to see if the requested op exists in
    // the filemap array
    if( array_key_exists( $op, $filemap ) )
      $filename = $filemap[$op];
    else
      $filename = $filemap['default'];

    // Build the fully qualified inclusion file
    $filename = "modules/$module/$filename";

    // Return the filename to use
    return $filename;

  } // end of function load_op()



  function create_random_string( $length, $MD5 = FALSE )
  {

    // Initialize the return string
    $return_string = '';

    // Create a string of the available characters
    // for the random string.  The function will be
    // accessing this string as an array.
    $chars = '';
    $chars .= 'abcdefghijklmnopqrstuvwxyz';
    //$chars .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $chars .= '0123456789';

    // Check to see if the length is within the 
    // boundaries.  If it is not, we will correct
    // it as necessary.
    if( $length < 0 )
      $length = 0;
    else if( $length > 255 )
      $length = 255;

    // Create the random string
    for( $i = 0; $i < $length; $i++ )
    {
      // Generate a random position of the chars string
      $rand = rand( 0, strlen( $chars ) - 1 );

      // Append the return string
      $return_string .= $chars[$rand];
    }

    // If the MD5 flag was triggered, encode the value
    // as an MD5 hash.
    if( $MD5 )
      $return_string = MD5( $return_string );

    // Return the random string
    return $return_string;

  } // end function create_random_string()


?>
