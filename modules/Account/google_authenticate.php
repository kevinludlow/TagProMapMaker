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
  $BODY .= "<h3>Welcome to TagPro Map Designer v1.0</h3>\n";

  $BODY .= "<p>It's been awhile since I last got involved in a community-driven project like this, but I'm happy to contribute however I can.  Since I build custom administration interfaces for a living, I thought a map generator and manager wouldn't be too hard a task.</p>\n";

  $BODY .= "<p>It's a little rough around the edges, but I've only put about 10 hours of work into so far.  If there is enough interest in using it, I will happily continue improving it as an ongoing side-project for the TagPro community.</p>\n\n";

  $BODY .= "<h4>Getting an Account</h4>\n";

  $BODY .= "<p>Map Designer uses the Google Authorization for account creation and management.</p>\n";

  $BODY .= "$authText\n";

  $BODY .= "<h4>Help us Out!</h4>\n";

  $BODY .= "<p>All programming and hosting is done entirely for the benefit of the TagPro community.  If you have the ability to help support us, we'd greatly appreciate it!</p>\n\n";

  $BODY .= "<form action=\"https://www.paypal.com/cgi-bin/webscr\" method=\"post\" target=\"_top\">\n";
  $BODY .= "  <input type=\"hidden\" name=\"cmd\" value=\"_s-xclick\">\n";
  $BODY .= "  <input type=\"hidden\" name=\"hosted_button_id\" value=\"Z5EPTZ7Z49478\">\n";
  $BODY .= "  <input type=\"image\" src=\"https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif\" border=\"0\" name=\"submit\" alt=\"PayPal Donate\">\n";
  $BODY .= "  <img alt=\"\" border=\"0\" src=\"https://www.paypalobjects.com/en_US/i/scr/pixel.gif\" width=\"1\" height=\"1\">\n";
  $BODY .= "</form>\n\n";

  $BODY .= "<h4>Using the Map Designer</h4>\n";

  $BODY .= "<p>The Map Designer is pretty basic and intuitive.  Create a new map, click on a brush, and click on a cell to draw it.  Use the keyboard shortcuts to navigate more quickly.  When you're done, click on the 'Save Map Now' link.  Note that the program auto-saves your map with every 10 changes you make; at most you'll only lose ten data points if you forget to save.</p>\n";

  $BODY .= "<p style=\"margin-left: 20px;\"><img src=\"theme/"._THEME_NAME."/images/help/MapDesignerHelp.png\" alt=\"Map Designer Help\" style=\"border: 3px solid #e5e5e5;\" /></p>\n\n";

  $BODY .= "<h4>Testing Your Map</h4>\n\n";

  $BODY .= "<p>Once our auto-generator is finished, we will have a link to let you test maps without needing to do anything special.  For the time being, you can download your PNG and JSON files from within the map editor.  Once you download both files, visit <a href=\"http://tagpro-maptest.koalabeast.com/testmap\">http://tagpro-maptest.koalabeast.com/testmap</a> and upload them.</p>\n\n";

  $BODY .= "<h4>Known Bugs and Design Flaws</h4>\n\n";

  $BODY .= "<p>I will try to keep this updated as best I can.  If you come across a bug or have a feature request, feel free to email me at the address above.</p>\n\n";

  $BODY .= "<ul>\n\n";

  $BODY .= "  <li><em>Editor takes up too much screen real-estate</em><br/>I know.  There are many ways I could change this so that the allocation of real-estate was more fitting of your small monitor, but all of this tends to result in excess coding and clunky Javascripting.  Given the program is intended as a design tool, my current response to this is just that you'll have to scroll a bit or use a larger monitor (I use a pretty basic Soyo 26\" connected to my MacBook and it has ample room).<br/><br/></li>\n";

  $BODY .= "  <li><em>No functionality to assign gates to buttons</em><br/>This is pretty much the last thing I need to incorporate into the codebase.  I should have this working in the next day or two.  For now, feel free to build maps.  The changes will all be backwards-compatible.<br/><br/></li>\n";

  $BODY .= "  <li><em>Shortcut Keys Not Working in Safari</em><br/>The shortcut keys for quickly selecting brushes are currently not working in Safari.  This is due to the keycodes being reported by Javascript, but I have yet to look into why there is a difference.  You can still click on the tool to select it.</li>\n";

  $BODY .= "</ul>\n\n";

  $BODY .= "<h4>Version History</h4>\n\n";

  $BODY .= "<ul>\n\n";

  $BODY .= "  <li>version 1.13.11\n";
  $BODY .= "  <ul>\n";
  $BODY .= "    <li>fixed JS glitch that was causing bombs from properly loading</li>\n";
  $BODY .= "    <li>standardized site navigation menu</li>\n";
  $BODY .= "    <li>added \"Active Maps\" section allowing people to view other people's maps</li>\n";
  $BODY .= "    <li>fixed additional DNS issues</li>\n";
  $BODY .= "  </ul>\n";
  $BODY .= "  </li>\n";

  $BODY .= "  <li>version 1.05.09\n";
  $BODY .= "  <ul>\n";
  $BODY .= "    <li>added support for red and blue team tiles</li>\n";
  $BODY .= "  </ul>\n";
  $BODY .= "  </li>\n";

  $BODY .= "  <li>version 1.03.09\n";
  $BODY .= "  <ul>\n";
  $BODY .= "    <li>added four different brush sizes to expedite the design process (these only apply to blank, wall, and floor cells)</li>\n";
  $BODY .= "    <li>fixed DNS issues</li>\n";
  $BODY .= "  </ul>\n";
  $BODY .= "  </li>\n";

  $BODY .= "  <li>version 1.01.07\n";
  $BODY .= "  <ul>\n";
  $BODY .= "    <li>added functionality for assigning buttons to switches and bombs</li>\n";
  $BODY .= "    <li>added map author to map editor functionality (also included in JSON output)</li>\n";
  $BODY .= "    <li>JSON output is now fully functioning with buttons, switches, and bombs</li>\n";
  $BODY .= "    <li>further separated options on the map editor site navigation</li>\n";
  $BODY .= "  </ul>\n";
  $BODY .= "  </li>\n";

  $BODY .= "  <li>version 1.00.05\n";
  $BODY .= "  <ul>\n";
  $BODY .= "    <li>fixed map PNG generator issues</li>\n";
  $BODY .= "    <li>hard-coded TagPro server IP to speed up connection problems (due to DNS resolve being slow)</li>\n";
  $BODY .= "  </ul>\n";
  $BODY .= "  </li>\n";

  $BODY .= "  <li>version 1.00.03\n";
  $BODY .= "  <ul>\n";
  $BODY .= "    <li>added functionality allowing maps to be directly tested from within the map editor</li>\n";
  $BODY .= "    <li>fixed javascript bug causing map saving text to be distorted</li>\n";
  $BODY .= "    <li>built initial error checking (currently limited to flag usage) for map completion</li>\n";
  $BODY .= "  </ul>\n";
  $BODY .= "  </li>\n";

  $BODY .= "  <li>version 1.00.00\n";
  $BODY .= "  <ul>\n";
  $BODY .= "    <li>initial release</li>\n";
  $BODY .= "  </ul>\n";
  $BODY .= "  </li>\n";

  $BODY .= "</ul>\n\n";

?>
