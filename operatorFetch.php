<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location:/login.php");
}
?>
<html>
  <head>
    <title>new tickets</title>
  </head>
  <body>

  <?php
  $bdd = new PDO('mysql:dbname=helpdesk;host=localhost', 'helpdesk_default', 'xixn2lCbJe90Xa8n');//id et mdp tmp
  $requete = $bdd->prepare('SELECT requests.ID_Request, requests.Submission_Datetime, employees.First_Name, employees.Last_Name, devices_types.devices_types FROM requests
                              LEFT JOIN employees ON requests.ID_Submitter = employees.ID_Employee
                              LEFT JOIN devices ON       requests.ID_Device = devices.ID_Device                      
                              LEFT JOIN devices_types ON   devices.ID_Device_Type = devices_types.ID_Device_Type 
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
            echo "device types :".$res["devices_types"]
            echo "Waiting to be processed.";
            
            echo "\n Submitted by: ".$res["First_Name"]." ".$res["Last_Name"];
            ?>

            <a> ici le SUPER BOUTON AJAX </a>
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
