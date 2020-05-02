<?php
session_start();

if (!$_SESSION['user_id']) {
  header("Location:/login.php");
}

if ($_SESSION['user_role'] !== 2) {
  echo "You don't have permission to access this page.";
  exit();
}
?>
<html>
<head>
  <title>Request n°<?php echo $_GET["ID_Task"]?></title>
  <link rel="stylesheet" href="style/ticketDisplay.css"/>
</head>

<body>

  <?php
  $bdd=	new PDO("mysql:host=127.0.0.1;dbname=helpdesk",'helpdesk_default','xixn2lCbJe90Xa8n');//id et mdp tmp
  $requete = $bdd->prepare("SELECT requests.ID_Request, requests.Submission_Datetime, requests.Processing_Datetime, requests.Solve_Datetime, problems.Problem_Description, solutions.Solution_Description, devices_details.Device_Details, device_types.Device_Type_Name, employees.First_Name, employees.Last_Name, departments.Department_Name, jobs.Job_Name
    FROM requests
    LEFT JOIN relations ON 		 requests.ID_Relation = relations.ID_Relation
    LEFT JOIN employees ON		 requests.ID_Submitter = employees.ID_Employee
    LEFT JOIN devices ON			 requests.ID_Device = devices.ID_Device
    LEFT JOIN devices_details ON  devices.ID_Device_Detail = devices_details.ID_Device_Detail
    LEFT JOIN locations ON		 devices.ID_Location = locations.ID_Location
    LEFT JOIN device_types ON	 devices.ID_Device_Type = device_types.ID_Device_Type
    LEFT JOIN solutions ON		 relations.ID_solution = solutions.ID_solution
    LEFT JOIN problems ON		 relations.ID_problem = problems.ID_problem
    LEFT JOIN departments ON 	 employees.ID_Department = departments.ID_Department
    LEFT JOIN jobs ON 			 employees.ID_Job = jobs.ID_Job
    WHERE requests.ID_Request = (SELECT tasks.ID_Request FROM tasks WHERE tasks.ID_Task = ?)
    ");
    $requete->bindParam(1,$_GET['ID_Task']);
    $requete->execute();
    $res = $requete->fetch();
    echo $res["Submission_Datetime"];
    echo $res["Processing_Datetime"];
    echo $res["Solve_Datetime"];
    echo $res["Problem_Description"];
    echo $res["Solution_Description"];
    echo $res["Device_Details"];
    echo $res["Device_Type_Name"];
    echo $res["First_Name"];
    echo $res["Last_Name"];
    echo $res["Department_Name"];
    echo $res["Job_Name"];
    ?>
    <a href="edit_request.php?ticket=<?php echo $res["ID_Request"]?>">Edit request</a>
    <button 

  </body>

  </html>
