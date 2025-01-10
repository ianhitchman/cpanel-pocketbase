<?php
include("/usr/local/cpanel/php/cpanel.php");  // Instantiate the CPANEL object.
$cpanel = new CPANEL();

$account = $cpanel->uapi( 
  'DomainInfo', 'domains_data',
  array(
      'format'    => 'hash',
  )
);

define("PB_DOMAIN_NAME", "your-site.com");
define("PB_URL", "https://" . PB_DOMAIN_NAME);

echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"./pocketbase.css\" />";

echo "<div class=\"pocketbase-container\">";

echo "<section>";
echo "<img src=\"./pocketbase-logo.png\" alt=\"Pocketbase\" />";
echo "</section>";

echo "<section>";
echo "To set up a new Pocketbase instance, please enter a new subdomain below.";
echo "</section>";

echo "<section>";
echo "<div>https://</div>";
echo "<input type=\"text\" id=\"subdomain\" name=\"subdomain\" />";
echo "<div>." . PB_DOMAIN_NAME . "</div>";
echo "</section>";

echo "<section>";
echo "If you leave this blank, the server will be accessible via a designated port instead (e.g." . PB_URL . ":8080).";
echo "</section>";

echo "<section>";
echo "<button id=\"create\">Proceed</button>";
echo "</section>";

echo "<pre>";
print_r($account);
echo "</pre>";

echo "</div>";

print $cpanel->footer();                      // Add the footer.
$cpanel->end();                               // Disconnect from cPanel - only do this once.
?>