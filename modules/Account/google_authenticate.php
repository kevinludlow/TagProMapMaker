<?php

########## Google Settings.. Client ID, Client Secret #############
$google_client_id       = _GOOGLE_CLIENT_ID;
$google_client_secret   = _GOOGLE_CLIENT_SECRET;
$google_redirect_url    = _GOOGLE_REDIRECT_URL;
$google_developer_key   = _GOOGLE_DEVELOPER_KEY;

########## MySql details #############
$db_username = ""; //Database Username
$db_password = ""; //Database Password
$hostname = ""; //Mysql Hostname
$db_name = ''; //Database Name
###################################################################

//include google api files
require_once 'tools/src/Google_Client.php';
require_once 'tools/src/contrib/Google_Oauth2Service.php';

//start session
//session_start();

$gClient = new Google_Client();
$gClient->setApplicationName('Login to TagProDesigner');
$gClient->setClientId($google_client_id);
$gClient->setClientSecret($google_client_secret);
$gClient->setRedirectUri($google_redirect_url);
$gClient->setDeveloperKey($google_developer_key);

$google_oauthV2 = new Google_Oauth2Service($gClient);

//If user wish to log out, we just unset Session variable
if (isset($_REQUEST['reset']))
{
  // Ensure the SESSION is destroyed by initializing the SESSION
  // variable as an array
  $_SESSION = array();

  // Destroy the session
  session_destroy();

  //unset($_SESSION['token']);
  $gClient->revokeToken();
  header('Location: ' . filter_var($google_redirect_url, FILTER_SANITIZE_URL));
}

if (isset($_GET['code']))
{
    $gClient->authenticate($_GET['code']);
    $_SESSION['token'] = $gClient->getAccessToken();
    header('Location: ' . filter_var($google_redirect_url, FILTER_SANITIZE_URL));
    return;
}


if (isset($_SESSION['token']))
{
        $gClient->setAccessToken($_SESSION['token']);
}


  if ($gClient->getAccessToken())
  {
    // Get user details if user is logged in
    $user                 = $google_oauthV2->userinfo->get();
    $user_id              = $user['id'];
    $user_name            = filter_var($user['name'], FILTER_SANITIZE_SPECIAL_CHARS);
    $email                = filter_var($user['email'], FILTER_SANITIZE_EMAIL);
    $profile_url          = filter_var($user['link'], FILTER_VALIDATE_URL);
    $profile_image_url    = filter_var($user['picture'], FILTER_VALIDATE_URL);
    $personMarkup         = "$email<div><img src='$profile_image_url?sz=50'></div>";
    $_SESSION['token']    = $gClient->getAccessToken();
  }
  else
  {
    // Get the Google Login URL
    $authUrl = $gClient->createAuthUrl();
  }


  // Display the content based on the user's status
  if( isset( $authUrl ) && ( ! _OVERRIDE ) )
  {
    // The user IS NOT logged in

    // Create the authentication image
    $authText = "<p>To create an account or to login to your existing account, click on the Google image below.</p>";
    $authText .= "<p style=\"margin-left: 20px;\"><a class=\"login\" href=\"$authUrl\"><img src=\"theme/Default/images/API/google-login-button.png\" /></a></p>";

    // Build the navigation menu
    $NAV .= "<h3>Site Navigation</h3>\n";
    $NAV .= "<em>You must <a href=\"$authUrl\">create an account</a> or <a href=\"$authUrl\">login</a> before you can navigate the site.</em>\n";

  }
  else
  {
    // The user IS ALREADY logged in

    if( _OVERRIDE )
    {
      $UserID = _OVERRIDE_USER_ID;
    }
    else
    {
      // Check the User in our database
      $query = "SELECT pk_ID FROM "._DB_NAME."."._DB_PREFIX."_User WHERE TRIM(fk_GoogleID)='$user_id'";
      $result = mysql_query( $query );
      $numrows = mysql_num_rows( $result );

      if( $numrows > 0 )
      {
        $row = mysql_fetch_assoc( $result );
  
        // Set the UserID into the local system can use it
        $UserID = $row['pk_ID'];
        $_SESSION["UserID"] = $UserID;
  
      }
      else
      {
        // Inser the user into the database
        $query = "INSERT INTO "._DB_NAME."."._DB_PREFIX."_User
                    ( fk_GoogleID,
                      vc_Name,
                      vc_Email,
                      vc_Link,
                      vc_Image )
                  VALUES
                    ( $user_id,
                      '$user_name',
                      '$email',
                      '$profile_url',
                      '$profile_image_url' )";
  
        $result = mysql_query( $query );

        $UserID = mysql_insert_id();
        $_SESSION["UserID"] = $UserID;

      } // end else

    } // end if _OVERRIDE

    // Build the navigation menu
    $NAV .= "<h3>Site Navigation</h3>\n";
    $NAV .= "<big>&middot;</big> <a href=\"index.php?module=Account\">About Map Designer</a><br/>\n";
    $NAV .= "<big>&middot;</big> <a href=\"index.php?module=Map&amp;op=list\">Your Maps</a><br/>\n";
    $NAV .= "<big>&middot;</big> <a href=\"index.php?module=Map&amp;op=new\">Create a New Map</a><br/>\n";
    $NAV .= "<big>&middot;</big> <a href=\"index.php?module=Active\">Active Maps</a><br/>\n";

    // Create the logout text

    $authText = "<p style=\"margin-left: 20px;\"><em>Currently logged in as ".getEmail( $UserID )."</em>.<br/><em><a href=\"index.php?module=Account&reset=1\">Logout of TagPro Map Designer</a></em></p>";

  } // end else not logged in

  // Create the body text
  $BODY .= "<h3>Welcome to TagPro Map Designer v2.0</h3>\n";

  $BODY .= "<p>It's been well over a year since I last supported this MapMaker.  But with more than <strong>".number_format( countUsers() )."</strong> users having created more than <strong>".number_format( countMaps() )."</strong>  maps, I thought it was time to open it back up to the general public.  I realize there are competing and even much better map programs available at this point.  But for anyone looking to get involved with a fun project, I'd love your help!</p>\n";

  $BODY .= "<p>The GitHub project can be found here under <a href=\"https://github.com/kevinludlow/TagProMapMaker\">TagProMapMaker</a>.</p>\n\n";

  $BODY .= "<h4>Getting an Account</h4>\n";

  $BODY .= "<p>Map Designer uses the Google Authorization for account creation and management.</p>\n";

  $BODY .= "$authText\n";

  $BODY .= "<h4>Help us Out!</h4>\n";

  $BODY .= "<p>All programming and hosting is done entirely for the benefit of the TagPro community.  If you have the ability to help support us, we'd greatly appreciate it!</p>\n\n";

  $BODY .= "<a href=\"https://www.paypal.me/ludlow/9.99\">\n";
  $BODY .= "  <img alt=\"\" border=\"0\" src=\"theme/Default/images/PayPal/Donate.png\" style=\"width: 160px;\" />\n";
  $BODY .= "</a>\n";

  $BODY .= "<h4>Using the Map Designer</h4>\n";

  $BODY .= "<p>The Map Designer is pretty basic and intuitive.  Create a new map, click on a brush, and click on a cell to draw it.  Use the keyboard shortcuts to navigate more quickly.  When you're done, click on the 'Save Map Now' link.  Note that the program auto-saves your map with every 10 changes you make; at most you'll only lose ten data points if you forget to save.</p>\n";

  $BODY .= "<p style=\"margin-left: 20px;\"><img src=\"theme/"._THEME_NAME."/images/help/MapDesignerHelp.png\" alt=\"Map Designer Help\" style=\"border: 3px solid #e5e5e5;\" /></p>\n\n";

  $BODY .= "<h4>Testing Your Map</h4>\n\n";

  $BODY .= "<p>Once our auto-generator is finished, we will have a link to let you test maps without needing to do anything special.  For the time being, you can download your PNG and JSON files from within the map editor.  Once you download both files, visit <a href=\"http://tagpro-maptest.koalabeast.com/testmap\">http://tagpro-maptest.koalabeast.com/testmap</a> and upload them.</p>\n\n";

?>
