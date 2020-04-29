<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location:/login.php");
}
?>
<html>
  <head>
    <link rel="stylesheet" href="style/ticketDisplay.css"/>
  </head>
  	
<body>	
		
		<?php
		$bdd=	new PDO("mysql:host=127.0.0.1;dbname=helpdesk",'helpdesk_default','xixn2lCbJe90Xa8n');//id et mdp tmp
		$requete = $bdd->prepare("SELECT employees.First_Name, employees.Last_Name, jobs.Job_Name FROM employees ");
		$requete->bindParam(1,$_GET['ID_Request']);
		$requete->execute();
		$res=$requete->fetch();?>
		<div class = "conteneurticket">
		    <?php echo $res["Submission_Datetime"] ?>
            <?php echo $res["Processing_Datetime"] ?>
            <?php echo $res["Solve_Datetime"] ?>
			<?php echo $res["Problem_Description"] ?>
			<?php echo $res["Solution_Description"] ?>
			<?php echo $res["Device_Details"] ?>
			<?php echo $res["Device_Type_Name"] ?>
			<?php echo $res["First_Name"] ?>
			<?php echo $res["Last_Name"] ?>
			<?php echo $res["Department_Name"] ?>
			<?php echo $res["Job_Name"] ?>
        </div>
		 <a href="editingRequest.php?ticket="<?php echo $_GET['ID_Request']?>>edit ticket</a>	
</body>

</html>
