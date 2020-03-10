<html>
  <head>
    <title>DashBoard</title>
    <link rel="stylesheet" href="style/helpdesk.css"/>
  </head>
  <body>
  <?php
  session_start();
  if (!isset($_SESSION['user_id'])) { //If user is not already logged in
      if (empty($_POST['user_login']) || empty($_POST['user_password'])) {
        ?>
        <div>
          You have to be logged in to access your dashboard. You can <a href="login.php">login here.</a>
        </div>
        <?php
      }
  }
  else
  {
    ?>
    <div class="mainframe-left">
      <?php
        // $user_id = 0;
        if($_SESSION['user_id'] == 0)
        {
          include "client-left.php";
        }
        if($_SESSION['user_id'] == 1)
        {

        }
        if($_SESSION['user_id'] == 2)
        {
          
        }
      ?>
    </div>
    <div class="mainframe-middle">
      <?php
        if($_SESSION['user_id'] == 0)
        {
          
        }
        if($_SESSION['user_id'] == 1)
        {
          
        }
        if($_SESSION['user_id'] == 2)
        {
          
        }
      ?>
    </div>
    <div class="mainframe-right">
      <?php
        if($_SESSION['user_id'] == 0)
        {
          
        }
        if($_SESSION['user_id'] == 1)
        {
          
        }
        if($_SESSION['user_id'] == 2)
        {
          
        }
      ?>
    </div>
    <?php
  }
  ?>
  </body>
</html>
