<?php
    session_start();
    try {
        $bdd = new PDO('mysql:dbname=helpdesk;host=localhost', 'helpdesk_default', 'xixn2lCbJe90Xa8n');
    }
        catch(PDOException $e) {
        $e->getMessage();
    }
    $newpass = $_GET['newPass'];
    $oldpass = $_GET['oldPass'];
    $res = $bdd->prepare("SELECT Password_Imprint, Password_Salt FROM employees WHERE ID_Employee=:employee LIMIT 1");
    $id = $_SESSION["user_id"];
    $res->execute(array(':employee' => $id));
    $pass = $res->fetch();
    if(isset($pass['Password_Salt']))
    {
        $oldpass = hash("sha256",$oldpass.$pass['Password_Salt']);
    }
    else{
        $oldpass = hash("sha256",$oldpass);
    }
    if(strcmp($oldpass,$pass['Password_Imprint']))
    {
        echo "1";
    }
    else{
        $res = $bdd->prepare("UPDATE employees SET Password_imprint=:newpass WHERE ID_Employee=:employee");
        if(isset($pass['Password_Salt']))
        {
            $newpassword = hash("sha256",$newpass.$pass['Password_Salt']);
            echo "oskour";
        }
        else{
            $newpassword = hash("sha256",$newpass);
        }
        $res->execute(array(':newpass' => $newpassword, ':employee' => $id));
        echo "0";
    }
?>