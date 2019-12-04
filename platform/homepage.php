<!DOCTYPE html>
<html>
<?php include('sidebar.php');
session_start();
if(isset($_POST['name']))
  @$_SESSION['name'] = $_POST['name']
?>

  <!-- Top header -->
  <header class="w3-container w3-xlarge">
    <p class="w3-left">環境音訓練及測試平台</p>
  </header>
  <!-- Image header -->
  <div class="w3-display-container w3-container">
    <img src="../picture/test1.jpg" alt="首頁" style="width:100%">
    <div class="w3-display-topleft w3-text-white" style="padding:24px 48px">
      <h1 class="w3-jumbo w3-hide-small">HOME PAGE</h1>
      <h1 class="w3-hide-small">Welcome<?php if(@$_SESSION['name']!=NULL)echo " ".$_SESSION['name']?></h1>
      <form action="" method="post">
        使用者名稱: <input type="text" maxlength="10" size="10" name="name"><br>
        <input type="submit" value="提交">
      </form>
    </div>
  </div>
  <!-- End page content -->
</div>

</html>
