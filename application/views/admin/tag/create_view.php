<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="container" style="margin-top:60px;">
    <div class="row">
        <div class="col-lg-12">
            <h1>Add <?php //echo $content_type;?> in <?php echo strtolower($content_language);?></h1>
            <?php echo form_open();?>
            <div class="form-group">
                <?php
                echo form_label('Name','name');
                echo form_error('name');
                echo form_input('name',set_value('name'),'class="form-control"');
                ?>
            </div>
			<?php
                echo '<div class="form-group">';
                echo form_error('published_at');
                echo form_hidden('published_at', date('Y-m-d H:i:s'));
                echo '</div>';
			?>
            <?php echo form_error('tag_id');?>
            <?php echo form_hidden('tag_id',set_value('tag_id',$tag_id));?>
            <?php echo form_error('language_slug');?>
            <?php echo form_hidden('language_slug',set_value('language_slug',$language_slug));?>
            <?php
            $submit_button = 'Add ';
            if($tag_id!=0) $submit_button = 'Add translation';
            echo form_submit('submit', $submit_button, 'class="btn btn-primary btn-lg btn-block"');?>
            <?php echo anchor('/admin/tag/index/', 'Cancel','class="btn btn-default btn-lg btn-block"');?>
            <?php echo form_close();?>
        </div>
    </div>
</div>
