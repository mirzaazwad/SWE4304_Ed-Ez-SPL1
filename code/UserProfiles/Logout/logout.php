<?php 

$root_path='../../';
require $root_path.'LibraryFiles/DatabaseConnection/config.php';
require $root_path.'LibraryFiles/SessionStore/session.php';
session_destroy();

header("Location: ".$root_path."Landing/index.php");

?>