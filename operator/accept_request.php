<?php
session_start();

if (!$_SESSION['user_id']) {
  header("Location:/login.php");
}

if ($_SESSION['user_role'] !== 1) {
  echo "You don't have permission to access this page.";
  exit();
}

if (!isset($_GET['ID_Request'])) {
  echo "Error: You must provide a request ID.";
  exit();
}

$bdd = new PDO('mysql:dbname=helpdesk;host=localhost', 'helpdesk_default', 'xixn2lCbJe90Xa8n');

$request = $bdd->prepare('SELECT * FROM requests WHERE ID_Request = ? AND ID_Operator IS NULL');
$request->bindParam(1, $_GET['ID_Request']);
$request->execute();

try {
  $request->fetch();
}
catch (Exception $e){
  echo "Error: The request you try to accept is already getting processed.";
  exit();
}

$datetime = new DateTime();
$datetime = $datetime->format("Y-m-d H:i:s");

$request = $bdd->prepare('UPDATE requests SET ID_Operator = ?, Processing_Datetime = ? WHERE ID_Request = ?');
$request->bindParam(1, $_SESSION['user_id']);
$request->bindParam(2, $datetime);
$request->bindParam(3, $_GET['ID_Request']);
$request->execute();

if ($request) {
  echo "Request accepted at ".$datetime.".<br />";
}
else {
  echo "Error: the request couldn't be accepted.";
}
?>
