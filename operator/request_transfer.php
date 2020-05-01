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
			$requete = $bdd->prepare("SELECT employees.First_Name, employees.Last_Name, specialities.Speciality_Name FROM specialization WHERE employees.ID_Job =3
				LEFT JOIN employees ON employees.ID_Employee = specialization.ID_Specialist
				LEFT JOIN jobs ON jobs.ID_Job=employees.ID_Job
				LEFT JOIN specialities ON specialities.ID_Speciality = specialization.ID_Speciality");
			$requete->execute();
			 while ($resultat = $requete->fetch()) {?>
				<div class = "liste specialiste">
				    <?php echo "specialist name : ".$resultat["First_Name"].$resultat[Last_Name]?>
				    <?php echo "speciality : ".$resultat["Speciality_Name"] ?>

				    <a href="transfer_request.php?ticket=<?php echo $_POST['ticket']?>&specialist=<?php echo $resultat['ID_Specialist'] ?>"> create task</a>
		        </div>

		<?php } ?>
</body>

</html>
