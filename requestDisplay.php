<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location:/login.php");
}
?>
<html>
  <head>
    <title>tickets n°<?php echo $_GET["ID_Request"]?></title>
    <link rel="stylesheet" href="style/ticketDisplay.css"/>
  </head>

<body>

		<?php
		$bdd=	new PDO("mysql:host=127.0.0.1;dbname=helpdesk",'helpdesk_default','xixn2lCbJe90Xa8n');//id et mdp tmp
		$requete = $bdd->prepare("SELECT request.Submission_Datetime, requests.Processing_Datetime, requests.Solve_Datetime, requests.Problem_Description, solutions.Solution_Description, devices_details.devices_details, devices_types.Device_Type_Name, employees.First_Name, employees.Last_Name, departments.Department_Name, jobs.Job_Name
		 FROM requests WHERE :ID_Request=?
		LEFT JOIN relations ON 		 requests.ID_Relations = relations.ID_Relations
		LEFT JOIN employees ON		 requests.ID_Submitter = employees.ID_Employee
		LEFT JOIN devices ON			 requests.ID_Device = devices.ID_Device
		LEFT JOIN devices_details ON  devices.ID_Device_Detail = devices_details.ID_Device_Detail
		LEFT JOIN locations ON		 devices.ID_Location = locations.ID_Location
		LEFT JOIN devices_types ON	 devices.ID_Device_Type = devices_types.ID_Device_Type
		LEFT JOIN solutions ON		 relations.ID_solution = solutions.ID_solutions
		LEFT JOIN problems ON		 relations.ID_problem = problem.ID_problem
		LEFT JOIN departments ON 	 employees.ID_Department = departments.ID_Department
		LEFT JOIN jobs ON 			 employees.ID_Job = jobs.ID_Job");
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
		 <a href="editingRequest.php?ticket=<?php echo $_GET['ticket']?>">edit ticket</a>

</body>

</html>
