<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="container" style="margin-top:60px;">
    <div class="row">
        <div class="col-lg-12">
            <h1>Edit <?php echo $content_title;?> in <?php echo strtolower($content_language);?></h1>
            <?php echo form_open();?>
            <div class="form-group">
                <?php

                //echo validation_errors();
				
				//print_r($translation);
                ?>
            </div>
            <div class="form-group">
                <?php
                echo form_label('Name','name');
                echo form_error('name');
                echo form_input('name',set_value('name',$translation->mode),'class="form-control"');
                ?>
            </div>
            <?php echo form_error('id');?>
            <?php echo form_hidden('id',set_value('id',$translation->project_work_mode_id));?>
            <?php echo form_error('language_slug');?>
            <?php echo form_hidden('language_slug',set_value('language_slug',$translation->language_slug));?>
            <?php echo form_error('translation_id');?>
            <?php echo form_hidden('translation_id',set_value('translation_id',$translation->id));?>
            <?php
            $submit_button = 'Edit translation';
            echo form_submit('submit', $submit_button, 'class="btn btn-primary btn-lg btn-block"');?>
            <?php echo anchor('/admin/projectmode/index/', 'Cancel','class="btn btn-default btn-lg btn-block"');?>
            <?php echo form_close();?>
        </div>
    </div>
</div>