<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="container" style="margin-top:60px;">
    <div class="row">
        <div class="col-lg-4 col-lg-offset-4">
            <h1>Create Banner</h1>
           <?php echo form_open_multipart();?>
            <div class="form-group">
                <?php
                echo form_label('Banner Type','image_type');
                echo form_error('image_type');?>
				<br>
                <label for="chkYes">
					<input type="radio" id="chkYes" name="image_type" checked value="1" />
					Upload Image
				</label>
				<label for="chkNo">
					<input type="radio" id="chkNo" name="image_type" value="2"/>
					Image URL
				</label>
              
            </div>
                    
			 <div class="form-group" id="dvupload">
                <?php
                echo form_label('Upload file','upload_image');
                echo form_error('upload_image');
                //echo $upload_errors;
                echo form_upload('upload_image',set_value('upload_image'),'class="form-control"');
                ?>
            </div>
            <div class="form-group" id="dvurl" style="display: none">
                <?php
                echo form_label('Image Link','image_url');
                echo form_error('image_url');
                echo form_input('image_url',set_value('image_url'),'class="form-control"');
                ?>
            </div>
            <div class="form-group">
                <?php
                echo form_label('Landing Url','landing_url');
                echo form_error('landing_url');
                echo form_input('landing_url',set_value('landing_url'),'class="form-control"');
                ?>
            </div>
           
            
			 <div class="form-group">
                    <?php
                    echo form_label('Starting Date', 'starting_date');
                    echo form_error('starting_date');
                    ?>
                    <div class="input-group date datetimepicker">
                        <?php
                        echo form_input('starting_date', set_value('starting_date', (isset($content->starting_date) ? $content->starting_date : date('Y-m-d H:i:s'))), 'class="form-control"');
                        ?>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                    </div>
                </div>
				
				 <div class="form-group">
                    <?php
                    echo form_label('Ending Date ', 'ending_date');
                    echo form_error('ending_date');
                    ?>
                    <div class="input-group date datetimepicker">
                        <?php
                        echo form_input('ending_date', set_value('ending_date', (isset($content->ending_date) ? $content->ending_date : date('Y-m-d H:i:s'))), 'class="form-control"');
                        ?>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                    </div>
                </div>
		   
            <?php echo form_submit('submit', 'Create Banner', 'class="btn btn-primary btn-lg btn-block"');?>
            <?php echo anchor('/admin/banners', 'Cancel','class="btn btn-default btn-lg btn-block"');?>
            <?php echo form_close();?>
        </div>
    </div>
</div>
 <script type="text/javascript">
    $(function () {
        $("input[name='image_type']").click(function () {
            if ($("#chkYes").is(":checked")) {
                $("#dvupload").show();
				 $("#dvurl").hide();
            } else {
                $("#dvupload").hide();
				 $("#dvurl").show();
            }
        });
    });
</script>