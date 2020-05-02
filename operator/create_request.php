<?php
session_start();

if (!$_SESSION['user_id']) {
  header("Location:/login.php");
}

if ($_SESSION['user_role'] !== 1) {
  echo "You don't have permission to access this page.";
  exit();
}

try {
  $bdd = new PDO('mysql:dbname=helpdesk;host=localhost', 'helpdesk_default', 'xixn2lCbJe90Xa8n');
}
catch(PDOException $e) {
  $e->getMessage();
}
?>
<html>
<body>
  <form action="create_request.php" method="post">
    <input type="hidden" name="action" value="create"/>
    Please select the user you are posting the request for:<br />
    <select id="user_select" name="user">
      <?php
      $request = $bdd->query('SELECT ID_Employee, First_Name, Last_Name FROM employees');
      while ($user = $request->fetch()) {
        ?>
        <option value="<?php echo $user['ID_Employee'] ?>"><?php echo $user['ID_Employee']." - ".$user['First_Name']." ".$user['Last_Name']; ?>
        <?php
      }
      ?>
    </select><br/>
    Please select the device you are having troubles with:<br />
    <div id="device_div">
      <select id="devices_select" name="device" onchange="callbackGetProblems()">
        <option value="NULL">---</option>
        <?php
        $query = $bdd->query('SELECT ID_Device,
          device_types.Device_Type_Name,
          locations.Location_Name
          FROM devices
          LEFT JOIN device_types ON device_types.ID_Device_Type = devices.ID_Device_Type
          LEFT JOIN locations ON locations.ID_Location = devices.ID_Location
          ');

          while ($device = $query->fetch()) {
            ?>
            <option value="<?php echo $device['ID_Device']; ?>">
              <?php echo $device['ID_Device']." - ".$device['Device_Type_Name'];
              if ($device['Location_Name'])
              echo" - ".$device['Location_Name'];
              ?>
            </option>
            <?php
          }
          ?>
        </select>
      </div>
      <div id="problems_div">
      </div>
      <div id="solutions_div">
      </div>
      <div id="problem_form" style="display:none;">
          <input type="text" id="problem_form_title" name="problem_title" placeholder="Give a title to your problem..."></input><br/>
          <textarea id="problem_form_description" name="problem_description" placeholder="Describe your problem here..."></textarea>
          <button id="problem_form_button" onclick="callbackPostRequest()">Post request</button>
          <div id="problem_form_error">
          </div>
      </div>
    </form>
  </body>
  </html>
