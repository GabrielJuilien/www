<?php
session_start();

if (!$_SESSION['user_id']) {
  header("Location:/login.php");
}

if ($_SESSION['user_role'] !== 1) {
  echo "You don't have permission to access this page.";
  exit();
}

try {
  $bdd = new PDO('mysql:dbname=helpdesk;host=localhost', 'helpdesk_default', 'xixn2lCbJe90Xa8n');
}
catch(PDOException $e) {
  $e->getMessage();
}

if (!isset($_POST['ID_Request']) || !isset($_POST['Problem_Title']) || !isset($_POST['Problem_Description'])) {
  ?>
  You must provide a title and a description to the problem.
  <?php
  exit();
}

$ID_Request = $_POST['ID_Request'];
$Problem_Title = $_POST['Problem_Title'];
$Problem_Description = $_POST['Problem_Description'];

if (!isset($_POST['Solution_Description'])) {
  $Solution_Description = NULL;
}
else {
  $Solution_Description = $_POST['Solution_Description'];
}

$request = $bdd->prepare('SELECT * FROM requests WHERE ID_Operator = ? AND ID_Request = ? AND (SELECT * FROM tasks WHERE ID_Request = ?) IS NULL');
$request->bindParam(1, $_SESSION['user_id']);
$request->bindParam(2, $ID_Request);
$request->bindParam(3, $ID_Request);
$request->execute();
if ($request) {
  try {
    $request->fetch();
  }
  catch(Exception $e) {
    ?>
    Error: You don't have permission to access this request.
    <?php
    exit();
  }
}
else {
  ?>
  Error: Couldn't retrieve request from server.
  <?php
  exit();
}

//Retrieving problem ID and solution ID
$request = $bdd->prepare('SELECT ID_Problem, ID_Solution FROM relations
  INNER JOIN requests ON requests.ID_Relation = relations.ID_Relation
  WHERE requests.ID_Request = ?');
$request->bindParam(1, $ID_Request);
$request->execute();

$relation = $request->fetch();

$ID_Problem = $relation['ID_Problem'];
$ID_Solution = $relation['ID_Solution'];

if ($ID_Solution) {
  $request = $bdd->prepare('UPDATE Solutions SET Solution_Description = ? WHERE ID_Solution = ?');
  $request->bindParam(1, $Solution_Description);
  $request->bindParam(2, $ID_Solution);
  $request->execute();
}
else if (!empty($Solution_Description)) {
  $request = $bdd->prepare('INSERT INTO Solutions (Solution_Description) VALUES (?); ');
  $request->bindParam(1, $Solution_Description);
  $request->execute();

  $request = $bdd->prepare('SELECT ID_Solution FROM solutions WHERE Solution_Description = ?');
  $request->bindParam(1, $Solution_Description);
  $request->execute();
  $data = $request->fetch();
  $ID_Solution = $data['ID_Solution'];


  $request = $bdd->prepare('UPDATE relations SET ID_Solution = ? WHERE ID_Relation = (SELECT ID_Relation FROM requests WHERE ID_Request = ?)');
  $request->bindParam(1, $ID_Solution);
  $request->bindParam(2, $ID_Request);
  $request->execute();
}

$request = $bdd->prepare('UPDATE Problems SET Problem_Title = ?, Problem_Description = ? WHERE ID_Problem = ?');
$request->bindParam(1, $Problem_Title);
$request->bindParam(2, $Problem_Description);
$request->bindParam(3, $ID_Problem);
$request->execute();

$datetime = new Datetime();
$datetime = $datetime->format("Y-m-d h:i:s");

$request = $bdd->prepare('UPDATE requests SET Solve_Datetime = ? WHERE ID_Request = ?');
$request->bindParam(1, $datetime);
$request->bindParam(2, $ID_Request);
$request->execute();

header("Location:/operator/display_requests.php");
