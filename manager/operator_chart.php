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
  $request = $bdd->prepare("SELECT
        COUNT(ID_Request) AS Processed_Requests,
        WEEKDAY(Processing_Datetime) AS Weekday,
        WEEK(Processing_Datetime) AS Week
    FROM
        requests
    WHERE
        Processing_Datetime IS NOT NULL
        AND ID_Operator = ?
        AND WEEK(Processing_Datetime) = WEEK(NOW()) + ?
        AND WEEKDAY(Processing_Datetime) IN(0, 1, 2, 3, 4)
    GROUP BY
        WEEKDAY(Processing_Datetime)
    ORDER BY
    WEEKDAY(Processing_Datetime)");
  $request->bindParam(1, $_POST['ID_Operator']);
  $request->bindParam(2, $back_time);

  $request2 = $bdd->prepare('SELECT WEEK(NOW()) + ? AS Week');
  $request2->bindParam(1, $back_time);
  $request2->execute();
  $data = $request2->fetch();
  $week = $data['Week'];
}
else
{
  $request = $bdd->prepare("SELECT
        COUNT(ID_Request) AS Processed_Requests,
        MONTH(Processing_Datetime) AS Month,
        YEAR(Processing_Datetime) AS Year
    FROM
        requests
    WHERE
        Processing_Datetime IS NOT NULL
        AND ID_Operator = ?
        AND YEAR(Processing_Datetime) = YEAR(NOW()) + ?
    GROUP BY
        MONTH(Processing_Datetime)
    ORDER BY
        MONTH(Processing_Datetime)");
  $request->bindParam(1, $_POST['ID_Operator']);
  $request->bindParam(2, $back_time);

  $request2 = $bdd->prepare('SELECT YEAR(NOW()) + ? AS Year');
  $request2->bindParam(1, $back_time);
  $request2->execute();
  $data = $request2->fetch();
  $year = $data['Year'];
}
$request->execute();
$data = $request->fetch()
?>

function createOperatorChart() {
  var ctx = document.getElementById('myChart').getContext('2d');
  var chart = new Chart(ctx,
    {
      // The type of chart we want to create
      type: 'bar',

      // The data for our dataset
      data:
      {
        <?php
        if(!strcmp("week", $time))
        {
          echo  "labels: ['Monday', 'Tuesday', 'Wedneday', 'Thursday', 'Friday'],";
          $chain = "data: [";
          for ($i = 0; $i < 5; $i++) {
            if ($data['Weekday'] == $i && $data['Processed_Requests']) {
              $chain .= $data['Processed_Requests'].",";
              $data = $request->fetch();
            }
            else {
              $chain .= "0,";
            }
          }
          $chain.="],";
        }
        else
        {
          echo "labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July','August','September','October','November','December'],";
          $chain="data: [";
          for ($i = 1; $i < 13; $i++ && $data['Processed_Requests']) {
            if ($data['Month'] == $i) {
              $chain .= $data['Processed_Requests'].",";
              $data = $request->fetch();
            }
            else {
              $chain .= "0,";
            }
          }
          $chain.="],";
        }
        ?>
        datasets:
        [{
          label: 'Number of requests processd by operator <?php echo $_POST['ID_Operator']; ?> during <?php if ($time == "week") echo "week ".$week; else echo "year ".$year; ?>',
          backgroundColor: 'rgb(255, 99, 132)',
          borderColor: 'rgb(255, 99, 132)',
          <?php echo $chain; ?>
        }]
      }
    },
  );
}
