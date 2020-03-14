<?php
session_start();

$_SESSION = array(NULL);
session_destroy();

header('Location:login.php');
?>
