<?php

try {

  throw new Exception("Not implemented yet.");

} catch (Exception $e) {
  echo "<section class=\"pocketbase-error\">" . $e->getMessage() . "</section>";
  include('form.php');
}

?>