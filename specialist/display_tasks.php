<?php
session_start();

if (!$_SESSION['user_id']) {
  header('Location:/login.php');
}

if ($_SESSION['user_role'] !== 2) {
  echo "You don't have permission to access this page.";
  exit();
}

try {
  $bdd = new PDO('mysql:dbname=helpdesk;host=localhost', 'helpdesk_default', 'xixn2lCbJe90Xa8n');
}
catch(PDOException $e) {
  $e->getMessage();
}

$ID_Specialist = $_SESSION['user_id'];

$request = $bdd->prepare('SELECT tasks.ID_Task, tasks.Reception_Datetime, requests.ID_Request, problems.Problem_Title FROM tasks
  JOIN requests ON requests.ID_Request = tasks.ID_Request
  JOIN relations ON relations.ID_Relation = requests.ID_Relation
  JOIN problems ON problems.ID_Problem = relations.ID_Problem
  WHERE tasks.ID_Specialist = ? AND tasks.Expedition_Datetime IS NULL
  ');
  $request->bindParam(1, $ID_Specialist);
  $request->execute();

  if (!$request) {
    echo "Error: Couldn't retrieve data for your ID.";
    exit();
  }
  if ($request === 1) {
    echo "You have no task.";
    exit();
  }

  try {
    $task = $request->fetch();
  }
  catch (Exception $e) {
    ?>
    No task to display.
    <?php
    exit();
  }

  if (!$task) {
    ?>
    No task to display.
    <?php
    exit();
  }

  ?>
  <div class="conteneurticket">
    <h3>Request n°<?php echo $task['ID_Request']; ?></h3>
    Received: <?php echo $task['Reception_Datetime']; ?><br />
    <?php echo $task['Problem_Title']; ?><br />
    <button class="display_request" onclick="callbackEditRequest(<?php echo $task['ID_Task'] ?>)">View request</button>
    <button class="request_transfer" onclick="callbackTransferRequest(<?php echo $task['ID_Task']; ?>)">Transfer request</button>
  </div>
  <?php

  while ($task = $request->fetch()) {
    ?>
    <hr class="separator">
    <div class="conteneurticket">
      <h3>Request n°<?php echo $task['ID_Request']; ?></h3>
      Received: <?php echo $task['Reception_Datetime']; ?><br />
      <?php echo $task['Problem_Title']; ?><br />
      <button class="display_request" onclick="callbackEditRequest(<?php echo $task['ID_Task'] ?>)">View request</button>
      <button class="request_transfer" onclick="callbackTransferRequest(<?php echo $task['ID_Task']; ?>)">Transfer request</button>
    </div>
    <?php
  }
  ?>
