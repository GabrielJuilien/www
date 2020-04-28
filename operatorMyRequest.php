<?php
session_start();
?>
<html>
  <head>
    <title>my tickets</title>
  </head>
  	
<body>	
		
		<?php
		$bdd=	new PDO("mysql:host=127.0.0.1;dbname=helpdesk",$_SESSION["ID"],$_SESSION["mdp"]);//id et mdp tmp
		$requete = $bdd->prepare("SELECT * FROM requests WHERE :ID_Operator=IdOp
		LEFT JOIN employees WHERE		 requests.ID_Submitter = employees.ID_Employee
		LEFT JOIN jobs WHERE 			 employees.ID_Job = jobs.ID_Job");
		$requete->execute(array(":id"=>$_SESSION['IdOp']));?>

		 <?php while($resultat=$reponse->fetch()):?>
			<div class = "conteneurticket">
				<?php echo "id request " $res["ID_Request"] ?>
			    <?php echo "Submission_Datetime " $res["Submission_Datetime"] ?>
	            <?php echo "\n Processing_Datetime " $res["Processing_Datetime"] ?>
	            <?php if($res["Solve_Datetime"]==NULL){echo "\n Solve_Datetime " $res["Solve_Datetime"]}else{echo "\n active"} ?>
				<?php echo "\n client name " $res["First_Name"] ?>
				<?php echo " " $res["Last_Name"] ?>

			 <a href="requestDisplay.php?ticket="<?php echo $_GET['ID_Request']?>> see request</a>
			 </div>			
		<?php endwhile ;?>
		
</body>

</html>