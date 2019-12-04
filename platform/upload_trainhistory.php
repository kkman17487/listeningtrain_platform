<?php
header('Content-Type: application/json; charset=UTF-8');
include('connect_to_sql.php');
if ($_SERVER['REQUEST_METHOD'] == "POST") 
{
$res = $con->query("UPDATE `trainhistory` SET data='$_POST[SaveStr]' WHERE id = '$_POST[LID]'");
  if (!$res) {
die('Invalid query: ' . mysqli_error($con));
}
echo json_encode("sucess");
}
?>