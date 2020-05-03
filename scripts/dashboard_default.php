<?php
session_start();

if (!$_SESSION['user_id']) {
  header("Location:/login.php");
  exit();
}

if ($_SESSION['user_role'] !== 0) {
  echo "You don't have permission to access this page.";
  exit();
}
?>

//Buttons animation
var contentVar = document.getElementById("content");

document.getElementById("left_menu").addEventListener('click', function(event) {
  if(event.target && event.target.className === "button") {
    event.target.style.borderRightColor = "rgb(0,0,0,0)";
    event.target.style.backgroundColor = "#BBB";
    event.target.style.color = "#EEE";
    document.getElementById("content").style.opacity = "1";
    contentVar.classList.add("widthTransitionUp");
  }

  var parent_child_node_tab = event.target.parentNode.childNodes;
  var last = parent_child_node_tab.length - 1;
  var i = 0;
  while(i < last) {
    if(parent_child_node_tab[i].className == "button") {
      if(parent_child_node_tab[i].id !== event.target.id) {
        parent_child_node_tab[i].style.borderRightColor = "#888";
        parent_child_node_tab[i].style.backgroundColor = "#DDD";
        parent_child_node_tab[i].style.color = "#888";
      }
    }
    i++;
  }
});

//Main frame
var page_content = document.getElementById("content");

//Buttons requests
var httpCreateRequestPage = new XMLHttpRequest();
var httpViewUserRequests = new XMLHttpRequest();
var httpUserProfile = new XMLHttpRequest();

var urlCreateRequestPage = "/default/create_request.php";
var urlViewUserRequests = "/default/display_requests.php";
var urlUserProfile = "/default/profile.php";

//Buttons listeners
var buttonCreateRequest = document.getElementById("create_request");
buttonCreateRequest.addEventListener('click', function() {
  httpCreateRequestPage.onreadystatechange = handlerCreateRequestPage;
  httpCreateRequestPage.open('GET', urlCreateRequestPage);
  httpCreateRequestPage.send();
  page_content.innerHTML = "";
});

var buttonViewUserRequests = document.getElementById("display_requests");
buttonViewUserRequests.addEventListener('click', function() {
  httpViewUserRequests.onreadystatechange = handlerViewUserRequests;
  httpViewUserRequests.open('GET', urlViewUserRequests);
  httpViewUserRequests.send();
  page_content.innerHTML = "";
});

var buttonUserProfile = document.getElementById("display_profile");
buttonUserProfile.addEventListener('click', function() {
  httpUserProfile.onreadystatechange = handlerUserProfile;
  httpUserProfile.open('GET', urlUserProfile);
  httpUserProfile.send();
  page_content.innerHTML = "";
});

//Button handlers
function handlerCreateRequestPage() {
  if (httpCreateRequestPage.readyState === XMLHttpRequest.DONE) {
    switch(httpCreateRequestPage.status) {
      case 200:
      break;
      default:
      return;
      break;
    }
    page_content.innerHTML = httpCreateRequestPage.responseText;
    getRequestFormVariables();
  }
}

function handlerViewUserRequests() {
  if (httpViewUserRequests.readyState === XMLHttpRequest.DONE) {
    switch (httpViewUserRequests.status) {
      case 200:
        break;
      default:
        return;
        break;
    }
    page_content.innerHTML = httpViewUserRequests.responseText;
  }
}

function handlerUserProfile() {
  if (httpUserProfile.readyState === XMLHttpRequest.DONE) {
    switch(httpUserProfile.status) {
      case 200:
      break;
      default:
      return;
      break;
    }

    page_content.innerHTML = httpUserProfile.responseText;
  }
}

//Create request
var httpGetProblems = new XMLHttpRequest();
var httpGetSolutions = new XMLHttpRequest();
var httpPostRequest = new XMLHttpRequest();

var urlGetProblems = "/default/get_problems_device_type.php";
var urlGetSolutions = "/default/get_solutions_from_problem.php";
var urlPostRequest = "/default/set_request.php";

var devices_div;
var devices_select;

var problems_div;
var problems_select;

var problem_form;
var user_problem_title;
var user_problem_description;

var problem_form_button;
var problem_form_error;

function getRequestFormVariables() {
  devices_div = document.getElementById("devices_div");
  devices_select = document.getElementById("devices_select");

  problems_div = document.getElementById("problems_div");
  problems_select = document.getElementById("problems_select");

  problem_form = document.getElementById("problem_form");
  user_problem_title = document.getElementById("problem_form_title");
  user_problem_description = document.getElementById("problem_form_description");

  problem_form_button = document.getElementById("problem_form_button");
  problem_form_button.addEventListener("click", function(event) {
    event.preventDefault();
  });
  problem_form_error = document.getElementById("problem_form_error");

  page_content = document.getElementById("content");
}

//Request creation handlers
function handlerGetProblems() {
  if (httpGetProblems.readyState === XMLHttpRequest.DONE) {
    problems_div.innerHTML = "Please select your problem, or fill the form if it is not listed: <br />";
    problems_div.innerHTML += httpGetProblems.responseText;

    problems_select = document.getElementById("problems_select");
    problem_form.style.display = "block";
  }
}

function handlerGetSolutions() {
  if (httpGetSolutions.readyState === XMLHttpRequest.DONE) {
    solutions_div.innerHTML = "Please try following solutions. If your problem isn't fixed or if you are having any trouble with instructions, please fill the form.<br />";
    solutions_div.innerHTML += httpGetSolutions.responseText;
    problem_form.style.display = "block";
  }
}

function handlerPostRequest() {
  if (httpPostRequest.readyState === XMLHttpRequest.DONE) {
    var response = httpPostRequest.responseText;
    if (response == "Error") {
      page_content.innerHTML = "There was an error creating your request. If the problem persists, please contact the helpdesk by phone.";
    }
    else {
      page_content.innerHTML = "Your request was successfully created with number: " + response + ". You can access it using the \"My requests\" panel.";
    }
  }
}

//Request creation callbacks
function callbackGetProblems() {
  if (devices_select.options[devices_select.selectedIndex].value == "NULL") {
    problems_div.innerHTML = "";
  }
  else {
    httpGetProblems.onreadystatechange = handlerGetProblems;
    httpGetProblems.open('GET', urlGetProblems + "?ID_Device=" + devices_select.options[devices_select.selectedIndex].value);
    httpGetProblems.send();
  }
  solutions_div.innerHTML = "";
  problem_form.style.display = "none";
}

function callbackGetSolutions() {
  if (problems_select.options[problems_select.selectedIndex].value == "NULL") {
    solutions_div.innerHTML = "";
  }
  else {
    httpGetSolutions.onreadystatechange = handlerGetSolutions;
    httpGetSolutions.open('GET', urlGetSolutions + "?ID_Problem=" + problems_select.options[problems_select.selectedIndex].value);
    httpGetSolutions.send();
  }
}

function callbackPostRequest() {
  if (problems_select.options[problems_select.selectedIndex].value == "NULL") {
    if (user_problem_title.value.length <= 0) {
      problem_form_error.innerHTML = "/!\\ You must set a title for your problem.";
    }
    if (user_problem_description.value.length <= 0) {
      problem_form_error.innerHTML += "/!\\ You must must provide a description for your problem.";
    }
    if (problem_form_error.innerHTML.length <= 0) {
      return;
    }
    else {
      httpPostRequest.onreadystatechange = handlerPostRequest;
      httpPostRequest.open('POST', urlPostRequest);
      httpPostRequest.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

      var param = "problem_title=" + problem_form_title.value + "&problem_description=" + problem_form_description.value + "&ID_User=<?php echo $_SESSION['user_id']; ?>" + "&ID_Device=" + devices_select.options[devices_select.selectedIndex].value;
      httpPostRequest.send(param);
    }
  }
  else {
    httpPostRequest.onreadystatechange = handlerPostRequest;
    httpPostRequest.open('POST', urlPostRequest);
    httpPostRequest.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    var param = "ID_Problem=" + problems_select.options[problems_select.selectedIndex].value + "&ID_User=<?php echo $_SESSION['user_id']; ?>" + "&ID_Device=" + devices_select.options[devices_select.selectedIndex].value;
    httpPostRequest.send(param);
  }
}

//Display request
var httpDisplayRequest = new XMLHttpRequest();

var urlDisplayRequest = "/default/display_request.php"

function handlerDisplayRequest() {
  if (httpDisplayRequest.readyState === XMLHttpRequest.DONE) {
    page_content.innerHTML = "" + httpDisplayRequest.responseText;
  }
}

function callbackDisplayRequest(ID_Request) {
  if (typeof(ID_Request) == "number") {
    httpDisplayRequest.onreadystatechange = handlerDisplayRequest;
    httpDisplayRequest.open('GET', urlDisplayRequest + "?ID_Request=" + ID_Request);
    httpDisplayRequest.send();
  }
}
