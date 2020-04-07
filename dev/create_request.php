<?php
if (!$_SESSION['user_id']) header('Location:login.php');
try {
  $bdd = new PDO('mysql:dbname=helpdesk;host=localhost', 'helpdesk_manager', 'JwfSswy19X5iSZ8P');
}
catch(PDOException $e) {
  $e->getMessage();
}
$user_id = $_SESSION['user_id'];
?>
<html>
  <body>
    <form action="create_request.php" method="post">
      <input type="hidden" name="action" value="create"/>
      <select name="device">
        <?php
        $query = $bdd->query('SELECT ID_Device FROM devices');
        while ($device = $query->fetch()) {
          ?>
          <option value="<?php echo $device['ID_Device']; ?>"><?php echo $device['ID_Device']; ?></option>
          <?php
        }
        ?>
      </select>
    </form>
  </body>
</html>
