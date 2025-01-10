<?php
try {

  $submitted_subdomain = $_POST['subdomain'];
  $check_subdomain = check_subdomain($submitted_subdomain);
  if ($check_subdomain['error']) {
    throw new Exception($check_subdomain['error']);
  }
  
  echo "<section>";
  echo "Creating subdomain...";
  echo "</section>";

  flush_output();  
  create_subdomain($submitted_subdomain);

  echo "DONE!";



} catch (Exception $e) {
  echo "<section class=\"pocketbase-error\">" . $e->getMessage() . "</section>";
  include('form.php');
}

?>