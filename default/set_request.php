<?php
session_start();

if (!$_SESSION['user_id']) {
  header("Location:/login.php");
}

if ($_SESSION['user_role'] !== 0) {
  echo "You don't have permission to access this page.";
  exit();
}

try {
  $bdd = new PDO('mysql:dbname=helpdesk;host=localhost', 'helpdesk_default', 'xixn2lCbJe90Xa8n');
}
catch(PDOException $e) {
  $e->getMessage();
}

$ID_Device = $_POST['ID_Device'];
$ID_User = $_POST['ID_User'];

if (empty($_POST['ID_Problem'])) {
  $problem_title = $_POST['problem_title'];
  $problem_description = $_POST['problem_description'];
  $req1 = $bdd->prepare('INSERT INTO problems (problem_title, problem_description) VALUES (?, ?)');
  $req1->bindParam(1, $problem_title);
  $req1->bindParam(2, $problem_description);
  $req1->execute();
  $ID_Problem = $bdd->lastInsertId();
}
else $ID_Problem = $_POST['ID_Problem'];

  $req2 = $bdd->prepare('INSERT INTO relations (ID_problem) VALUES (?)');
  $req2->bindParam(1, $ID_Problem);
  $req2->execute();
  $ID_Relation = $bdd->lastInsertId();

  $datetime = new DateTime();
  $datetime = $datetime->format("Y-m-d H:i:s");
  $req3 = $bdd->prepare('INSERT INTO requests (ID_Submitter, ID_Device, ID_Relation, Submission_Datetime) VALUES (?, ?, ?, ?)');
  $req3->bindParam(1, $ID_User);
  $req3->bindParam(2, $ID_Device);
  $req3->bindParam(3, $ID_Relation);
  $req3->bindParam(4, $datetime);
  $req3->execute();

  $req4 = $bdd->prepare('SELECT requests.ID_Request FROM requests WHERE ID_Relation = ?');
  $req4->bindParam(1, $ID_Relation);
  $req4->execute();

  if (!$req4) {
    echo "Error";
  }
  else {
    $data = $req4->fetch();
    echo $data['ID_Request'];
  }
?>
