<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<?php
/*echo $this->lang->line('homepage_welcome');

echo '<br />'.$current_lang['slug'];
echo '<br />hello';
echo '<pre>';
print_r($langs);
echo '</pre>';*/
?>

<script>
    function setUserType(uType)
    {
        var freelancer = document.getElementById('freelancer');
        var projectowner = document.getElementById('projectowner');
        var user_type = document.getElementById('user_type');

        projectowner.className ="";
        freelancer.className ="";
        if(uType == 2 )
        {
            projectowner.className ="freelance_btn";
            freelancer.className ="po_btn";
            user_type.value = uType;
        }
        else if(uType == 3 )
        {
            projectowner.className ="po_btn";
            freelancer.className ="freelance_btn";
            user_type.value = uType;
        }
    }

</script>

<section class="container col-sm-12" >
<div class="container-area">
   <div class="col-sm-7 lgn_demo_area">
   <img src="<?php echo site_url('assets/img/logo_banner.png');?>" style="width:100%">
   </div>
<div class="col-sm-5">
    <?php echo form_open('',array('class'=>'form-register'));?>
      <h2 class="form-signin-heading" style="text-align:center;text-transform:none; font-weight:500">Create your CFI account</h2>
      <div class="catg_btn">
         <?php
            $user_type =set_value('user_type');
            echo form_error('user_type');
            $atr_user_type = array(
                              'type' => 'hidden',
                              'name' => 'user_type',
                              'id' => 'user_type',
                              'value' => $user_type
                             );
             echo form_input($atr_user_type);
             $calss_po = "po_btn";
             $calss_freelancer = "po_btn";
             if($user_type == 2)
             {
                $calss_po = "freelance_btn";

             }
             if($user_type == 3)
             {
                 $calss_freelancer = "freelance_btn";
             }
             $atr_user_type_freelancer = array(
                                 'name' => 'freelancer',
                                 'id' => 'freelancer',
                                 'content' => 'Freelancer',
                                 'class' => $calss_freelancer,
                                 'onClick'=> 'setUserType(3)'
                                 );

             echo form_button($atr_user_type_freelancer);

            $atr_user_type_po = array(
                                'name' => 'projectowner',
                                'id' => 'projectowner',
                                'content' => 'Project Owner',
                                'class' => $calss_po,
                                'onClick'=> 'setUserType(2)'
                                );

            echo form_button($atr_user_type_po);
       ?>


      </div><!--catg_btn-->
      <br>
      <?php
            echo form_error('identity');
            $atr_identity = array(
                                'name' => 'identity',
                                'class' => 'form-control',
                                'placeholder' => 'Choose User Name for you',
                                'value' => set_value('identity')
                                );
            echo form_input($atr_identity);
      ?>
      <br/>
      <?php
            echo form_error('email');
            $atr_email = array(
                                'name' => 'email',
                                'class' => 'form-control',
                                'placeholder' => 'Email Address',
                                'value' => set_value('email')
                                );
            echo form_input($atr_email);
      ?>
      <br/>

      <?php
            echo form_error('password');
            $atr_password = array(
                                'name' => 'password',
                                'class' => 'form-control',
                                'placeholder' => 'Choose Password',
                                'value' => set_value('password')
                                );
            echo form_password($atr_password);
      ?>
      <br/>
      <?php
            echo form_error('confirm_password');
            $atr_confirm_password = array(
                                'name' => 'confirm_password',
                                'class' => 'form-control',
                                'placeholder' => 'Confirm Password',
                                'value' => set_value('confirm_password')
                                );
            echo form_password($atr_confirm_password);
      ?>

       <label class="checkbox" style=" margin-left: 20px;font-weight:normal;">

         <?php
            echo form_error('tnc');
            echo form_checkbox('tnc','1',FALSE);?> I accept the Terms &amp; condition and Privacy policy.
      </label>
      <?php echo form_submit('submit', 'Create Account', 'class="btn btn-lg btn-create btn-block"');?>
      <h6>Or</h6>
      <div class="fb-login">
      <a href="#"> <img src="<?php echo site_url('assets/img/fblogin.png');?>"></a> &nbsp; &nbsp; <a href=""><img src="<?php echo site_url('assets/img/lilogin.png');?>">
      </div>


      <?php echo form_close();?>

</div>

 </div><!--container-area-->
</section>
