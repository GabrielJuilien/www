<?php
session_start();
//if (!$_SESSION['user_id']) header('Location:login.php');
try {
  $bdd = new PDO('mysql:dbname=helpdesk;host=localhost', 'helpdesk_default', 'xixn2lCbJe90Xa8n');
}
catch(PDOException $e) {
  $e->getMessage();
}
//$user_id = $_SESSION['user_id'];
?>
<html>
<body>
  <form action="create_request.php" method="post">
    <input type="hidden" name="action" value="create"/>
    Please select the device you are having troubles with:<br />
    <div id="device_div">
      <select id="device" name="device" onchange="getAssociatedProblems()">
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
      <div id="problem_div">
      </div>
    </form>

    <script>

    var httpRequest = new XMLHttpRequest();
    var url = "get_problems_device_type.php"

    var problems_div = document.getElementById("problem_div");
    var devices_select = document.getElementById("device");

    function handler() {
      if (httpRequest.readyState === XMLHttpRequest.DONE) {
        //Testing response status code
        switch(httpRequest.status) {
          case 200:
          break;
          case 400:
          return;
          break;
        }

        var response = httpRequest.responseText;
        problems_div.innerHTML = "Please select your problem, or fill the form if it is not listed:<br />";
        problems_div.innerHTML += response;
      }
    }

    function getAssociatedProblems() {
      if (devices_select.options[devices_select.selectedIndex].value == "NULL") {
        problems_div.innerHTML = "";
      }
      else {
        httpRequest.onreadystatechange = handler;
        httpRequest.open('GET', url + "?ID_Device=" + devices_select.options[devices_select.selectedIndex].value)
        httpRequest.send();
      }
    }

    </script>
  </body>
  </html>
