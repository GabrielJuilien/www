<?php
session_start();
?>
<html>
  <head>
    <title>Login Page</title>
    <link rel="stylesheet" href="style/login.css"/>
  </head>
  <body>
    <div id=loginboard>
      <img src="image/helpdesk_logo.png"/>
      <form action="dashboard.php" method="post">
        <input type="text" name="user_login" placeholder="Login"/><br />
        <input type="password" name="user_password" placeholder="Password"/><br />
        <input type="submit" Value="Submit">
      </form>
    </div>
  </body>
</html>
