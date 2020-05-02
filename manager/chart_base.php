<?php
session_start();
$time="weeks";
try {
  $bdd = new PDO('mysql:dbname=helpdesk;host=localhost', 'helpdesk_default', 'xixn2lCbJe90Xa8n');
}
catch(PDOException $e) {
  $e->getMessage();
}
if($time=="week")
{
	$request = $bdd->prepare("SELECT DAYOFWEEK(requests.Submission_Datetime) AS DOW, COUNT(requests.ID_Request) AS count from requests WHERE requests.Solve_Datetime IS NULL AND DAY(requests.Submission_DateTime)>DAY(CURRENT_TIMESTAMP)-7 GROUP BY YEAR(requests.Submission_DateTime),MONTH(requests.Submission_DateTime),DAY(requests.Submission_DateTime) ");
}
else
{
  $request = $bdd->prepare("SELECT MONTH(requests.Submission_Datetime) AS month, COUNT(requests.ID_Request) AS count from requests WHERE requests.Solve_Datetime IS NULL AND DAY(requests.Submission_DateTime)>DAY(CURRENT_TIMESTAMP)-365 GROUP BY YEAR(requests.Submission_DateTime),MONTH(requests.Submission_DateTime)");
}
$request->execute();
?>
<canvas id="myChart"></canvas>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
<script>
var ctx = document.getElementById('myChart').getContext('2d');
var chart = new Chart(ctx, 
    {
      // The type of chart we want to create10001
      type: 'line',

      // The data for our dataset
      data:
       { 
        <?php 
    	    	if($time=="week") 
    	    	{
    	    		
    	    		echo  "labels: ['Monday', 'Tuesday', 'Wedneday', 'Thursday', 'Friday'],";?>

              <?php
               $chain="data: [";
               $tmptab = array(
                 1=>0,
                 2=>0,
                 3=>0,
                 4=>0,
                 5=>0,
                 6=>0,
                 7=>0,
               );
              while ($Cdata = $request->fetch())
              {
               $tmptab[$Cdata['DOW']]=$Cdata['count'];
              }
              for($i = 2;$i < 7;$i++)
              {
                $chain.="'".$tmptab[$i]."',";
              }
              $chain =rtrim($chain,",");
              $chain.="],";
    	    	}
    	    	else
    	    	{
    	    		echo "labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July','August','September','October','November','December'],";
              $chain="data: [";
               $tmptab = array(
                 1=>0,
                 2=>0,
                 3=>0,
                 4=>0,
                 5=>0,
                 6=>0,
                 7=>0,
                 8=>0,
                 9=>0,
                 10=>0,
                 11=>0,
                 12=>0,
               );
              while ($Cdata = $request->fetch())
              {
               $tmptab[$Cdata['month']]=$Cdata['count'];
              }
              for($i = 1;$i < 13;$i++)
              {
                $chain.="'".$tmptab[$i]."',";
              }
              $chain =rtrim($chain,",");
              $chain.="],";
    	      }
        ?>
          datasets:
           [{
              label: 'it works!!',
              backgroundColor: 'rgb(255, 99, 132)',
              borderColor: 'rgb(255, 99, 132)',
               <?php echo $chain ;
              ?>
            }]
        }
     },

);
</script>

