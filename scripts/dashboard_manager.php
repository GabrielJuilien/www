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
var current_time = "week";

function previous() {
  current_date--;
  switchChart(chartToDisplay);
}

function next() {
  current_date++;
  switchChart(chartToDisplay);
}

function switch_time() {
  var checkbox = document.getElementById('time_switch');
  if (checkbox.checked) {
    current_time = "year";
  }
  else {
    current_time = "week";
  }
current_date = 0;
  switchChart(chartToDisplay);
}

function switchChart(chart_type) {
  chartToDisplay = chart_type;
  document.getElementById('charts').innerHTML = '<canvas id="myChart"></canvas>';
  switch(chart_type) {
    case "none":
    chart_quit.style.display = "none";
    time_selector.style.display = "none";
    period_selector.style.display = "none";
    operator_selector.style.display = "none";
    charts_buttons.style.display = "block";
    break;
    case "time":
    chart_quit.style.display = "block";
    period_selector.style.display = "block";
    operator_selector.style.display = "none";
    time_selector.style.display = "block";
    charts_buttons.style.display = "none";
    callbackTimeChart();
    break;
    case "general":
    chart_quit.style.display = "block";
    period_selector.style.display = "block";
    operator_selector.style.display = "none";
    time_selector.style.display = "block";
    charts_buttons.style.display = "none";
    callbackGeneralChart();
    break;
    case "operator":
    chart_quit.style.display = "block";
    period_selector.style.display = "block";
    operator_selector.style.display = "block";
    time_selector.style.display = "block";
    charts_buttons.style.display = "none";
    callbackOperatorChart();
    break;
    default:
    break;
  }
}

//Time chart
var httpTimeChart = new XMLHttpRequest();

var urlTimeChart = "/manager/time_chart.php";

function handlerTimeChart() {
  if (httpTimeChart.readyState === XMLHttpRequest.DONE) {
    page_content.removeChild(document.getElementById('chart_script'));
    var script = document.createElement("script");
    script.type = 'text/javascript';
    script.id = "chart_script";
    script.innerHTML = httpTimeChart.responseText;
    page_content.append(script);
    createTimeChart();
  }
}

function callbackTimeChart() {
  httpTimeChart.onreadystatechange = handlerTimeChart;
  httpTimeChart.open('POST', urlTimeChart);
  httpTimeChart.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

  var param = "back_time=" + current_date;
  param += "&time=" + current_time;
  httpTimeChart.send(param);
}

//General chart
var httpGeneralChart = new XMLHttpRequest();

var urlGeneralChart = "/manager/general_chart.php";

function handlerGeneralChart() {
  if (httpGeneralChart.readyState === XMLHttpRequest.DONE) {
    page_content.removeChild(document.getElementById('chart_script'));
    var script = document.createElement("script");
    script.type = 'text/javascript';
    script.id = "chart_script";
    script.innerHTML = httpGeneralChart.responseText;
    page_content.append(script);
    createGeneralChart();
  }
}

function callbackGeneralChart() {
  httpGeneralChart.onreadystatechange = handlerGeneralChart;
  httpGeneralChart.open('POST', urlGeneralChart);
  httpGeneralChart.setRequestHeader('Content-type', "application/x-www-form-urlencoded");

  var param = "back_time=" + current_date;
  param += "&time=" + current_time;
  httpGeneralChart.send(param);
}

//Operator chart
var httpOperatorChart = new XMLHttpRequest();

var urlOperatorChart = "/manager/operator_chart.php";

function handlerOperatorChart() {
  if (httpOperatorChart.readyState === XMLHttpRequest.DONE) {
    page_content.removeChild(document.getElementById("chart_script"));
    var script = document.createElement("script");
    script.type = "text/javascript";
    script.id = "chart_script";
    script.innerHTML = httpOperatorChart.responseText;
    page_content.append(script);
    createOperatorChart();
  }
}

function callbackOperatorChart() {
  httpOperatorChart.onreadystatechange = handlerOperatorChart;
  httpOperatorChart.open('POST', urlOperatorChart);
  httpOperatorChart.setRequestHeader('Content-type', "application/x-www-form-urlencoded");

  var param = "back_time=" + current_date;
  param += "&time=" + current_time;
  param += "&ID_Operator=" + operator_select.options[operator_select.selectedIndex].value;
  httpOperatorChart.send(param);
}
