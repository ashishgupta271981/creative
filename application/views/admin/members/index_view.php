<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="container" style="margin-top:60px;">
    <div class="row">
        <div class="col-lg-12">
            <a href="<?php echo site_url('admin/members/create/'.$group_id);?>" class="btn btn-primary">Create user</a>
            
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12" style="margin-top: 10px;">
            <?php
            if(!empty($users))
            {
                echo '<table class="table table-hover table-bordered table-condensed">';
                echo '<tr><td>ID</td><td>Username</td></td><td>Name</td><td>Email</td><td>Last login</td><td>Operations</td></tr>';
				$i=1;
                foreach($users as $user)
                {
					//print_r($user);
					
                    echo '<tr>';
                    echo '<td>'.$i.'</td>';
					echo '<td>'.$user['username'].'</td><td>'.$user['first_name'].' '.$user['last_name'].'</td></td><td>'.$user['email'].'</td><td>'.date('Y-m-d H:i:s', $user['last_login']).'</td><td>';
                    if($current_user->id != $user['id']) echo anchor('admin/members/edit/'.$user['id'],'<span class="glyphicon glyphicon-pencil"></span>').' '.anchor('admin/members/delete/'.$user['id'],'<span class="glyphicon glyphicon-remove"></span>');
					else echo '&nbsp;';
					echo ' '.anchor('admin/members/view/'.$user['id'].'/','<span class="glyphicon glyphicon-zoom-in'.'" aria-hidden="true"></span>');
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