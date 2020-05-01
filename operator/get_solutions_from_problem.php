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

$ID_Problem = $_GET['ID_Problem'];

//Select all solutions linked to this problem
$request = $bdd->prepare('SELECT solutions.ID_Solution, solutions.solution_description FROM problems
                            JOIN relations ON relations.ID_Problem = ?
                            RIGHT JOIN solutions ON solutions.ID_Solution = relations.ID_Solution');
$request->bindParam(1, $ID_Problem);
$request->execute();

while ($data = $request->fetch()) {
  echo "<p>".$data['solution_description']." <a href=\"faq.php?ID_Solution=".$data['ID_Solution']."\">More info...</a></p>";
}
?>
