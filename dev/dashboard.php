<html>
  <head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="style/dashboard.css"/>
    <link rel="stylesheet" href="style/login.css"/>
  </head>
  <body>
  <?php
  session_start();


  if (isset($_SESSION['user_id']))
  {
    ?>
     <p>You're logged in. You can access to your profile <a href="myprofile.php">here.</a>
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
