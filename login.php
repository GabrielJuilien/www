<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  ?>
  <html>
    <head>
      <title>Login Page</title>
      <link rel="stylesheet" href="style/login.css"/>
    </head>
    <body>
      <section>
        <div id=loginboard>
          <img src="image/helpdesk_logo.png"/>
          <form action="template.php" method="post">
            <input type="text" name="user_login" placeholder="Login"/><br />
            <input type="password" name="user_password" placeholder="Password"/><br />
            <input type="submit" Value="Submit">
          </form>
        </div>
      </section>
      <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
      <script type="text/javascript">
        $('section').mousemove(function(e){
          var moveX = (e.pageX * -1 / 50);
          var moveY = (e.pageY * -1 / 50);
          $(this).css('background-position', moveX + 'px ' + moveY + 'px')
        })
      </script>
    </body>
  </html>
  <?php
}
else {
  ?>
  <html>
    <head>
      <title>Login Page</title>
      <link rel="stylesheet" href="style/login.css"/>
    </head>
    <body>
      <div id=loginboard>
        <img src="image/helpdesk_logo.png"/>
        You are logged in. Access your <a href="template.php">dashboard</a> or <a href="disconnect.php">disconnect.</a>
      </div>
    </body>
  </html>
  <?php
}
?>
