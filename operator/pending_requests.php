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
  <title>New tickets</title>
</head>
<body>
  <?php
  $bdd = new PDO('mysql:dbname=helpdesk;host=localhost', 'helpdesk_default', 'xixn2lCbJe90Xa8n');//id et mdp tmp
  $requete = $bdd->prepare('SELECT requests.ID_Request, requests.Submission_Datetime, employees.First_Name, employees.Last_Name, device_types.Device_Type_Name FROM requests
    LEFT JOIN employees ON requests.ID_Submitter = employees.ID_Employee
    LEFT JOIN devices ON requests.ID_Device = devices.ID_Device
    LEFT JOIN device_types ON devices.ID_Device_Type = device_types.ID_Device_Type
    WHERE requests.Processing_Datetime IS NULL
    ');
    $requete->execute();

    if ($requete) {
      if ($resultat = $requete->fetch()) {
        ?>
        <div class = "conteneurticket">
          <?php
          echo "ID Request: ".$resultat["ID_Request"];
          echo "Submitted on: ".$resultat["Submission_Datetime"];
          echo "Device type :".$resultat["Device_Type_Name"];
          echo "Waiting to be processed.";

          echo "\n Submitted by: ".$resultat["First_Name"]." ".$resultat["Last_Name"];
          ?>
          <button class="take_request" onclick="callbackAcceptRequest(<?php echo $resultat['ID_Request']; ?>)">Accept request</button>
        </div>
        <?php
      }
      else {
        ?>
        No request found.
      <?php
    }
    while($resultat = $requete->fetch()) {?>
      <div class = "conteneurticket">
        <?php
        echo "ID Request: ".$resultat["ID_Request"];
        echo "Submitted on: ".$resultat["Submission_Datetime"];
        echo "device types :".$resultat["devices_types"];
        echo "Waiting to be processed.";

        echo "\n Submitted by: ".$resultat["First_Name"]." ".$resultat["Last_Name"];
        ?>

        <button class="take_request" onclick="callbackAcceptRequest(<?php echo $resultat['ID_Request']; ?>)">Accept request</button>
      </div>
      <?php
    }
  }
  else {
    ?>
    No request found.
    <?php
  }
  ?>
</body>
</html>
