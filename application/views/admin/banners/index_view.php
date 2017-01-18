<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="col-lg-12" style="margin-top: 10px;"><h1>Banners Management</h1> </div>
<div class="container" style="margin-top:60px;">
  
    <div class="row">
        <div class="col-lg-12">
            <a href="<?php echo site_url('admin/banners/create/');?>" class="btn btn-primary">Create Banner</a>
            
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12" style="margin-top: 10px;">
            <?php
            if(!empty($users))
            {
                echo '<table class="table table-hover table-bordered table-condensed">';
                echo '<tr><td>S.No.</td><td>Image Type</td></td><td>Upload Image / Image Link </td><td>Landing Url</td><td>Starting date</td><td>Ending date</td><td>Operations</td></tr>';
				$i=1;
                foreach($users as $user)
                {
					//print_r($user);
					
					$image_type = ($user['image_type']==1)?'UPLOAD':'URL';
                    echo '<tr>';
                    echo '<td>'.$i.'</td>';
					echo '<td>'.$image_type.'</td>';
					if($user['upload_image']!=''){echo '<td><img src="'.base_url().'uploads/banners/'.$user['upload_image'].'" height="100" weight="100"></td>';}
					if($user['image_url']!=''){echo '<td><img src="'.$user['image_url'].'" height="100" weight="100" ></td>';}
					echo '<td>'.$user['landing_url'].'</td>';
					echo '<td>'.$user['starting_date'].'</td>';
					echo '<td>'.$user['ending_date'].'</td>';
                    echo '<td>'.anchor('admin/banners/edit/'.$user['id'],'<span class="glyphicon glyphicon-pencil"></span>').' '.anchor('admin/banners/delete/'.$user['id'],'<span class="glyphicon glyphicon-remove"></span>');
										
                    echo '</td>';
                    echo '</tr>';
					$i++;
                }
                echo '</table>';
            }
            ?>
        </div>
    </div>
</div>