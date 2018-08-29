<?php
if(!isset($_COOKIE['login']))
  setcookie("login","",time()+3600);
if($_COOKIE['login'] != "yes")
{
  header("Location: login.php"); /* Redirect browser */
  exit();
}
 ?>
