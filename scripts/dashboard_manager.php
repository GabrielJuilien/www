<?php
session_start();

if (!$_SESSION['user_id']) {
  header("Location:/login.php");
  exit();
}

if ($_SESSION['user_role'] !== 3) {
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
var httpPerformanceCharts = new XMLHttpRequest();
var httpUserProfile = new XMLHttpRequest();

var urlPerformanceCharts = "/manager/charts.php";
var urlUserProfile = "/manager/profile.php";

//Buttons listeners
var buttonPerformanceCharts = document.getElementById('display_performances');
buttonPerformanceCharts.addEventListener('click', function() {
  httpPerformanceCharts.onreadystatechange = handlerPerformanceCharts;
  httpPerformanceCharts.open('GET', urlPerformanceCharts);
  httpPerformanceCharts.send();
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
function handlerPerformanceCharts() {
  if (httpPerformanceCharts.readyState === XMLHttpRequest.DONE) {
    page_content.innerHTML = httpPerformanceCharts.responseText;

    chartToDisplay = "none";

    chart_quit = document.getElementById('chart_quit')

    charts_buttons = document.getElementById('charts_buttons');

    operator_select = document.getElementById('operator_select');
    operator_selector = document.getElementById('operator_selector');

    period_selector = document.getElementById('period_selector');

    time_selector = document.getElementById('time_selector');
    time_switch = document.getElementById('time_switch');
  }
}

function handlerUserProfile() {
  if (httpUserProfile.readyState === XMLHttpRequest.DONE) {
    page_content.innerHTML = httpUserProfile.responseText;
  }
}

//Chart switch

var chartToDisplay;

var chart_quit;

var charts_buttons;

var time_selector;
var time_switch;

var period_selector;

var operator_select;
var operator_selector;

var current_date = 0;

function previous() {
  current_date--;
}

function next() {
  current_date++;
}

function switchChart(chart_type) {
  switch(chart_type) {
    case "none":
      charts.style.display = "none";
      chart_quit.style.display = "none";
      time_selector.style.display = "none";
      period_selector.style.display = "none";
      operator_selector.style.display = "none";
      charts_buttons.style.display = "block";
      break;
    case "time":
      charts.style.display = "block";
      chart_quit.style.display = "block";
      period_selector.style.display = "block";
      operator_selector.style.display = "none";
      time_selector.style.display = "block";
      charts_buttons.style.display = "none";
      break;
    case "general":
      charts.style.display = "block";
      chart_quit.style.display = "block";
      period_selector.style.display = "block";
      operator_selector.style.display = "none";
      time_selector.style.display = "block";
      charts_buttons.style.display = "none";
      break;
    case "operator":
      charts.style.display = "block";
      chart_quit.style.display = "block";
      period_selector.style.display = "block";
      operator_selector.style.display = "block";
      time_selector.style.display = "block";
      charts_buttons.style.display = "none";
      break;
    default:
      break;
  }
}
