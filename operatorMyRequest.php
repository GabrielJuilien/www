<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location:/login.php");
}
?>
<html>
  <head>
    <title>my tickets</title>
  </head>
  <body>

  <?php
  $bdd = new PDO('mysql:dbname=helpdesk;host=localhost', 'helpdesk_default', 'xixn2lCbJe90Xa8n');//id et mdp tmp
  $requete = $bdd->prepare('SELECT requests.ID_Request, requests.Submission_Datetime, requests.Processing_Datetime, requests.Solve_Datetime, employees.First_Name, employees.Last_Name FROM requests
                              LEFT JOIN employees ON requests.ID_Submitter = employees.ID_Employee
                              WHERE requests.ID_Operator = ?
                            ');
  $requete->bindParam(1, $_SESSION['id_user']);
  $data = $requete->execute();

  if ($data != 1) {
      while($resultat = $data->fetch()) {?>
          <div class = "conteneurticket">
            <?php
            echo "ID Request: ".$res["ID_Request"];
            echo "Submitted on: ".$res["Submission_Datetime"];
            echo "\n Request state: ";
            if (isset($resultat["Solve_Datetime"])) {
              echo "Solved (".$resultat["Solve_Datetime"].")";
            }
            else if (isset($resultat["Processing_Datetime"])){
              echo "Getting processed since: (".$resultat["Processing_Datetime"].")";
            }
            else {
              echo "Waiting to be processed.";
            }
            echo "\n Submitted by: ".$res["First_Name"]." ".$res["Last_Name"];
            ?>

            <a href="requestDisplay.php?ticket="<?php echo $_GET['ID_Request']?>> See request</a>
          </div>
          <?php
        }
  }
  else {
    echo "No requests found.";
  }
  ?>


  </body>
</html>
