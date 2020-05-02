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
  <link rel="stylesheet" href="style/ticketDisplay.css"/>
</head>
<body>
  <?php
  $bdd=	new PDO("mysql:host=127.0.0.1;dbname=helpdesk",'helpdesk_default','xixn2lCbJe90Xa8n');//id et mdp tmp
  $requete = $bdd->prepare("SELECT employees.ID_Employee AS ID_Specialist, employees.First_Name, employees.Last_Name, specialities.Speciality_Name FROM specialization
    LEFT JOIN employees ON employees.ID_Employee = specialization.ID_Specialist
    LEFT JOIN jobs ON jobs.ID_Job=employees.ID_Job
    LEFT JOIN specialities ON specialities.ID_Speciality = specialization.ID_Speciality
    WHERE employees.ID_Job = 3
    GROUP BY employees.ID_Employee
    ");
    $requete->execute();
    while ($resultat = $requete->fetch()) {
      ?>
      <div class="specialist_container">
        <?php
        echo "specialist name : ".$resultat["First_Name"].$resultat["Last_Name"];
        echo "speciality : ".$resultat["Speciality_Name"];
        ?>
        <button class="transfer_request" onclick="callbackRequestTransfer(<?php echo $_GET['ID_Request'].",".$resultat['ID_Specialist'];?>)">Transfer request</button>
      </div>
      <?php
    }
    ?>
  </body>
  </html>
