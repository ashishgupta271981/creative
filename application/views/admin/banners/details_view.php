<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="container" style="margin-top:60px;">
    <div class="row">
        <div class="col-lg-12">
            <h1>Member Detail View</h1>
            
			  <div class="form-group">
                <?php
				//print_r($translation);
				/*stdClass Object ( [id] => 14 [ip_address] => ::1 [username] => inder12sss [password] => $2y$08$Yw8k5kBPuDNwNGXJK1D.wuLMpaVIuATDfkQrJ5jP1MdK6sCJXhw9O [salt] => [activation_code] => [forgotten_password_code] => [forgotten_password_time] => [remember_code] => [first_name] => ashish [last_name] => gupta [dp_image] => [cover_image] => [company] => TBW [email] => admin@sss.com [phone] => 79879798798 [about] => [address_1] => [address_2] => [city_id] => 0 [state_id] => 0 [country_id] => 0 [exp_year] => 0 [exp_month] => 0 [fb] => [ln] => [created_on] => 1483704535 [last_login] => [active] => 1 [updated_at] => [deleted_at] => [updated_by] => 0 [deleted_by] => 0 [group_id] => 3 ) */
				
                echo "<b>Username</b> : ".$translation->username;
				echo "<br><b>User Email</b> : ".$translation->email;
				if($translation->first_name!='' || $translation->last_name!=''){echo "<br><b>Full Name</b> : ".$translation->first_name.' '.$translation->last_name;}
				if($translation->company!=''){echo "<br><b>Company name</b> : ".$translation->company;}
				if($translation->phone!=''){echo "<br><b>Phone</b> : ".$translation->phone;}
				if($translation->group_id!=''){echo "<br><b>Group Name</b> : ".$group_name[$translation->group_id];}
				
                ?>
            </div>
			
			
         
            
        </div>
    </div>
</div>