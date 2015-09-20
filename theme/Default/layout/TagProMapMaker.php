<?php

  echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\"\n";
  echo "                      \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\n\n";

  echo "<!--\n";
  echo "//############################################################################/\n";
  echo "//##                                                                        ##/\n";
  echo "//##  TagPro Map Designer                                                   ##/\n";
  echo "//##                                                                        ##/\n";
  echo "//##  Developed by Kevin Ludlow (ludlow@gmail.com)                          ##/\n";
  echo "//##                                                                        ##/\n";
  echo "//##  This site is my contribution to a community-driven software           ##/\n";
  echo "//##  project and as such comes with no guarantees.  Enjoy!                 ##/\n";
  echo "//##                                                                        ##/\n";
  echo "//############################################################################/\n";
  echo "-->\n\n";

  echo "<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\" lang=\"en\" id=\"MapDesigner\">\n\n";

  echo "<head>\n";
  echo "  <meta http-equiv=\"content-type\" content=\"text/html; charset=UTF-8\" />\n";
  echo "  <link rel=\"StyleSheet\" href=\"theme/"._THEME_NAME."/style/style.css\" type=\"text/css\" media=\"screen\" title=\"Default\" />\n";
  echo "  <script type=\"text/javascript\" src=\"js/jquery-1.8.0.min.js\"></script>\n";
  echo "  <script type=\"text/javascript\" src=\"js/jquery-common.js\"></script>\n";
  echo "  <title>"._THEME_TITLE."</title>\n";

  echo "  <script type=\"text/javascript\">\n\n";

  echo "    var _gaq = _gaq || [];\n";
  echo "    _gaq.push(['_setAccount', 'UA-5617293-2']);\n";
  echo "    _gaq.push(['_trackPageview']);\n\n";

  echo "    (function() {\n";
  echo "      var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;\n";
  echo "      ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';\n";
  echo "      var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);\n";
  echo "    })();\n\n";

  echo "  </script>\n\n";

  echo "</head>\n\n";

  echo "<body>\n\n";

  echo "<div class=\"container\">\n";

  echo "  <div class=\"header\">\n";
  echo "    <div class=\"logo\">\n";
  echo "      <a href=\"index.php\"><img src=\"theme/"._THEME_NAME."/images/logos/MapDesigner_v1.0.png\" alt=\"Map Designer\" /></a>\n";
  echo "    </div>\n";
  echo "    <div class=\"status\"><%STATUS%></div>\n";
  echo "  </div>\n";

  echo "  <table class=\"bodyContainer\">\n";
  echo "  <tr>\n";
  echo "    <td class=\"navigation\">\n";
  echo "      <%NAV%>\n";
  echo "    </td>\n";
  echo "    <td class=\"content\">\n";
  echo "      <%BODY%>\n";
  echo "    </td>\n";
  echo "  </tr>\n";
  echo "  </table>\n\n";

  echo "</div>\n\n";

  echo "</body>\n\n";

  echo "</html>\n\n";

?>
