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

if(!strcmp("week", $time))
{
  $request = $bdd->prepare("SELECT
    COUNT(ID_Request) AS Unsolved_Requests,
    WEEKDAY(Submission_Datetime) AS Weekday,
    WEEK(Submission_Datetime) AS Week
    FROM
    requests
    WHERE
    Solve_Datetime IS NULL
    AND WEEK(Submission_Datetime) = WEEK(NOW()) + ?
    AND WEEKDAY(Submission_Datetime) IN(0, 1, 2, 3, 4)
    GROUP BY
    WEEKDAY(Submission_Datetime)
    ORDER BY
    WEEKDAY(Submission_Datetime)");
    $request->bindParam(1, $back_time);
  }
  else
  {
    $request = $bdd->prepare("SELECT
      COUNT(ID_Request) AS Unsolved_Requests,
      MONTH(Submission_Datetime) AS Month,
      YEAR(Submission_Datetime) AS Year
      FROM
      requests
      WHERE
      Solve_Datetime IS NULL
      AND YEAR(Submission_Datetime) = YEAR(NOW()) + ?
      GROUP BY
      MONTH(Submission_Datetime)
      ORDER BY
      MONTH(Submission_Datetime)");
      $request->bindParam(1, $back_time);
    }
    $request->execute();
    $data = $request->fetch()
    ?>
    function createGeneralChart() {
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
            $week_num = $data['Week'];
            echo  "labels: ['Monday', 'Tuesday', 'Wedneday', 'Thursday', 'Friday'],";
            $chain = "data: [";
            for ($i = 0; $i < 5; $i++) {
              if ($data['Weekday'] == $i && $data['Unsolved_Requests']) {
                $chain .= $data['Unsolved_Requests'].",";
                $data = $request->fetch();
              }
              else {
                $chain .= "0,";
              }
            }
            $chain.="]";
          }
          else
          {
            $week_num = $data['Year'];
            echo "labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July','August','September','October','November','December'],";
            $chain="data: [";
            for ($i = 1; $i < 13; $i++) {
              if ($data['Month'] == $i && $data['Unsolved_Requests']) {
                $chain .= $data['Unsolved_Requests'].",";
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
            label: 'Requests waiting to be processed in <?php if ($time == "week") echo "week ".$week_num; else echo "year ".$week_num; ?>',
            backgroundColor: 'rgb(255, 99, 132)',
            borderColor: 'rgb(255, 99, 132)',
            <?php echo $chain; ?>
          }]
        }
      }
      );
    }
