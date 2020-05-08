<?php
session_start();

if (!$_SESSION['user_id']) {
  header("Location:/login.php");
}

if ($_SESSION['user_role'] !== 1) {
  echo "You don't have permission to access this page.";
  exit();
}
?>
<html>
<head>
  <title>My tickets</title>
</head>
<body>
  <?php
  $bdd = new PDO('mysql:dbname=helpdesk;host=localhost', 'helpdesk_default', 'xixn2lCbJe90Xa8n');//id et mdp tmp
  $requete = $bdd->prepare('SELECT requests.ID_Request, requests.Submission_Datetime, requests.Processing_Datetime, requests.Solve_Datetime, employees.First_Name, employees.Last_Name FROM requests
    JOIN employees ON requests.ID_Submitter = employees.ID_Employee
    WHERE requests.ID_Operator = ? AND requests.ID_Request NOT IN (SELECT ID_Request FROM tasks GROUP BY ID_Request) AND requests.Solve_Datetime IS NULL
    ');
    $requete->bindParam(1, $_SESSION['user_id']);
    $requete->execute();
    if ($requete && $resultat = $requete->fetch()) {
      ?>
      <div class = "conteneurticket">
        <?php
        echo "ID Request: ".$resultat["ID_Request"];
        echo "Submitted on: ".$resultat["Submission_Datetime"];
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
        echo "\n Submitted by: ".$resultat["First_Name"]." ".$resultat["Last_Name"];
        ?>
        <button class="edit_request" onclick="callbackEditRequest(<?php echo $resultat['ID_Request']; ?>)">Edit request</button>
        <button class="request_transfer" onclick="callbackTransferRequest(<?php echo $resultat['ID_Request']; ?>)">Transfer request</button>
      </div>
      <?php
      while ($resultat = $requete->fetch()) {?>
        <div class = "conteneurticket">
          <?php
          echo "ID Request: ".$resultat["ID_Request"];
          echo "Submitted on: ".$resultat["Submission_Datetime"];
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
          echo "\n Submitted by: ".$resultat["First_Name"]." ".$resultat["Last_Name"];
          ?>

          <button class="edit_request" onclick="callbackEditRequest(<?php echo $resultat['ID_Request']; ?>)">Edit request</button>
          <button class="request_transfer" onclick="callbackTransferRequest(<?php echo $resultat['ID_Request']; ?>)">Transfer request</button>
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
