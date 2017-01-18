<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="container" style="margin-top:60px;">
    <div class="row">
        <div class="col-lg-12">
            <h1><?php  echo ucfirst($translation['title']);?> Details View</h1>
            
			  <div class="form-group">
                <?php
                echo "<b>Project Title</b> : ".$translation['title'];
				echo "<br><b>Project Budget</b> : ".$translation['budget'];
				echo "<br><b>Project Mode</b> : ".$translation['mode'];
				
				echo "<br><b>Project Description</b> : ".$translation['description'];
				echo '<br><b>Project Image</b> : <img src="'.$translation['featured_image'].'" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">';
                ?>
            </div>

          
            
        </div>
    </div>
</div>