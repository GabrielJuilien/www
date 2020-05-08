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
<body>
  <?php
  $bdd=	new PDO("mysql:host=127.0.0.1;dbname=helpdesk",'helpdesk_default','xixn2lCbJe90Xa8n');//id et mdp tmp
  $requete = $bdd->prepare("SELECT employees.ID_Employee AS ID_Specialist, employees.First_Name, employees.Last_Name, GROUP_CONCAT(specialities.Speciality_Name ORDER BY specialities.Speciality_Name ASC SEPARATOR ', ') AS Speciality_Name FROM specialization
    LEFT JOIN employees ON employees.ID_Employee = specialization.ID_Specialist
    LEFT JOIN jobs ON jobs.ID_Job=employees.ID_Job
    LEFT JOIN specialities ON specialities.ID_Speciality = specialization.ID_Speciality
    WHERE employees.ID_Job = 3 AND employees.ID_Department = 3
    GROUP BY employees.ID_Employee
    ");
    $requete->execute();
    try {
      $resultat = $requete->fetch();
    }
    catch (Exception $e) {
      ?>
      No specialist available.
      <?php
    }
    ?>
    <div class="specialist_container">
      <?php
      echo $resultat["First_Name"]." ".$resultat["Last_Name"]."<br />";
      echo "Specialities:<br />".$resultat["Speciality_Name"]."<br />";
      ?>
      <button class="transfer_request" onclick="callbackRequestTransfer(<?php echo $_GET['ID_Request'].",".$resultat['ID_Specialist'];?>)">Transfer request</button>
    </div>
    <?php
    while ($resultat = $requete->fetch()) {
      ?>
      <hr class="sperator">
      <div class="specialist_container">
        <?php
        echo $resultat["First_Name"]." ".$resultat["Last_Name"]."<br />";
        echo "Specialities:<br />".$resultat["Speciality_Name"]."<br />";
        ?>
        <button class="transfer_request" onclick="callbackRequestTransfer(<?php echo $_GET['ID_Request'].",".$resultat['ID_Specialist'];?>)">Transfer request</button>
      </div>
      <?php
    }
    ?>
  </body>
  </html>
