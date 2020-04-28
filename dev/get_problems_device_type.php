<?php
try {
  $bdd = new PDO('mysql:dbname=helpdesk;host=localhost', 'helpdesk_default', 'xixn2lCbJe90Xa8n');
}
catch(PDOException $e) {
  $e->getMessage();
}

$ID_Device = $_GET['ID_Device'];

//Select problems already encountered on similar devices
$request = $bdd->prepare('SELECT DISTINCT problems.ID_Problem, problems.Problem_Title
  FROM requests
  LEFT JOIN relations ON relations.ID_Relation = requests.ID_Relation
  LEFT JOIN problems ON problems.ID_Problem = relations.ID_Problem
  WHERE requests.ID_Device IN
    (SELECT ID_Device FROM devices WHERE ID_Device_Type =
        (SELECT ID_Device_Type FROM devices WHERE ID_Device = ?)
    )
  ');
$request->bindParam(1, $ID_Device);
$request->execute();
echo "<select name=\"problem\" id=\"problems_select\" onchange=\"getAssociatedSolutions()\">";
echo "<option value=\"NULL\"> --- </option>";

while ($data = $request->fetch()) {
  echo "<option value=\"".$data['ID_Problem']."\">".$data['Problem_Title']."</option>";
}
echo "</select>";
?>
