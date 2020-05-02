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
document.getElementById("left_menu").addEventListener('click', function(event) {
  if(event.target && event.target.className === "button") {
    event.target.style.borderRightColor = "rgb(0,0,0,0)";
    event.target.style.backgroundColor = "#BBB";
    event.target.style.color = "#EEE";
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
    switch(httpDisplayTasks.status) {
      case 200:
      break;
      default:
      return;
      break;
    }
    page_content.innerHTML = httpDisplayTasks.responseText;
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
