<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<?php
/*echo $this->lang->line('homepage_welcome');

echo '<br />'.$current_lang['slug'];
echo '<br />hello';
echo '<pre>';
print_r($langs);
echo '</pre>';*/
?>



<section class="container col-sm-12" >
<div class="container-area">
   <div class="col-sm-7 lgn_demo_area">
   <img src="<?php echo site_url('assets/img/logo_banner.png');?>" style="width:100%">
   </div>
<div class="col-sm-5">
    <?php echo form_open('',array('class'=>'form-horizontal'));?>
      <h2 class="form-signin-heading" style="text-align:center">Log In</h2>
      <p style="text-align:center">Dont have an account? <a href="#" style="color:#ff0066">Sign up</a></p>

      <?php echo form_error('identity');?>
      <?php echo form_input('identity','','class="form-control"');?>
      <br/>

      <?php echo form_error('password');?>
      <?php echo form_password('password','','class="form-control"');?>

       <label class="checkbox" style=" margin-left: 20px;font-weight:normal;">

         <?php echo form_checkbox('remember','1',FALSE);?> Remember me
        <a href="#" style="float:right; text-align:right;">Forgot Password?</a>
      </label>
      <?php echo form_submit('submit', 'Login', 'class="btn btn-lg btn-create btn-block"');?>
      <h6>Or</h6>
      <div class="fb-login">
      <a href="#"> <img src="<?php echo site_url('assets/img/fblogin.png');?>"></a> &nbsp; &nbsp; <a href=""><img src="<?php echo site_url('assets/img/lilogin.png');?>">
      </div>
      <?php echo form_hidden('redirect_to',$redirect_to);?>

      <?php echo form_close();?>

</div>

 </div><!--container-area-->
</section>
