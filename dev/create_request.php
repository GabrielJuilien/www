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
      <select id="devices_select" name="device" onchange="getAssociatedProblems()">
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
          <button id="problem_form_button" onclick="setRequest()">Post request</button>
          <div id="problem_form_error">
          </div>
      </div>
    </form>

    <script>

    var httpProblemsRequest = new XMLHttpRequest();
    var url1 = "get_problems_device_type.php";

    var httpSolutionsRequest = new XMLHttpRequest();
    var url2 = "get_solutions_from_problem.php";

    var httpSetRequest = new XMLHttpRequest();
    var url3 = "set_request.php";

    var devices_div = document.getElementById("devices_div")
    var devices_select = document.getElementById("devices_select");

    var problems_div = document.getElementById("problems_div");
    var problems_select = document.getElementById("problems_select");

    var problem_form = document.getElementById("problem_form");
    var problem_form_title = document.getElementById("problem_form_title");
    var problem_form_description = document.getElementById("problem_form_description");
    var problem_form_button = document.getElementById("problem_form_button");
    problem_form_button.addEventListener("click", function(event) {
      event.preventDefault();
    });
    var problem_form_error = document.getElementById("problem_form_error");

    function handler1() {
      if (httpProblemsRequest.readyState === XMLHttpRequest.DONE) {
        //Testing response status code
        switch(httpProblemsRequest.status) {
          case 200:
          break;
          default:
          return;
          break;
        }

        var response = httpProblemsRequest.responseText;
        problems_div.innerHTML = "Please select your problem, or fill the form if it is not listed:<br />";
        problems_div.innerHTML += response;

        problems_select = document.getElementById("problems_select");
        problem_form.style.display = "block";
      }
    }

    function handler2() {
      if (httpSolutionsRequest.readyState === XMLHttpRequest.DONE) {
        //Testing response status code
        switch(httpProblemsRequest.status) {
          case 200:
          break;
          default:
          return;
          break;
        }

        var response = httpSolutionsRequest.responseText;
        solutions_div.innerHTML = "Please try following solutions. If your problem isn't fixed or if you are having any trouble with instructions, please fill the form.<br />";
        solutions_div.innerHTML += response;
        problem_form.style.display = "block";
      }
    }

    function handler3() {
      if (httpSetRequest.readyState === XMLHttpRequest.DONE) {
        //Testing response status code
        switch(httpProblemsRequest.status) {
          case 200:
          break;
          default:
          return;
          break;
        }

        //TODO
        var response = httpSetRequest.responseText;
        if (response == "Success") {

        }
        else {

        }
      }
    }

    function getAssociatedProblems() {
      if (devices_select.options[devices_select.selectedIndex].value == "NULL") {
        problems_div.innerHTML = "";
      }
      else {
        httpProblemsRequest.onreadystatechange = handler1;
        httpProblemsRequest.open('GET', url1 + "?ID_Device=" + devices_select.options[devices_select.selectedIndex].value);
        httpProblemsRequest.send();
      }
      solutions_div.innerHTML = "";
      problem_form.style.display = "none";
    }

    function getAssociatedSolutions() {
      if (problems_select.options[problems_select.selectedIndex].value == "NULL") {
        solutions_div.innerHTML = "";
      }
      else {
        httpSolutionsRequest.onreadystatechange = handler2;
        httpSolutionsRequest.open('GET', url2 + "?ID_Problem=" + problems_select.options[problems_select.selectedIndex].value);
        httpSolutionsRequest.send();
      }
    }

    function setRequest() {
      problem_form_error.innerHTML = "";
      if (problem_form_title.value.length <= 0) {
        problem_form_error.innerHTML = "/!\\ You must set a title for your problem.";
      }
      if (problem_form_description.value.length <= 0) {
        problem_form_error.innerHTML += "/!\\ You must must provide a description for your problem.";
      }
      if (problem_form_error.innerHTML.length <= 0) {
        return;
      }
      else {
        httpSetRequest.onreadystatechange = handler3;
        httpSetRequest.open('POST', url3);
        httpSetRequest.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        var param = "problem_title=" + problem_form_title.value + "&problem_description=" + problem_form_description.value + "&ID_User=10005" /*<?php echo $_SESSION['user_id']?> */
        httpSetRequest.send(param);
      }
    }
    </script>
  </body>
  </html>
