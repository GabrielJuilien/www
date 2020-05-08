<?php
session_start();
/*
if (!$_SESSION['user_id']) {
  header("Location:/login.php");
}

if ($_SESSION['user_role'] !== 3) {
  echo "You don't have permission to access this page.";
  exit();
}*/

$time="weeks";
try {
  $bdd = new PDO('mysql:dbname=helpdesk;host=localhost', 'helpdesk_default', 'xixn2lCbJe90Xa8n');
}
catch(PDOException $e) {
  $e->getMessage();
}

if($time=="week")
{
  $request = $bdd->prepare("SELECT DAYOFWEEK(requests.Submission_Datetime) AS DOW, CAST(AVG(requests.Solve_Datetime - requests.Submission_Datetime)AS DATE ) AS VALUE  from requests WHERE DAY(requests.Submission_DateTime)>DAY(CURRENT_TIMESTAMP)-7 AND requests.Solve_Datetime IS NOT NULL GROUP BY YEAR(requests.Submission_DateTime),MONTH(requests.Submission_DateTime),DAY(requests.Submission_DateTime) ");
}
else
{
  $request = $bdd->prepare("SELECT MONTH(requests.Submission_Datetime) AS month, CAST(AVG(requests.Solve_Datetime - requests.Submission_Datetime)AS DATE) AS VALUE from requests WHERE DAY(requests.Submission_DateTime)>DAY(CURRENT_TIMESTAMP)-365 AND requests.Solve_Datetime IS NOT NULL GROUP BY YEAR(requests.Submission_DateTime),MONTH(requests.Submission_DateTime)");
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
          1=>date(DATE_ATOM, mktime(0, 0, 0, 0, 0, 0)),
          2=>date(DATE_ATOM, mktime(0, 0, 0, 0, 0, 0)),
          3=>date(DATE_ATOM, mktime(0, 0, 0, 0, 0, 0)),
          4=>date(DATE_ATOM, mktime(0, 0, 0, 0, 0, 0)),
          5=>date(DATE_ATOM, mktime(0, 0, 0, 0, 0, 0)),
          6=>date(DATE_ATOM, mktime(0, 0, 0, 0, 0, 0)),
          7=>date(DATE_ATOM, mktime(0, 0, 0, 0, 0, 0)),
        );
        while ($Cdata = $request->fetch())
        {
          $tmptab[$Cdata['DOW']]=$Cdata['VALUE'];
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
          1=>date(DATE_ATOM, mktime(0, 0, 0, 0, 0, 0)),
          2=>date(DATE_ATOM, mktime(0, 0, 0, 0, 0, 0)),
          3=>date(DATE_ATOM, mktime(0, 0, 0, 0, 0, 0)),
          4=>date(DATE_ATOM, mktime(0, 0, 0, 0, 0, 0)),
          5=>date(DATE_ATOM, mktime(0, 0, 0, 0, 0, 0)),
          6=>date(DATE_ATOM, mktime(0, 0, 0, 0, 0, 0)),
          7=>date(DATE_ATOM, mktime(0, 0, 0, 0, 0, 0)),
          8=>date(DATE_ATOM, mktime(0, 0, 0, 0, 0, 0)),
          9=>date(DATE_ATOM, mktime(0, 0, 0, 0, 0, 0)),
          10=>date(DATE_ATOM, mktime(0, 0, 0, 0, 0, 0)),
          11=>date(DATE_ATOM, mktime(0, 0, 0, 0, 0, 0)),
          12=>date(DATE_ATOM, mktime(0, 0, 0, 0, 0, 0)),
        );
        while ($Cdata = $request->fetch())
        {
          $tmptab[$Cdata['month']]=$Cdata['VALUE'];
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
        label: 'average time of resolution tickets',
        backgroundColor: 'rgb(255, 99, 132)',
        borderColor: 'rgb(255, 99, 132)',
        <?php echo $chain ;
        ?>

      }]
    }

      scales:
       {
         xAxes:
          [{
            type: 'time',
            time: 
             {
               unit: 'month'
             }
          }]
        }
  },

);
</script>
