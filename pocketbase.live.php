<?php

include_once("./header.php");

switch($_POST['action']) {

  case "create":
    include("./create.php");
    break;

  default:
    include("./form.php");

}


include_once("./footer.php");

?>