<!DOCTYPE html>
<?php
include('connect_to_sql.php');
@$this_type = $_GET['sound_type'];
$data = $con->query("SELECT * FROM enviro WHERE category LIKE '%$this_type%' ORDER BY id");
$category = $con->query("select category from enviro where category != ''");
include('sidebar.php');
?>
<html>
  <!-- Top header -->
  <header class="w3-container w3-xlarge">
    <p class="w3-left">情境訓練模式</p>
    <!--<p class="w3-right">
      <i class="fa fa-shopping-cart w3-margin-right"></i>
      <i class="fa fa-search"></i>
    </p>-->
  </header>
  <body> 
  <div class="w3-container w3-text-grey" id="sounds">
    <form>
      <select onChange="location = 'envirotrain.php?sound_type=' + this.options[this.selectedIndex].value;">
        <option value="#">全部</option>
        <?php
        $classfication = array();
        for($j = 1;$j <= mysqli_num_rows($category);$j++){
          $rs_category = mysqli_fetch_assoc($category);
          $tmp = explode(";",$rs_category['category']);
            $classfication = array_merge($classfication, $tmp);
        }
        $classfication = array_unique($classfication);
        foreach ($classfication as $key => $value){
          echo '<option value="'.$value.'"';
          if($this_type == "$value")
            echo 'selected';
          echo '>'.$value.'</option>';
        }?>
      </select>
    </form>
    <p>一共有<?php echo mysqli_num_rows($data);?>筆資料</p>
  </div>

  <!-- Product grid -->
    <?php
    for($i = 1;$i <= mysqli_num_rows($data);$i++){
      $rs = mysqli_fetch_assoc($data);
      if($i % 4 == 1)echo '<div class="w3-row">';
    ?>
    <div class="w3-col l3 s6">
      <div class="w3-container">
        <div class="w3-display-container">
          <img src="<?php echo $rs['background_src']?>" height="100%" width="100%" />
          <?php
          //if()
            //echo '<span class="w3-tag w3-display-topleft">New</span>'?>
          <div class="w3-display-middle w3-display-hover">
			<input value="Play" type="button" class="w3-button w3-black " onclick="location.href='envirotrain_content.php?id='+<?php echo $rs['id']?>"></input>
          </div>
        </div>
        <p align = 'center'><?php echo $rs['name']?></p>
      </div>
  </div>
  <?php
    if($i % 4 == 0 || $i == mysqli_num_rows($data))echo '</div>';
  }
  ?>
  <!-- End page content -->
  
</body>
</html>
