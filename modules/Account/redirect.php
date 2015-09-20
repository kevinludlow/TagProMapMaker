<?php

  // Due to the way that the SESSION handler works,
  // we need to send the user to a page so that
  // we can redirect them and show them logged in.
  header( "Location: index.php?module=Account" );

?>
