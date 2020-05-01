<html>
  <head>
    <link rel="stylesheet" href="/template.css"/>
    <title>Dashboard</title>
  </head>
  <body>
      <div id="menuToggle">

        <input type="checkbox" />
        <img src="/image/Hamburger_icon.png" id="Hamburger_icon"/>

        <div id="left_menu">
          <hr class="separator"/>

          <div class="button" id="show_my_requests">
            <p>
              <text>My requests</text>
              <img class="right_arrow"/>
            </p>
          </div>
          <hr class="separator"/>
          <div class="button" id="create_request_button">
            <p>
              <text>Create a request</text>
              <img class="right_arrow"/>
            </p>
          </div>
          <hr class="separator"/>
          <div class="button" id="show_my_profile">
            <p>
              <text>My profile</text>
              <img class="right_arrow"/>
            </p>
          </div>
          <hr class="separator"/>
        </div>
        <div id="content">
        </div>
      </div>

  </body>
</html>

<script src="scripts/dashboard_default.js">
</script>
