<?php
session_start();

if (!$_SESSION['user_id']) {
  header("Location:/login.php");
}

if ($_SESSION['user_role'] !== 1) {
  echo "You don't have permission to access this page";
  exit();
}

try {
  $bdd = new PDO('mysql:dbname=helpdesk;host=localhost', 'helpdesk_default', 'xixn2lCbJe90Xa8n');
}
catch(PDOException $e) {
  $e->getMessage();
}

if (isset($_GET['ID_Request']) && isset($_GET['ID_Specialist']))
{
  $ID_Request = $_GET['ID_Request'];
  $ID_Specialist = $_GET['ID_Specialist'];

  $specialist_request = $bdd->prepare('SELECT employees.First_Name, employees.Last_Name FROM employees WHERE ID_Employee = ?');
  $specialist_request->bindParam(1, $ID_Specialist);
  $specialist_request->execute();

  $datetime = new DateTime();
  $datetime = $datetime->format("Y-m-d H:i:s");

  if ($specialist_request && $specialist_info = $specialist_request->fetch()) {
    $request = $bdd->prepare('INSERT INTO tasks (Reception_Datetime, ID_Specialist, ID_Request) VALUES (?, ?, ?)');
    $request->bindParam(1, $datetime);
    $request->bindParam(2, $ID_Specialist);
    $request->bindParam(3, $ID_Request);
    $request->execute();

    if ($request) {
      echo "Request successfully transfered to ".$specialist_info['First_Name']." ".$specialist_info['Last_Name'].".";
    }
    else {
      echo "Error: Couldn't transfer request to the specialist.";
    }
  }
  else {
    echo "Error: No specialist found with ID ".$ID_Specialist.".";
  }
}
else {
  echo "Error: Missing parameters. The request couldn't be transfered.";
}










 ?>
