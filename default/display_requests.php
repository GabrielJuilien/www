<?php
session_start();

if (!$_SESSION['user_id']) {
  header("Location:/login.php");
}

if ($_SESSION['user_role'] !== 0) {
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
    WHERE requests.ID_Submitter = ?
    ');
    $requete->bindParam(1, $_SESSION['user_id']);
    $requete->execute();

    if ($requete && $resultat = $requete->fetch()) {
      ?>
      <div class = "conteneurticket">
        <?php
        echo "ID Request: ".$resultat["ID_Request"]."<br />";
        echo "Submitted on: ".$resultat["Submission_Datetime"]."<br />";
        echo "Request state: ";
        if (isset($resultat["Solve_Datetime"])) {
          echo "Solved (".$resultat["Solve_Datetime"].")"."<br />";
        }
        else if (isset($resultat["Processing_Datetime"])){
          echo "Getting processed since: (".$resultat["Processing_Datetime"].")"."<br />";
        }
        else {
          echo "Waiting to be processed."."<br />";
        }
        echo "Submitted by: ".$resultat["First_Name"]." ".$resultat["Last_Name"]."<br />";
        ?>
        <button class="display_request" onclick="callbackEditRequest(<?php echo $resultat['ID_Request']; ?>)">View request</button>
      </div>
      <?php
      while ($resultat = $requete->fetch()) {?>
        <hr class="separator">
        <div class = "conteneurticket">
          <?php
          echo "ID Request: ".$resultat["ID_Request"]."<br />";
          echo "Submitted on: ".$resultat["Submission_Datetime"]."<br />";
          echo "Request state: ";
          if (isset($resultat["Solve_Datetime"])) {
            echo "Solved (".$resultat["Solve_Datetime"].")"."<br />";
          }
          else if (isset($resultat["Processing_Datetime"])){
            echo "Getting processed since: (".$resultat["Processing_Datetime"].")"."<br />";
          }
          else {
            echo "Waiting to be processed."."<br />";
          }
          echo "Submitted by: ".$resultat["First_Name"]." ".$resultat["Last_Name"]."<br />";
          ?>
          <button class="display_request" onclick="callbackEditRequest(<?php echo $resultat['ID_Request']; ?>)">View request</button>
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
