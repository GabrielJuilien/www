<?php
session_start();

if (!$_SESSION['user_id']) {
  header('Location:/login.php');
}

if ($_SESSION['user_role'] !== 2) {
  echo "You don't have permission to access this page.";
  exit();
}

try {
  $bdd = new PDO('mysql:dbname=helpdesk;host=localhost', 'helpdesk_default', 'xixn2lCbJe90Xa8n');
}
catch(PDOException $e) {
  $e->getMessage();
}
if($time=="week")
{
	$request = $bdd->prepare("SELECT COUNT(requests.ID_Request) from requests WHERE requests.Solve_Datetime IS NULL AND DAY(requests.Submission_DateTime)>DAY(CURRENT_TIMESTAMP)-7 GROUP BY YEAR(requests.Submission_DateTime),MONTH(requests.Submission_DateTime),DAY(requests.Submission_DateTime) ");
}
else
{
	$request = $bdd->prepare("SELECT COUNT(requests.ID_Request) from requests WHERE requests.Solve_Datetime IS NULL AND DAY(requests.Submission_DateTime)>DAY(CURRENT_TIMESTAMP)-365 GROUP BY YEAR(requests.Submission_DateTime),MONTH(requests.Submission_DateTime)");
}
$request->execute();

?>

<?php 
$time="weeks";
?>
<canvas id="myChart"></canvas>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
<script>
var ctx = document.getElementById('myChart').getContext('2d');
var chart = new Chart(ctx, {
    // The type of chart we want to create
    type: 'line',

    // The data for our dataset
    <?php 
	    	if($time=="week") 
	    	{
	    		
	    		echo  "data: { labels: ['Monday', 'Tuesday', 'Wedneday', 'Thursday', 'Friday'],";
	    	}
	    	else
	    	{
	    		echo "data: { labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July','August','September','October','November','December'],";
	        }?>
        datasets: [{
            label: 'My First dataset',
            backgroundColor: 'rgb(255, 99, 132)',
            borderColor: 'rgb(255, 99, 132)',
            data:

             <?php
             echo '[';
				while ($Cdata = $request->fetch())
 				{
				 	echo "'".$Cdata.","
				}
			echo ']' 
            ?>
        }]

    },

    // Configuration options go here
    options: {}
});
</script>

