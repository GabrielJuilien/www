<?php
session_start();
?>
<html>
  <head>
    <title>tickets n°<?php echo $_GET["ID_Request"]?></title>
    <link rel="stylesheet" href="style/ticketDisplay.css"/>
    </head>

    <body>
		<div class="titreMagasin">
			INFOS ARTICLE
		</div>
		<?php
		$bdd=	new PDO("mysql:host=127.0.0.1;dbname=helpdesk",$_SESSION["ID"],$_SESSION["mdp"]);//id et mdp tmp //TODO
		$requete = $bdd->prepare("SELECT * FROM requests WHERE :ID_Request=id
		LEFT JOIN relations WHERE 		    requests.ID_Relations = relations.ID_Relations
		LEFT JOIN employees WHERE         requests.ID_Submitter = employees.ID_Employee
		LEFT JOIN devices WHERE			      requests.ID_Device = devices.ID_Device
		LEFT JOIN devices_details WHERE   devices.ID_Device_Detail = devices_details.ID_Device_Detail
		LEFT JOIN locations WHERE         devices.ID_Location = locations.ID_Location
		LEFT JOIN devices_types WHERE     devices.ID_Device_Type = devices_types.ID_Device_Type
		LEFT JOIN solutions WHERE         relations.ID_solution = solutions.ID_solutions
		LEFT JOIN problems WHERE          relations.ID_problem = problem.ID_problem
		LEFT JOIN departments WHERE       employees.ID_Department = departments.ID_Department
		LEFT JOIN jobs WHERE              employees.ID_Job = jobs.ID_Job");
		$requete->execute(array(":id"=>$_GET['ID_Request']));
		$res=$requete->fetch();?>

        <form>

            <input type="text" id="firstName" value=<?php echo $res["First_Name"]?> name="firstName"><br>
            <input type="text" id="lastName" value=<?php echo $res["Last_Name"] ?> name="lastName"><br>
            <input type="text" id="jobName" value=<?php echo $res["Job_Name"] ?> name="jobName"><br>
            <input type="text" id="departmentName" value=<?php echo $res["Department_Name"] ?> name="departmentName"><br>

            <input type="text" id="subDT" value=<?php echo $res["Submission_Datetime"] ?> name="subDT"><br>
            <input type="text" id="processDT" value=<?php echo $res["Processing_Datetime"] ?> name="processDT"><br>
            <input type="text" id="solveDT" value=<?php echo $res["Solve_Datetime"] ?> name="SolveDT"><br>
            <input type="text" id="problemDesc" value=<?php echo $res["Problem_Description"] ?> name="ProblemDesc"><br>
            <input type="text" id="solutionDesc" value=<?php echo $res["Solution_Description"] ?> name="SolutionDesc"><br>
            <input type="text" id="deviceType" value=<?php echo $res["Device_Type_Name"] ?> name="deviceType"><br>
            <input type="text" id="deviceDetails" value=<?php echo $res["Device_Details"] ?> name="deviceDetails"><br>
            <input type="submit" value="submit">
        </form>







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
    </body>

</html>
