<?php
try {
  $bdd = new PDO('mysql:dbname=helpdesk;host=localhost', 'helpdesk_manager', 'JwfSswy19X5iSZ8P');
}
catch(PDOException $e) {
  $e->getMessage();
}
$req = $bdd->query('SELECT ID_Employee, First_Name, Last_Name FROM employees
  LEFT JOIN departments
  ON departments.ID_Department = employees.ID_Department
  LEFT JOIN jobs
  ON jobs.ID_Job = employees.ID_Job
  WHERE jobs.Job_Name = "Operator"
  AND departments.Department_Name = "IT"
  ');
echo "<?xml version=\"1.0\" ?>\n";
echo "<root>\n";
while ($i = $req->fetch()) {
  echo "  <Employee>\n";
  echo "    <ID_Employee>\n";
  echo "      ".$i['ID_Employee']."\n";
  echo "    </ID_Employee>\n";
  echo "    <First_Name>\n";
  echo "      ".$i['First_Name']."\n";
  echo "    </First_Name>\n";
  echo "    <Last_Name>\n";
  echo "      ".$i['Last_Name']."\n";
  echo "    </Last_Name>\n";
  echo "  </Employee>\n";
}
echo "</root>";
?>
