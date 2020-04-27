<html>
  <head>
    <link rel="stylesheet" href="template.css"/>
    <title>Dashboard</title>
  </head>
  <body>
    <div id="left_menu">
      <div class="button">
        <p>
          <text>My requests</text>
          <img class="right_arrow" src="right-arrow.png"/>
        </p>
      </div>
      <hr class="separator"/>
      <div class="button" id="create_request_button">
        <p>
          <text>Create a request</text>
          <img class="right_arrow" src="right-arrow.png"/>
        </p>
      </div>
      <hr class="separator"/>
      <div class="button">
        <p>
          <text>My profile</text>
          <img class="right_arrow" src="right-arrow.png"/>
        </p>
      </div>
    </div>
    <div id="content">
    </div>
  </body>
</html>

<script>
var httpCreateRequest = new XMLHttpRequest();
var urlCreateRequest = "create_request.php";

var httpProblemsRequest = new XMLHttpRequest();
var url1 = "get_problems_device_type.php";

var httpSolutionsRequest = new XMLHttpRequest();
var url2 = "get_solutions_from_problem.php";

var httpSetRequest = new XMLHttpRequest();
var url3 = "set_request.php";

var buttonCreateRequest = document.getElementById("create_request_button");
buttonCreateRequest.addEventListener('click', function () {
  httpCreateRequest.onreadystatechange = handlerPageRequest;
  httpCreateRequest.open('GET', urlCreateRequest);
  httpCreateRequest.send();

  page_content.innerHTML = "";
});

var devices_div;
var devices_select;

var problems_div;
var problems_select;

var problem_form;
var problem_form_title;
var problem_form_description;
var problem_form_button;
var problem_form_error;

var page_content = document.getElementById("content");

function handlerPageRequest() {
  if (httpCreateRequest.readyState === XMLHttpRequest.DONE) {
    //Testing response status code
    switch(httpCreateRequest.status) {
      case 200:
      break;
      default:
      return;
      break;
    }

    page_content.innerHTML = httpCreateRequest.responseText;

    devices_div = document.getElementById("devices_div")
    devices_select = document.getElementById("devices_select");

    problems_div = document.getElementById("problems_div");
    problems_select = document.getElementById("problems_select");

    buttonCreateRequest = document.getElementById("create_request_button");

    problem_form = document.getElementById("problem_form");
    problem_form_title = document.getElementById("problem_form_title");
    problem_form_description = document.getElementById("problem_form_description");
    problem_form_button = document.getElementById("problem_form_button");
    problem_form_button.addEventListener("click", function(event) {
      event.preventDefault();
    });
    problem_form_error = document.getElementById("problem_form_error");

    page_content = document.getElementById("content");

  }
}

function handler1() {
  if (httpProblemsRequest.readyState === XMLHttpRequest.DONE) {
    //Testing response status code
    switch(httpProblemsRequest.status) {
      case 200:
      break;
      default:
      return;
      break;
    }

    var response = httpProblemsRequest.responseText;
    problems_div.innerHTML = "Please select your problem, or fill the form if it is not listed:<br />";
    problems_div.innerHTML += response;

    problems_select = document.getElementById("problems_select");
    problem_form.style.display = "block";
  }
}

function handler2() {
  if (httpSolutionsRequest.readyState === XMLHttpRequest.DONE) {
    //Testing response status code
    switch(httpProblemsRequest.status) {
      case 200:
      break;
      default:
      return;
      break;
    }

    var response = httpSolutionsRequest.responseText;
    solutions_div.innerHTML = "Please try following solutions. If your problem isn't fixed or if you are having any trouble with instructions, please fill the form.<br />";
    solutions_div.innerHTML += response;
    problem_form.style.display = "block";
  }
}

function handler3() {
  if (httpSetRequest.readyState === XMLHttpRequest.DONE) {
    //Testing response status code
    switch(httpProblemsRequest.status) {
      case 200:
      break;
      default:
      return;
      break;
    }

    //TODO
    var response = httpSetRequest.responseText;
    if (response == "Success") {

    }
    else {

    }
  }
}

function getAssociatedProblems() {
  if (devices_select.options[devices_select.selectedIndex].value == "NULL") {
    problems_div.innerHTML = "";
  }
  else {
    httpProblemsRequest.onreadystatechange = handler1;
    httpProblemsRequest.open('GET', url1 + "?ID_Device=" + devices_select.options[devices_select.selectedIndex].value);
    httpProblemsRequest.send();
  }
  solutions_div.innerHTML = "";
  problem_form.style.display = "none";
}

function getAssociatedSolutions() {
  if (problems_select.options[problems_select.selectedIndex].value == "NULL") {
    solutions_div.innerHTML = "";
  }
  else {
    httpSolutionsRequest.onreadystatechange = handler2;
    httpSolutionsRequest.open('GET', url2 + "?ID_Problem=" + problems_select.options[problems_select.selectedIndex].value);
    httpSolutionsRequest.send();
  }
}

function setRequest() {
  if (problems_select.options[problems_select.selectedIndex].value == "NULL") {
    if (problem_form_title.value.length <= 0) {
      problem_form_error.innerHTML = "/!\\ You must set a title for your problem.";
    }
    if (problem_form_description.value.length <= 0) {
      problem_form_error.innerHTML += "/!\\ You must must provide a description for your problem.";
    }
    if (problem_form_error.innerHTML.length <= 0) {
      return;
    }
    else {
      httpSetRequest.onreadystatechange = handler3;
      httpSetRequest.open('POST', url3);
      httpSetRequest.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

      var param = "problem_title=" + problem_form_title.value + "&problem_description=" + problem_form_description.value + "&ID_User=10005" + "&ID_Device=" + devices_select.options[devices_select.selectedIndex].value; <?php #echo $_SESSION['user_id']?>
      httpSetRequest.send(param);
    }
  }
  else {
    httpSetRequest.onreadystatechange = handler3;
    httpSetRequest.open('POST', url3);
    httpSetRequest.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    var param = "ID_Problem=" + problems_select.options[problems_select.selectedIndex].value + "&ID_User=10005" + "&ID_Device=" + devices_select.options[devices_select.selectedIndex].value; <?php #echo $_SESSION['user_id']?>
    httpSetRequest.send(param);
  }
}
</script>
