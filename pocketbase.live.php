<?php

include_once("./header.php");

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
echo "If you leave this blank, the server will be accessible via a designated port instead<br />(e.g." . PB_URL . ":8080).";
echo "</section>";

foreach (PB_SUBDOMAINS as $subdomain) {
  echo "<section>";
  echo "<div>https://</div>";
  echo $subdomain;
  echo "<div>." . PB_DOMAIN_NAME . "</div>";
  echo "</section>";
}

echo "<section>";
echo "<button id=\"create\">Proceed</button>";
echo "</section>";


include_once("./footer.php");

?>