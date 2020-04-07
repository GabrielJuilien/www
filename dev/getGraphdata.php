<?php
  try {
    $bdd = new PDO('mysql:dbname=helpdesk;host=localhost', 'helpdesk_manager', 'JwfSswy19X5iSZ8P');
  }
  catch(PDOException $e) {
    $e->getMessage();
  }

  $operator = $_GET['operator'];
  $timestamp = $_GET['timestamp'];
  $step = $_GET['step'];


  switch ($step) {
  case 'h':
  $req = $bdd->prepare('SELECT COUNT(ID_Request) FROM requests 
    ');
    break;
  case 'd':
    break;
  case 'w':
    break;
  case 'm':
    break;
  case 'y':
    break;
  default:
    exit();
    break;
  }
?>
