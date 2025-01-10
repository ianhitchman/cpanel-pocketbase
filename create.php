<?php
try {

  $submitted_subdomain = $_POST['subdomain'];
  check_subdomain($submitted_subdomain);
  
  echo "<section>";
  echo "Creating subdomain...";
  echo "</section>";
  flush_output();  
  create_subdomain($submitted_subdomain);

  echo "<section>";
  echo "Get an available port...";
  echo "</section>";
  flush_output();  
  $port = find_available_port();

  echo "<section>";
  echo "Set up service...";
  echo "</section>";
  flush_output(); 
  setup_daemon($submitted_subdomain, $port);

  echo "DONE!";



} catch (Exception $e) {
  echo "<section class=\"pocketbase-error\">" . $e->getMessage() . "</section>";
  include('form.php');
}

?>