<?php
try {
  $bdd = new PDO('mysql:dbname=helpdesk;host=localhost', 'helpdesk_default', 'xixn2lCbJe90Xa8n');
}
catch(PDOException $e) {
  $e->getMessage();
}


if (!isset($_GET['ID_Request'])) {
  ?>
  You must provide the ID of the request you want to edit.
  <?php
  exit();
}

$request = $bdd->prepare('SELECT COUNT(*) AS count FROM requests WHERE ID_Request = ?');
$request->bindParam(1, $_GET['ID_Request']);
$request->execute();

if (!$request) {
  ?>
  Couldn't find the request with ID <?php echo $_GET['ID_Request']; ?>
  <?php
  exit();
}

$count = $request->fetch();

if ($count['count'] != 1) {
  ?>
  Couldn't find the request with ID <?php echo $_GET['ID_Request']; ?>
  <?php
  exit();
}

$request = $bdd->prepare('SELECT
  requests.ID_Request,
  requests.Submission_Datetime,
  requests.Processing_Datetime,
  requests.Solve_Datetime,

  devices.ID_Device,
  device_types.Device_Type_Name,
  devices_details.Device_Details,
  locations.Location_Name,

  problems.Problem_Title,
  problems.Problem_Description,
  solutions.Solution_Description,

  employees.First_Name AS Submitter_Firstname,
  employees.Last_Name AS Submitter_Lastname,
  jobs.Job_Name AS Submitter_Job,
  departments.Department_Name AS Submitter_Department,

  operator.First_Name AS Operator_Firstname,
  operator.Last_Name AS Operator_Lastname,

  specialist.First_Name AS Specialist_Firstname,
  specialist.Last_Name AS Specialist_Lastname,
  GROUP_CONCAT(specialities.Speciality_Name ORDER BY specialities.Speciality_Name ASC SEPARATOR ", ") AS Specialist_Specialities
  FROM
  requests
  JOIN devices ON devices.ID_Device = requests.ID_Device
  JOIN device_types ON device_types.ID_Device_Type = devices.ID_Device_Type
  JOIN devices_details ON devices_details.ID_Device_Detail = devices.ID_Device_Detail
  LEFT JOIN locations ON locations.ID_Location = devices.ID_Location

  JOIN relations ON relations.ID_Relation = requests.ID_Relation
  JOIN problems ON problems.ID_Problem = relations.ID_Problem
  LEFT JOIN solutions ON solutions.ID_Solution = relations.ID_Relation

  JOIN employees ON employees.ID_Employee = requests.ID_Submitter
  JOIN jobs ON jobs.ID_Job = employees.ID_Job
  JOIN departments ON departments.ID_Department = employees.ID_Department

  LEFT JOIN employees AS operator ON operator.ID_Employee = requests.ID_Operator

  LEFT JOIN tasks ON tasks.ID_Request = requests.ID_Request
  JOIN employees AS specialist ON employees.ID_Employee = tasks.ID_Specialist
  JOIN specialization ON specialist.ID_Employee = specialization.ID_Specialist
  JOIN specialities ON specialities.ID_Speciality = specialization.ID_Speciality

  WHERE
  requests.ID_Request = ?');

  $request->bindParam(1, $_GET['ID_Request']);
  $request->execute();
  $data = $request->fetch();
  $submission_datetime = DateTime::createFromFormat("Y-m-d H:i:s" ,$data['Submission_Datetime']);
  if (!empty($data['Processing_Datetime'])) {
    $processing_datetime = DateTime::createFromFormat("Y-m-d H:i:s", $data['Processing_Datetime']);
  }
  if (!empty($data['Solve_Datetime'])) {
    $solve_datetime = DateTime::createFromFormat("Y-m-d H:i:s", $data['Solve_Datetime']);
  }
  ?>

  <div id="request_info">
    <h3>Request informations:</h3>
    Request number: <?php echo $data['ID_Request']; ?><br />
    Submitted on: <?php echo $submission_datetime->format("d/m/Y"); ?> at <?php echo $submission_datetime->format("H:i"); ?><br />
    <?php
    if (isset($processing_datetime)) {
      ?>
      Request processing started: <?php echo $processing_datetime->format("d/m/Y"); ?> at <?php echo $processing_datetime->format("H:i"); ?><br />
      <?php
    }
    if (isset($solve_datetime)) {
      ?>
      Request solved: <?php echo $solve_datetime->format("d/m/Y"); ?> at <?php echo $solve_datetime->format("H:i"); ?><br />
      <?php
    }
    ?>
  </div>
  <div id="device_info">
    <h3>Device info:</h3>
    <?php
    echo "Device ID: ".$data['ID_Device']."<br />";
    echo "Device type: ".$data['Device_Type_Name']."<br />";
    echo "Device details: ".$data['Device_Details']."<br />";
    if (isset($data['Location_Name'])) {
      echo "Device location: ".$data['Location_Name']."<br />";
    }
    ?>
  </div>
  <div id="problem_info">
    <?php
    if (isset($data['Solve_Datetime'])) { ?>
      <h3><?php echo $data['Problem_Title']; ?></h3>
      <p><?php echo $data['Problem_Description']; ?></p>
      <?php
    }
    else {
      ?>
      <input type="text" name="problem_title" value="<?php echo $data['Problem_Title']; ?>"><br />
      <textarea name="problem_description"><?php echo $data['Problem_Description']; ?></textarea><br />
      <button onclick="callbackCancel()">Discard changes</button>
      <button onclick="callbackSaveRequest(<?php echo $data['ID_Request'] ?>)">Save changes</button>
      <button onclick="callbackTransferRequest(<?php echo $data['ID_Request'] ?>)">Transfer request</button>
      <button onclick="callbackSolveRequest(<?php echo $data['ID_Request'] ?>)">Solve request</button>
      <?php
    }
    ?>
  </div>
  <div id="submitter_info">
    <h3>Submitter informations:</h3>
    Submitted by: <?php echo $data['Submitter_Firstname']." ".$data['Submitter_Lastname']; ?><br />
    Working at <?php echo $data['Submitter_Department']." as ".$data['Submitter_Job']; ?><br />
  </div>
  <?php
  if (isset($processing_datetime)) {
    ?>
    <div id="operator_info">
      <h3>Operator informations:</h3>
      Processed by: <?php echo $data['Operator_Firstname']." ".$data['Operator_Lastname']; ?><br />
    </div>
    <?php
  }
  if(isset($data['Specialist_Firstname']) && isset($data['Specialist_Lastname'])) {
    ?>
    <div id="specialist_info">
      <h3>Specialist informations:</h3>
      Currently handled by: <?php echo $data['Specialist_Firstname']." ".$data['Specialist_Lastname']; ?><br />
      Specialities: <?php echo $data['Specialist_Specialities']; ?><br />
    </div>
    <?php
  }
  ?>
