<html>
  <head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="style/dashboard.css"/>
    <link rel="stylesheet" href="style/login.css"/>
  </head>
  <body>
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
  if (isset($_SESSION['user_id']))
  {
    ?>
    <div class="mainframe">
      <div class="mainframe-left">
        <?php
          // $user_id = 0;
          if ($_SESSION['user_role'] == Roles::Basic)
          {
            include "client-left.php";
          }
          else if ($_SESSION['user_role'] == Roles::Operator)
          {
            include "operator-left.php";

          }
          else if ($_SESSION['user_role'] == Roles::Specialist)
          {
            include "specialist-left.php";
          }
          else if ($_SESSION['user_role'] == Roles::Manager)
          {

          }
          else if ($_SESSION['user_role'] == Roles::Admin)
          {

          }
          else { //Role attribution error

          }
        ?>
      </div>
      <div class="mainframe-middle">
        <?php
        if ($_SESSION['user_role'] === Roles::Basic)
        {
          include "client-middle.php";
        }
        else if ($_SESSION['user_role'] === Roles::Operator)
        {
          include "operator-specialist-middle.php";

        }
        else if ($_SESSION['user_role'] == Roles::Specialist)
        {
          include "operator-specialist-middle.php";

        }
        else if ($_SESSION['user_role'] == Roles::Manager)
        {

        }
        else if ($_SESSION['user_role'] == Roles::Admin)
        {
          include "client-middle.php";

        }
        else { //Role attribution error

        }
        ?>
      </div>
      <div class="mainframe-right">
        <?php
        if ($_SESSION['user_role'] == Roles::Basic)
        {
          include "client-right.php";
        }
        else if ($_SESSION['user_role'] == Roles::Operator)
        {
            include "operator-right.php";
        }
        else if ($_SESSION['user_role'] == Roles::Specialist)
        {
          include "specialist-right.php";
        }
        else if ($_SESSION['user_role'] == Roles::Manager)
        {

        }
        else if ($_SESSION['user_role'] == Roles::Admin)
        {

        }
        else { //Role attribution error

        }
        ?>
      </div>
    </div>
    <?php
  }
  ?>
  </body>
</html>
