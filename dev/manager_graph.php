<!DOCTYPE html>
<html>
  <head>
  <link rel="stylesheet" href="style/manager_graph.css"/>
  </head>
  <body>
    <button id="button">Oui</button>

    <script>

    var httpRequest = new XMLHttpRequest();
    document.getElementById("button").addEventListener('click', makeRequest);


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
        var XML = httpRequest.responseText;
        alert(XML);
      }
    }

    function makeRequest() {
      httpRequest.onreadystatechange = handler;
      httpRequest.open('GET', 'getGraphData.php', true);
      httpRequest.send();
    }
    </script>
  </body>
</html>
