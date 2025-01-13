<?php
try {

  $submitted_subdomain = $_POST['subdomain'];
  check_subdomain($submitted_subdomain);
  
  echo "<section>";
  echo "Creating subdomain '$submitted_subdomain'... ";
  flush_output();  
  create_subdomain($submitted_subdomain);
  echo "OK";
  echo "</section>";

  echo "<section>";
  echo "Get an available port... ";
  flush_output();  
  $port = find_available_port();
  echo $port . " OK";
  echo "</section>";

  echo "<section>";
  echo "Set up service... ";
  flush_output(); 
  $output = setup_daemon($submitted_subdomain, $port);
  echo "<pre>";
  print_r($output);
  echo "</pre>";
  echo "</section>";

  echo "<section>";
  echo "All done";
  echo "</section>";



} catch (Exception $e) {
  echo "<section class=\"pocketbase-error\">" . $e->getMessage() . "</section>";
  include('form.php');
}

?>