<?php
if(isset($_GET['lang'])){
    $lang = trim($_GET['lang']);
}
if(empty($lang))
{
        $lang = 'en';
}
$string = file_get_contents("../assets/languages/{$lang}/extras_lang.json");
$obj_lang = json_decode($string);
 ?>

<h2><?php echo $obj_lang->session_timeout;?></h2>
<hr>
<!--profile-img-->
  <div class="col-md-12" >

          <div class="form-group  form-group-field">
            <label class="col-lg-12 control-label control-label_cust">
                <h3><?php echo $obj_lang->txt_logout_msg;?></h3>
                <?php echo $obj_lang->txt_click;?>
                <a href="../user/"><?php echo $obj_lang->txt_here;?></a>
                <?php echo $obj_lang->txt_to_login;?>

            </label>

          </div>
</div>
<div class="clearfix" style="margin-bottom:20px "></div>
