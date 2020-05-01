<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>My Profile</title>
    <link rel="stylesheet" href="style/profile.css"/>
  </head>
  <body>
    <?php
        session_start();
        try {
            $bdd = new PDO('mysql:dbname=helpdesk;host=localhost', 'helpdesk_default', 'xixn2lCbJe90Xa8n');
        }
            catch(PDOException $e) {
            $e->getMessage();
        }
        if (!isset($_SESSION['user_id']))
        {
    ?>
            <p>You're not logged in. You can <a href="login.php">login here.</a>
    <?php
        }
        else{
            $req = $bdd->prepare('SELECT First_Name,Last_Name FROM employees WHERE ID_Employee=? LIMIT 1');
            $req->bindParam(1, $_SESSION['user_id']);
            $req->execute();
            $data = $req->fetch();
    ?>
            <div id=profileboard>
              <h1>My Profile</h1>
              <p>Username : <?php echo $_SESSION['user_id'] ?></p>
              <p>First Name : <?php echo $data['First_Name'] ?></p>
              <p>Last Name : <?php echo $data['Last_Name'] ?></p>
              <p>Change Password :</p>
              <input type="password" id="user_password" placeholder="Current Password"/></br>
              <input type="password" id="new_password" placeholder="New Password"/><p></br>
              <input type="submit" onclick="submitform()" value="Change"/>
            </div>
    <?php
        }
    ?>
    <script>
      function submitform()
      {
        var Pass1;
        var Pass2;
        Pass1 = document.getElementById("user_password");
        Pass2 = document.getElementById("new_password");
        if(Pass1.value == "")
        {
          Pass1.style.backgroundColor = "red";
          return 1;
        }
        console.log(Pass1.value);
        console.log(Pass2.value);
        Pass1.value ="";
        Pass2.value = "";
      }
    </script>
  </body>
</html>
