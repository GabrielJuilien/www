<?php

session_start();
try {
  $bdd = new PDO('mysql:dbname=helpdesk;host=localhost', 'helpdesk_default', 'xixn2lCbJe90Xa8n');
}
catch(PDOException $e) {
  $e->getMessage();
}

$ID_Device = $_POST['ID_Device'];
$ID_User = $_POST['ID_User'];
$ID_Problem = $_POST['ID_Problem'];
$problem_title = $_POST['problem_title'];
$problem_description = $_POST['problem_description'];

if (empty($ID_Problem)) {
  $req1 = $bdd->prepare('INSERT INTO problems (problem_title, problem_description) VALUES (?, ?)');
  $req1->bindParam(1, $problem_title);
  $req1->bindParam(2, $problem_description);
  $req1->execute();
  $ID_Problem = $bdd->lastInsertId();
}

  $req2 = $bdd->prepare('INSERT INTO relations (ID_problem) VALUES (?)');
  $req2->bindParam(1, $ID_Problem);
  $req2->execute();
  $ID_Relation = $bdd->lastInsertId();

  $datetime = new DateTime();
  $req3 = $bdd->prepare('INSERT INTO requests (ID_Submitter, ID_Device, ID_Relation, Submission_Datetime) VALUES (?, ?, ?, ?)');
  $req3->bindParam(1, $ID_User);
  $req3->bindParam(2, $ID_Device);
  $req3->bindParam(3, $ID_Relation);
  $req3->bindParam(4, $datetime->format("Y-m-d H:i:s"));
  $req3->execute();
?>
