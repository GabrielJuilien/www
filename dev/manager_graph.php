<html>
  <head>
  <link rel="stylesheet" href="style/manager_graph.css"/>
  </head>
  <body>
    <script language="text/javascript">

    function graphDataHandler() {
      //Testing response status code
      switch (httpRequest.status) {
        case 200:
          break;
        case 400:
          return;
          break;
      }

    }

    httpRequest = new XMLHttpRequest();
    httpRequest.onreadystatechange = graphDataHandler;

    httpRequest.open('GET', 'getGraphData.php', false);
    httpRequest.send();

    </script>
  </body>
</html>
