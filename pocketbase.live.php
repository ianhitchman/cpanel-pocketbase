<?php
include("/usr/local/cpanel/php/cpanel.php");  // Instantiate the CPANEL object.
$cpanel = new CPANEL();                       // Connect to cPanel - only do this once.
print $cpanel->header( "Page Heading , app_key" );      // Add the header.

echo "<img src=\"./pocketbase-logo.png\" alt=\"Pocketbase\" />";
echo "Testing!";

print $cpanel->footer();                      // Add the footer.
$cpanel->end();                               // Disconnect from cPanel - only do this once.
?>