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
              <?php
                switch($_SESSION['user_role'])
                {
                  case 0:
                    echo "<p>Role : Simple User</p>";
                    break;
                  case 1:
                    echo "<p>Role : Operator</p>";
                    break;
                  case 2:
                    echo "<p>Role : Specialist</p>";
                    break;
                  case 3:
                    echo "<p>Role : Manager</p>";
                    break;
                  case 4:
                    echo "<p>Role : Admin</p>";
                    break;
                }
              ?>
              <p>Change Password :</p>
              <input type="password" id="user_password" placeholder="Current Password"/></br>
              <input type="password" id="new_password" placeholder="New Password"/></br>
              <input type="password" id="new_password2" placeholder="Re-write New Password"/></br>
              <input type="submit" id="button" onclick="submitform()" value="Change"/>
            </div>
    <?php
        }
    ?>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
    <script type="text/javascript">
      function submitform()
      {
        var Pass1;
        var Pass2;
        Pass1 = document.getElementById("user_password");
        Pass2 = document.getElementById("new_password");
        Pass3 = document.getElementById("new_password2");
        Button = document.getElementById("button");
        if(Pass1.value == "" || Pass2.value == "" || Pass3.value == "")
        {
          if(Pass1.value == "")
          {
            Pass1.style.backgroundColor = "pink";
            if(!document.getElementById("OldPass"))
            {
              var op = document.createElement("P");
              op.setAttribute("id","OldPass");
              var ot = document.createTextNode("Please enter your password !");
              op.appendChild(ot);
              Pass1.parentNode.insertBefore(op, Pass1.nextSibling);
            }
          }
          if(Pass2.value == "")
          {
            Pass2.style.backgroundColor = "pink";
            if(!document.getElementById("NewPasstry"))
            {
              var np = document.createElement("P");
              np.setAttribute("id","NewPasstry");
              var nt = document.createTextNode("Please enter your new password !");
              np.appendChild(nt);
              Pass2.parentNode.insertBefore(np, Pass2.nextSibling);
            }
          }
          if(Pass3.value == "")
          {
            Pass3.style.backgroundColor = "pink";
          }
          if(Pass1.value != "")
          {
            Pass1.style.backgroundColor = "white";
          }
          if(Pass2.value != "")
          {
            Pass2.style.backgroundColor = "white";
          }
          if(Pass3.value != "")
          {
            Pass3.style.backgroundColor = "white";
          }
          return 1;
        }
        if(Pass2.value != Pass3.value)
        {
          Pass3.style.backgroundColor = "pink";
          var p = document.createElement("P");
          p.setAttribute("id","NewPass");
          var t = document.createTextNode("The two new passwords doesn't match !");
          p.appendChild(t);
          Pass3.parentNode.insertBefore(p, Pass3.nextSibling);
          return 2;
        }
        console.log(Pass1.value);
        console.log(Pass2.value);
        console.log(Pass3.value);
        currPass = Pass1.value;
        newPassw = Pass2.value;
        Pass1.value ="";
        Pass2.value ="";
        Pass3.value ="";
        try{
          var WrongPass = document.getElementById("NewPass");
          WrongPass.parentNode.removeChild(WrongPass);
        }
        catch(err){}
        try{
          var oWrongPass = document.getElementById("OldPass");
          oWrongPass.parentNode.removeChild(oWrongPass);
        }
        catch(err){}
        try{
          var nWrongPass = document.getElementById("NewPasstry");
          nWrongPass.parentNode.removeChild(nWrongPass);
        }
        catch(err){}
        try{
          var SupButton = document.getElementById("Error");
          SupButton.parentNode.removeChild(SupButton);
        }
        catch(err){}
        try{
          var SupButton = document.getElementById("Success");
          SupButton.parentNode.removeChild(SupButton);
        }
        catch(err){}
        Pass1.style.backgroundColor = "white";
        Pass2.style.backgroundColor = "white";
        Pass3.style.backgroundColor = "white";
        $.ajax({
          url: 'changepassword.php',
          type: 'GET',
            data: { oldPass: currPass, newPass: newPassw },
            success: function(data) {
              if(data=="1")
              {
                var p = document.createElement("P");
                p.setAttribute("id","Error");
                var t = document.createTextNode("You put the wrong password !");
                p.appendChild(t);
                Button.parentNode.insertBefore(p, Button.nextSibling);
              }
              if(data=="0")
              {
                var p = document.createElement("P");
                p.setAttribute("id","Success");
                var t = document.createTextNode("Password succesfully changed !");
                p.appendChild(t);
                Button.parentNode.insertBefore(p, Button.nextSibling);
              }
              console.log(data);
            }
        });
      }
    </script>

  </body>
</html>
