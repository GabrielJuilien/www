<?php
session_start();

if (!$_SESSION['user_id']) {
  header("Location:/login.php");
  exit();
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

$request = $bdd->prepare('SELECT ID_Employee, First_Name, Last_Name FROM employees WHERE ID_Job = 4 AND ID_Department = 3');
$request->execute();
?>
<div id="chart_quit">
  <button onclick="switchChart('none')"><img src="/image/cross.png"/></button>
</div>
<div id="charts"><canvas id="myChart"></canvas></div>
<div id="charts_buttons">
  <button onclick="switchChart('time')">Request processing time</button>
  <button onclick="switchChart('general')">Request processed</button>
  <button onclick="switchChart('operator')">Request per operator</button>
</div>
<script id="chart_script"></script>
<table id="selector">
  <tr>
    <td>
      <div id="time_selector">
        <h3>Time period</h3>
        Week
        <label class="switch">
          <input id="time_switch" type="checkbox" onchange="switch_time()">
          <span class="slider round"></span>
        </label>
        Year
      </div>
    </td>
    <td>
      <div id="period_selector">
        <button onclick="previous()"><img src="/image/previous.png"/></button>
        <button onclick="next()"><img src="/image/next.png"/></button>
      </div>
    </td>
    <td>
      <div id="operator_selector">
        <select id="operator_select" onchange="callbackOperatorChart()">
          <?php
          while ($operator = $request->fetch()) {
            ?>
            <option value="<?php echo $operator['ID_Employee']; ?>"><?php echo $operator['ID_Employee']." - ".$operator['First_Name']." ".$operator['Last_Name']; ?></option>
            <?php
          }
          ?>
        </select>
      </div>
    </td>
  </tr>
</table>
</body>
</html>
