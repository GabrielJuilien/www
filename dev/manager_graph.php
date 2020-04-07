<?php
  try {
    $bdd = new PDO('mysql:dbname=helpdesk;host=localhost', 'helpdesk_manager', 'JwfSswy19X5iSZ8P');
  }
  catch(PDOException $e) {
    $e->getMessage();
  }
?>
<html>
  <head>
  <link rel="stylesheet" href="style/manager_graph.css"/>
  </head>
  <body>
    <select id="operator">
      <?php
      $data = $bdd->query('SELECT ID_Employee AS ID_Operator, First_Name, Last_Name FROM employees
        LEFT JOIN departments
        ON departments.ID_Department = employees.ID_Department
        LEFT JOIN jobs
        ON jobs.ID_Job = employees.ID_Job
        WHERE jobs.Job_Name = "Operator"
        AND departments.Department_Name = "IT"
        ');

        while($operator = $data->fetch()) {
          ?>
          <option value="<?php echo $operator['ID_Operator']?>"><?php echo $operator['ID_Operator']." - ".$operator['Last_Name']." ".$operator['First_Name']; ?></option>
          <?php
        }
      ?>
    </select>

    <button id="button">Oui</button>

    <script>
      var httpRequest = new XMLHttpRequest();
      var url = "getGraphData.php";

      document.getElementById("button").addEventListener('click', makeRequest);
      var button = document.getElementById("button");
      var list = document.getElementById("operator");
      var graph = document.getElementById("graph");

      function handler() {
        if (httpRequest.readyState === XMLHttpRequest.DONE) {
          //Testing response status code
          switch (httpRequest.status) {
            case 200:
              break;
            case 400:
              return;
              break;
          }
          //Get the response containing number of request handled by the operator according to the timestamp and duration
          var response = httpRequest.responseText;
          alert(response);
        }
      }

      function makeRequest() {
        url + "?operator=" + list.options[list.selectedIndex].value + "&timestamp=" + new Date() + "&step=" + "d";
        httpRequest.onreadystatechange = handler;
        httpRequest.open('GET', url + "?operator=" + list.options[list.selectedIndex].value + "&timestamp=" + new Date() + "&step=" + "d", true);
        httpRequest.send();
      }
    </script>
    <div id="graph">
    </div>
  </body>
</html>
