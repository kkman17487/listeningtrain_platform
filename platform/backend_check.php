<?php
session_start();
if(!isset($_SESSION['login']))
  $_SESSION['login'] = "";
if($_SESSION['login'] != "yes")
{
  header("Location: login.php"); /* Redirect browser */
  exit();
}
 ?>
