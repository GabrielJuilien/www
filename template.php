<?php
session_start();

if (!isset($_SESSION['user_role']) || !isset($_SESSION['user_id'])) {
  echo "You don't have permission to access this page.";
  exit();
}
?>

<html>
<head>
  <link rel="stylesheet" href="template.css"/>
  <title>Dashboard</title>
</head>
<body>

  <div id="left_menu">
    <hr class="separator"/>
    <?php
    if ($_SESSION['user_role'] === 0) {
      ?>
      <div class="button" id="create_request" onclick="activateButton(this)">
        <p>
          <text>Create a request</text>
          <img class="right_arrow" src="right-arrow.png"/>
        </p>
      </div>
      <hr class="separator"/>
      <div class="button" id="display_requests">
        <p>
          <text>My requests</text>
          <img class="right_arrow" src="right-arrow.png"/>
        </p>
      </div>
      <hr class="separator"/>
      <div class="button" id="display_profile">
        <p>
          <text>My profile</text>
          <img class="right_arrow" src="right-arrow.png"/>
        </p>
      </div>
      <?php
    }
    else if ($_SESSION['user_role'] === 1) {
      ?>
      <div class="button" id="create_request">
        <p>
          <text>Create a request</text>
          <img class="right_arrow" src="right-arrow.png"/>
        </p>
      </div>
      <hr class="separator"/>
      <div class="button" id="display_requests">
        <p>
          <text>Current requests</text>
          <img class="right_arrow" src="right-arrow.png"/>
        </p>
      </div>
      <hr class="separator"/>
      <div class="button" id="display_pending_request">
        <p>
          <text>Pending requests</text>
          <img class="right_arrow" src="right-arrow.png"/>
        </p>
      </div>
      <hr class="separator"/>
      <div class="button" id="display_profile">
        <p>
          <text>My profile</text>
          <img class="right_arrow" src="right-arrow.png"/>
        </p>
      </div>
      <?php
    }
    else if ($_SESSION['user_role'] === 3) {
      ?>
      <div class="button" id="display_tasks">
        <p>
          <text>Current tasks</text>
          <img class="right_arrow" src="right-arrow.png"/>
        </p>
      </div>
      <hr class="separator"/>
      <div class="button" id="display_profile">
        <p>
          <text>My profile</text>
          <img class="right_arrow" src="right-arrow.png"/>
        </p>
      </div>
      <?php
    }
    else if ($_SESSION['user_role'] === 4) {
      ?>
      <div class="button" id="display_performances">
        <p>
          <text>Current requests</text>
          <img class="right_arrow" src="right-arrow.png"/>
        </p>
      </div>
      <hr class="separator"/>
      <div class="button" id="display_pending_request">
        <p>
          <text>Pending requests</text>
          <img class="right_arrow" src="right-arrow.png"/>
        </p>
      </div>
      <hr class="separator"/>
      <div class="button" id="display_profile">
        <p>
          <text>My profile</text>
          <img class="right_arrow" src="right-arrow.png"/>
        </p>
      </div>
      <?php
    }
    ?>
    <hr class="separator"/>
  </div>
  <div id="content">
  </div>
</body>
<?php
switch ($_SESSION['user_role']) {
  case 0: ?>
  <script src="scripts/dashboard_default.js"></script>
  <?php
  break;
  case 1: ?>
  <script src="scripts/dashboard_operator.js"></script>
  <?php
  break;
  case 2: ?>
  <script src="scripts/dashboard_specialist.js"></script>
  <?php
  break;
  case 3: ?>
  <script src="scripts/dashboard_manager.js"></script>
  <?php
  break;
}
?>
</html>
