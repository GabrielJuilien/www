<?php
session_start();

if (!$_SESSION['user_id']) {
  header("Location:/login.php");
  exit();
}

if ($_SESSION['user_role'] !== 2) {
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
var httpDisplayTasks = new XMLHttpRequest();
var httpUserProfile = new XMLHttpRequest();

var urlDisplayTasks = "/specialist/display_tasks.php";
var urlUserProfile = "/specialist/profile.php";

//Buttons listeners
var buttonDisplayTasks = document.getElementById("display_tasks");
buttonDisplayTasks.addEventListener('click', function() {
  httpDisplayTasks.onreadystatechange = handlerDisplayTasks;
  httpDisplayTasks.open('GET', urlDisplayTasks);
  httpDisplayTasks.send();
  page_content.innerHTML = "";
});

var buttonUserProfile = document.getElementById("display_profile");
buttonUserProfile.addEventListener('click', function() {
  httpUserProfile.onreadystatechange = handlerUserProfile;
  httpUserProfile.open('GET', urlUserProfile);
  httpUserProfile.send();
  page_content.innerHTML = "";
});


//Buttons handlers
function handlerDisplayTasks() {
  if (httpDisplayTasks.readyState === XMLHttpRequest.DONE) {
    page_content.innerHTML = httpDisplayTasks.responseText;
  }
}

function handlerUserProfile() {
  if (httpUserProfile.readyState === XMLHttpRequest.DONE) {
    page_content.innerHTML = httpUserProfile.responseText;
  }
}

//Display request
var httpDisplayRequest = new XMLHttpRequest();

var urlDisplayRequest = "/specialist/display_request.php"

function handlerDisplayRequest() {
  if (httpDisplayRequest.readyState === XMLHttpRequest.DONE) {
    page_content.innerHTML = "" + httpDisplayRequest.responseText;
  }
}

function callbackDisplayRequest(ID_Task) {
  if (typeof(ID_Task) == "number") {
    httpDisplayRequest.onreadystatechange = handlerDisplayRequest;
    httpDisplayRequest.open('GET', urlDisplayRequest + "?ID_Task=" + ID_Task);
    httpDisplayRequest.send();
  }
}

//Transfer request
var httpTransferRequest = new XMLHttpRequest();

var urlTransferRequest = "/specialist/transfer_request.php";

function handlerTransferRequest() {
  if (httpTransferRequest.readyState === XMLHttpRequest.DONE) {
    page_content.innerHTML = httpTransferRequest.responseText;
  }
}

function callbackTransferRequest(ID_Task) {
  if (typeof(ID_Task) == "number") {
    httpTransferRequest.onreadystatechange = handlerTransferRequest;
    httpTransferRequest.open('GET', urlTransferRequest + "?ID_Task=" + ID_Task);
    httpTransferRequest.send();
  }
}

//Request transfer
var httpRequestTransfer = new XMLHttpRequest();

var urlRequestTransfer = "/specialist/request_transfer.php";

function handlerRequestTransfer() {
  if (httpTransferRequest.readyState === XMLHttpRequest.DONE) {
    page_content.innerHTML = httpTransferRequest.responseText;
  }
}

function callbackRequestTransfer(ID_Task, ID_Specialist) {
  var specialist_select = document.getElementById("specialist_select");
  if (typeof(ID_Task) == "number" && typeof(ID_Specialist) == "number") {
    httpTransferRequest.onreadystatechange = handlerRequestTransfer;
    httpTransferRequest.open('GET', urlRequestTransfer + "?ID_Task=" + ID_Task + "&ID_Specialist=" + ID_Specialist);
    httpTransferRequest.send();
  }
}

//Edit request
var httpEditRequest = new XMLHttpRequest();

var urlEditRequest = "/specialist/edit_request.php";

function handlerEditRequest() {
  if (httpEditRequest.readyState === XMLHttpRequest.DONE) {
    page_content.innerHTML = httpEditRequest.responseText;
  }
}

function callbackEditRequest(ID_Request) {
  if(typeof(ID_Request) == "number") {
    httpEditRequest.onreadystatechange = handlerEditRequest;
    httpEditRequest.open('GET', urlEditRequest + "?ID_Request=" + ID_Request);
    httpEditRequest.send();
  }
}

//Discard request changes
var httpDiscardChanges = new XMLHttpRequest();

var urlDiscardChanges = "/specialist/display_tasks.php";

function handlerDiscardChanges() {
  if (httpDiscardChanges.readyState === XMLHttpRequest.DONE) {
    page_content.innerHTML = httpDiscardChanges.responseText;
  }
}

function callbackDiscardChanges() {
  httpDiscardChanges.onreadystatechange = handlerDiscardChanges;
  httpDiscardChanges.open('GET', urlDiscardChanges);
  httpDiscardChanges.send();
}

//Save changes
var httpSaveChanges = new XMLHttpRequest();

var urlSaveChanges = "/specialist/save_changes.php";

function handlerSaveChanges() {
  if (httpSaveChanges.readyState === XMLHttpRequest.DONE) {
    page_content.innerHTML = httpSaveChanges.responseText;
  }
}

function callbackSaveChanges(ID_Request) {
  if (typeof(ID_Request) == "number") {
    httpSaveChanges.onreadystatechange = handlerSaveChanges;
    httpSaveChanges.open('POST', urlSaveChanges);
    httpSaveChanges.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    var params = "ID_Request=" + ID_Request + "&Problem_Title=" + document.getElementById('problem_title').value + "&Problem_Description=" + document.getElementById('problem_description').value + "&Solution_Description=" + document.getElementById('solution_description').value;
    httpSaveChanges.send(params);
  }
}

//Solve request
var httpSolveRequest = new XMLHttpRequest();

var urlSolveRequest = "/specialist/solve_request.php";

function handlerSolveRequest() {
  if (httpSolveRequest.onreadystatechange === XMLHttpRequest.DONE) {
    page_content.innerHTML = httpSolveRequest.responseText;
  }
}

function callbackSolveRequest(ID_Request) {
  if (typeof(ID_Request) == "number") {
    httpSolveRequest.onreadystatechange = handlerSolveRequest;
    httpSolveRequest.open('POST', urlSolveRequest);
    httpSolveRequest.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    var params = "ID_Request=" + ID_Request + "&Problem_Title=" + document.getElementById('problem_title').value + "&Problem_Description=" + document.getElementById('problem_description').value + "&Solution_Description=" + document.getElementById('solution_description').value;
    httpSolveRequest.send(params);
  }
}
