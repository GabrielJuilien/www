<?php
session_start();

class Roles{
  const __default = self::Basic;
  const Basic = 0;
  const Operator = 1;
  const Specialist = 2;
  const Manager = 3;
  const Admin = 4;
}

try {
  $bdd = new PDO('mysql:dbname=helpdesk;host=localhost', 'helpdesk_default', 'xixn2lCbJe90Xa8n');
}
catch(PDOException $e) {
  $e->getMessage();
}

if (!isset($_SESSION['user_id'])) { //If user is not already logged in
  if (!isset($_POST['user_login']) || !isset($_POST['user_password'])) {
    ?>
    <div>
      You have to be logged in to access your dashboard. You can <a href="login.php">login here.</a>
    </div>
  </body>
  </html>
  <?php
  session_destroy();
  exit();
}
else {
  $req = $bdd->prepare('SELECT Password_Imprint, Password_Salt FROM employees WHERE ID_Employee=? LIMIT 1');
  $req->bindParam(1, $_POST['user_login']);
  $req->execute();
  if (!empty($req)) { //User exists in the database
    $data = $req->fetch();
    if (!strcmp(hash("sha256", $_POST['user_password'].$data['Password_Salt']), $data['Password_Imprint'])) { //If passwords hashes match
      $_SESSION['user_id'] = $_POST['user_login'];

      //Retrieving job/departement informations
      $req = $bdd->prepare('SELECT departments.Department_Name AS Department_Name, jobs.Job_Name AS Job_Name FROM employees
        LEFT JOIN departments
        ON employees.ID_Department = departments.ID_Department
        LEFT JOIN jobs
        ON employees.ID_Job = jobs.ID_Job
        WHERE employees.ID_Employee = ? LIMIT 1');
        $req->bindParam(1, $_SESSION['user_id']);
        $req->execute();

        //Assigning role
        $data = $req->fetch();
        if ($data['Department_Name'] != "IT") {
          $_SESSION['user_role'] = Roles::Basic;
        }
        else if ($data['Job_Name'] == "Operator") {
          $_SESSION['user_role'] = Roles::Operator;
        }
        else if ($data['Job_Name'] == "Manager") {
          $_SESSION['user_role'] = Roles::Manager;
        }
        else if ($data['Job_Name'] == "Specialist") {
          $_SESSION['user_role'] = Roles::Specialist;
        }
        else if ($data['Job_Name'] == "Database Administator") {
          $_SESSION['user_role'] = Roles::Admin;
        }
        else {
          $_SESSION['user_role'] = Roles::Basic;
        }
      }
      else { //If passwords doesn't match
        ?>
        <section>
          <div id=loginboard>
            <img src="image/helpdesk_logo.png"/>
            Unknow credentials. <a href="login.php">Retry.</a>
          </div>
        </section>
      </body>
      </html>
      <?php
      session_destroy();
      exit();
    }
  }
  else { //User doesn't exist in the database
    ?>
    <section>
      <div id=loginboard>
        <img src="image/helpdesk_logo.png"/>
        Unknow credentials. <a href="login.php">Retry.</a>
      </div>
    </section>
  </body>
  </html>
  <?php
  session_destroy();
  exit();
}
}
}
?>

<html>
<head>
  <link rel="stylesheet" href="/template.css"/>
  <title>Dashboard</title>
</head>
<body>
  <div id="Header"> IT Helpdesk
  </div>
  <div id ="base">
    <input type="checkbox" id="openMenu" checked="checked"/>
      <div id="menuToggle">
        <label for="openMenu">
          <img src="/image/Hamburger_icon.png" id="Hamburger_icon"/>
        </label>
        <div id="left_menu">
          <hr class="separator"/>
          <?php
          if ($_SESSION['user_role'] === 0) {
            ?>
            <div class="button" id="create_request">
              <p>
                <text>Create a request</text>
                <img class="right_arrow" src="image/right-arrow.png"/>
              </p>
            </div>
            <hr class="separator"/>
            <div class="button" id="display_requests">
              <p>
                <text>My requests</text>
                <img class="right_arrow" src="image/right-arrow.png"/>
              </p>
            </div>
            <hr class="separator"/>
            <div class="button" id="display_profile">
              <p>
                <text>My profile</text>
                <img class="right_arrow" src="image/right-arrow.png"/>
              </p>
            </div>
            <?php
          }
          else if ($_SESSION['user_role'] === 1) {
            ?>
            <div class="button" id="create_request">
              <p>
                <text>Create a request</text>
                <img class="right_arrow" src="image/right-arrow.png"/>
              </p>
            </div>
            <hr class="separator"/>
            <div class="button" id="display_requests">
              <p>
                <text>Current requests</text>
                <img class="right_arrow" src="image/right-arrow.png"/>
              </p>
            </div>
            <hr class="separator"/>
            <div class="button" id="pending_requests">
              <p>
                <text>Pending requests</text>
                <img class="right_arrow" src="image/right-arrow.png"/>
              </p>
            </div>
            <hr class="separator"/>
            <div class="button" id="display_profile">
              <p>
                <text>My profile</text>
                <img class="right_arrow" src="image/right-arrow.png"/>
              </p>
            </div>
            <?php
          }
          else if ($_SESSION['user_role'] === 2) {
            ?>
            <div class="button" id="display_tasks">
              <p>
                <text>Current tasks</text>
                <img class="right_arrow" src="image/right-arrow.png"/>
              </p>
            </div>
            <hr class="separator"/>
            <div class="button" id="display_profile">
              <p>
                <text>My profile</text>
                <img class="right_arrow" src="image/right-arrow.png"/>
              </p>
            </div>
            <?php
          }
          else if ($_SESSION['user_role'] === 3) {
            ?>
            <div class="button" id="display_performances">
              <p>
                <text>Current requests</text>
                <img class="right_arrow" src="image/right-arrow.png"/>
              </p>
            </div>
            <hr class="separator"/>
            <div class="button" id="display_pending_request">
              <p>
                <text>Pending requests</text>
                <img class="right_arrow" src="image/right-arrow.png"/>
              </p>
            </div>
            <hr class="separator"/>
            <div class="button" id="display_profile">
              <p>
                <text>My profile</text>
                <img class="right_arrow" src="image/right-arrow.png"/>
              </p>
            </div>
            <?php
          }
          ?>
          <hr class="separator"/>
          <div class="button" id="disconnect" onclick='window.location.replace("/disconnect.php")'>
            <p>
              <text>Disconnect</text>
              <img class="right_arrow" src="image/right-arrow.png"/>
            </p>
          </div>
          <hr class="separator"/>
        </div>
      </div>
      <div id="content">
      </div>
  </div>

</body>
<?php
switch ($_SESSION['user_role']) {
  case 0: ?>
  <script src="scripts/dashboard_default.php"></script>
  <?php
  break;
  case 1: ?>
  <script src="scripts/dashboard_operator.php"></script>
  <?php
  break;
  case 2: ?>
  <script src="scripts/dashboard_specialist.php"></script>
  <?php
  break;
  case 3: ?>
  <script src="scripts/dashboard_manager.php"></script>
  <?php
  break;
}
?>
</html>
