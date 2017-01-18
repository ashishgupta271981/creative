<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="container" style="margin-top:60px;">
    <div class="row">
        <div class="col-lg-12">
            <h1>Order Details View</h1>
            
			  <div class="form-group">
                <?php
				//print_r($translation);
				
                echo "<b>Order Id</b> : ".$translation['transaction_id'];
				echo "<br><b>User Name</b> : Admin ".$translation['user_id'];
				
				echo "<br><b>Order Price</b> : ".$translation['total'];
				if($translation['tax']>0){echo "<br><b>Tax</b> : ".$translation['tax'];}
				
				if($translation['gift_card_discount']>0){echo "<br><b>Gift card discount</b> : ".$translation['gift_card_discount'];}
				if($translation['coupon_discount']>0){echo "<br><b>coupon_discount</b> : ".$translation['coupon_discount'];}
				echo "<br><b>Order Total</b> : ".$translation['subtotal'];
				
				if($translation['notes']!=''){echo "<br><b>Notes</b> : ".$translation['notes'];}
				
				echo "<br><b>Status</b> : ".$translation['status'];
				
				echo "<br><b>Ordered Date</b> : ".$translation['ordered_on'];
                ?>
            </div>
			
			
			<div class="container" style="margin-top:60px;">
    
    <div class="row">
        <div class="col-lg-12" style="margin-top: 10px;">
            <?php
            echo '<table class="table table-hover table-bordered table-condensed">';
            echo '<thead>';
            echo '<tr>';
            echo '<th rowspan="2">ID</th>';
            echo '<th rowspan="2">Project name </th>';
            echo '<th rowspan="2">Project Own</th>';
			 echo '<th rowspan="2">Quantity</th>';
            echo '<th rowspan="2">Price</th>';
            echo '<th>All</th>';
            echo '</tr>';
            echo '<tr>';
            echo '<th>Operations</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            if(!empty($project_list))
            {
					//print_r($project_list);
                $i =1;
                foreach($project_list as $id => $value)
                {
                    $style='';
                    echo '<tr>';
                    echo '<td>'.$i.'</td>';
                    echo '<td>'.$value['title'].'</td>';
					echo '<td>admin '.$value['user_id'].'</td>';
                    echo '<td>'.$value['quantity'].'</td>';
                    echo '<td>'.$value['lead_price'].'</td>';
                    echo '<td>';
                  
                    echo ' '.anchor('admin/project/view/all/'.$value['project_id'],'<span class="glyphicon glyphicon-zoom-in'.'" aria-hidden="true"'.$style.'></span>');
                    echo '</td>';
                    echo '</tr>';
                    $i++;
                }
            }
            echo '</tbody>';
            echo '</table>';
            ?>
        </div>
    </div>
</div>
          
            
        </div>
    </div>
</div>