<?php
define("CPANEL", null);
define("PB_HOME_DIR", "/home/ianhitchman/temp");
define("PB_DOMAIN_NAME", "localhost");
define("PB_URL", "https://" . PB_DOMAIN_NAME);
define("PB_SUBDOMAINS", []);

include('functions.php');

try {
  echo "installing pocketbase<br />";
  
  install_pocketbase('test');

  echo "get available port<br />";
  $port = find_available_port();
  
  echo "set up daemon<br />";
  setup_daemon('test', $port);

  
} catch (Exception $e) {
  echo $e->getMessage();
}

?>