<!DOCTYPE html>
<html lang="zh-Hant-TW">
<?php include('backendheader.php'); ?>

<style>
* {
    box-sizing: border-box;
}

/* Create two equal columns that floats next to each other */
.column {
    float: left;
    width: 45%;
    padding: 15px;
}

/* Clear floats after the columns */
.row:after {
    content: "";
    display: table;
    clear: both;
}
</style>

<body>
<?php include('backendsidebar.php'); ?>
<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
<h1 class="sub-header">訓練模式</h1>

<div class="row">
  <div class="column" style="background-color:#ccff99;">
    <h2>Column 1</h2>
    <p>Some text..</p>
  </div>
  <div class="column" style="background-color:#bbb;">
    <h2>Column 2</h2>
    <p>Some text..</p>
  </div>
</div>

</div>
</body>
</html>	