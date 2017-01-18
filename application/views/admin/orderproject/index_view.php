<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="container" style="margin-top:60px;">
    
    <div class="row">
         <div class="col-lg-12" style="margin-top: 10px;"><h1>All Order</h1> </div>
        <div class="col-lg-12" style="margin-top: 10px;">
            <?php
            echo '<table class="table table-hover table-bordered table-condensed">';
            echo '<thead>';
            echo '<tr>';
            echo '<th rowspan="2">ID</th>';
            echo '<th rowspan="2">Order Id </th>';
            echo '<th rowspan="2">User Name</th>';
            echo '<th rowspan="2">Order Price</th>';
             echo '<th rowspan="2">Status</th>';
            echo '<th>All</th>';
            echo '</tr>';
            echo '<tr>';
            echo '<th>Operations</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            if(!empty($list_orders))
            {
					//print_r($list_orders);
                $i =1;
                foreach($list_orders as $id => $value)
                {
                    $style='';
                    echo '<tr>';
                    echo '<td>'.$i.'</td><td>'.$value['transaction_id'].'</td>';
                    echo '<td>Admin '.$value['user_id'].'</td>';
                    echo '<td>'.$value['subtotal'].'</td>';
                    echo '<td>'.$value['status'].'</td>';
                    echo '<td>';
                   // echo anchor('admin/projectmode/delete/all/'.$value['id'],'<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>','onclick="return confirm(\'Are you sure you want to delete?\')"');
                    echo ' '.anchor('admin/orderproject/view/'.$value['id'].'/','<span class="glyphicon glyphicon-zoom-in'.'" aria-hidden="true"'.$style.'></span>');
                    echo '<br />'.$value['ordered_on'];
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

<!--

<script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.6.1/angular.js"></script>
<script>
var app = angular.module('app',[]);
</script> -->