<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="container" style="margin-top:60px;">
    <div class="row">
        <div class="col-lg-12">
            <h1>Edit Tag in <?php echo strtolower($content_language);?></h1>
            <?php echo form_open();?>
            <div class="form-group">
                <?php

                echo validation_errors();
                ?>
            </div>
            <div class="form-group">
                <?php
                echo form_label('Tag Name','name');
                echo form_error('name');
                echo form_input('name',set_value('name',$translation->name),'class="form-control"');
                ?>
            </div>
            <?php echo form_error('tag_id');?>
            <?php echo form_hidden('tag_id',set_value('tag_id',$translation->tag_id));?>
            <?php echo form_error('language_slug');?>
            <?php echo form_hidden('language_slug',set_value('language_slug',$translation->language_slug));?>
            <?php echo form_error('translation_id');?>
            <?php echo form_hidden('translation_id',set_value('translation_id',$translation->id));?>
            <?php
            $submit_button = 'Edit translation';
            echo form_submit('submit', $submit_button, 'class="btn btn-primary btn-lg btn-block"');?>
            <?php echo anchor('/admin/tags/index/', 'Cancel','class="btn btn-default btn-lg btn-block"');?>
            <?php echo form_close();?>
        </div>
    </div>
</div>
