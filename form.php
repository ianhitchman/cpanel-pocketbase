<?php

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

echo "<section>";
echo "<input type=\"hidden\" id=\"action\" name=\"action\" value=\"create\" />";
echo "<input type=\"submit\" id=\"create\" value=\"Proceed\" />";
echo "</section>";

?>