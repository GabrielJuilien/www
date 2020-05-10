<?php
session_start();

if (!$_SESSION['user_id']) {
  header("Location:/login.php");
}

if ($_SESSION['user_role'] !== 3) {
  echo "You don't have permission to access this page.";
  exit();
}

try {
  $bdd = new PDO('mysql:dbname=helpdesk;host=localhost', 'helpdesk_default', 'xixn2lCbJe90Xa8n');
}
catch(PDOException $e) {
  $e->getMessage();
}

if (isset($_POST['time'])) {
  $time = $_POST['time'];
}
else {
  $time = "week";
}

if (isset($_POST['back_time'])) {
    $back_time = $_POST['back_time'];
}
else {
  $back_time = 0;
}

if (!isset($_POST['ID_Operator'])) {
  ?>
  You must provide an Operator's ID.
  <?php
  exit();
}

if(!strcmp("week", $time))
{
  $request = $bdd->prepare("SELECT DAYOFWEEK(requests.Submission_Datetime) AS DOW, COUNT(requests.ID_Request) AS count from requests WHERE DAY(requests.Submission_DateTime)>DAY(CURRENT_TIMESTAMP) - ? * 7 AND DAY(requests.Submission_DateTime) < DAY(CURRENT_TIMESTAMP) - ? * 7 + 7 AND requests.ID_Operator= ? GROUP BY YEAR(requests.Submission_DateTime),MONTH(requests.Submission_DateTime),DAY(requests.Submission_DateTime) ");
  $request->bindParam(1, $back_time);
  $request->bindParam(2, $back_time);
}
else
{
  $request = $bdd->prepare("SELECT MONTH(requests.Submission_Datetime) AS month, COUNT(requests.ID_Request) AS count from requests WHERE DAY(requests.Submission_DateTime)>DAY(CURRENT_TIMESTAMP) - ? * 365 AND DAY(requests.Submission_DateTime) < DAY(CURRENT_TIMESTAMP) - ? * 365 + 365 AND requests.ID_Operator = ? GROUP BY YEAR(requests.Submission_DateTime),MONTH(requests.Submission_DateTime)");
  $request->bindParam(1, $back_time);
  $request->bindParam(2, $back_time);
}
$request->bindParam(3, $_POST['ID_Operator']);
$request->execute();
?>

function createOperatorChart() {
  var ctx = document.getElementById('myChart').getContext('2d');
  var chart = new Chart(ctx,
    {
      // The type of chart we want to create10001
      type: 'bar',

      // The data for our dataset
      data:
      {
        <?php
        if(!strcmp("week", $time))
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
          label: 'Number of requests processd by operator <?php echo $_POST['ID_Operator'] ?>',
          backgroundColor: 'rgb(255, 99, 132)',
          borderColor: 'rgb(255, 99, 132)',
          <?php echo $chain ;
          ?>
        }]
      }
    },
  );
}
