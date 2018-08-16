<?php
session_start();
if($_SESSION['login'] != "yes")
{
  header("Location: login.php"); /* Redirect browser */
  exit();
}
 ?>
