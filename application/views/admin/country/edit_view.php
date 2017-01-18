<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="container" style="margin-top:60px;">
    <div class="row">
        <div class="col-lg-12">
            <h1>Edit <?php //echo $country->content_type;?> in <?php echo strtolower($content_language);?></h1>
            <?php echo form_open();?>
            <div class="form-group">
                <?php

                echo validation_errors();
                ?>
                <?php
            //    echo form_label('Parent','parent_id');
              //  echo form_error('parent_id');
                //echo form_dropdown('parent_id',$parents,set_value('parent_id',$country->parent_id),'class="form-control"');
                ?>
            </div>
            <div class="form-group">
                <?php
              echo form_label('Name','name');
                echo form_error('name');
                echo form_input('name',set_value('name',$translation->name),'class="form-control"');
                ?>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <?php
                        echo form_label('Short Name','sort_name');
                        echo form_error('sort_name');
                        echo form_input('sort_name',set_value('sort_name',$translation->sort_name),'class="form-control"');
                        ?>
                    </div>
                    <div class="form-group">
                        <?php
                    //    echo form_label('Teaser','teaser');
                      //  echo form_error('teaser');
                        //echo form_textarea('teaser',set_value('teaser',$translation->teaser),'class="form-control"');
                        ?>
                    </div>
                    <div class="form-group">
                        <?php
                    //    echo form_label('Slug','slug');
                      //  echo form_error('slug');
                        //echo form_input('slug',set_value('slug'),'class="form-control"');
                        ?>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <?php
                        //echo form_label('Page title','page_title');
                        //echo form_error('page_title');
                        //echo form_input('page_title',set_value('page_title',$translation->page_title),'class="form-control" placeholder="SEO..."');
                        ?>
                    </div>
                    <div class="form-group">
                        <?php
                      //  echo form_label('Page keywords','page_keywords');
                      //  echo form_error('page_keywords');
                      //  echo form_input('page_keywords',set_value('page_keywords',$translation->page_keywords),'class="form-control" placeholder="SEO..."');
                        ?>
                    </div>
                    <div class="form-group">
                        <?php
                      //  echo form_label('Page description','page_description');
                      //  echo form_error('page_description');
                      //  echo form_textarea('page_description',set_value('page_description',$translation->page_description),'class="form-control" placeholder="SEO..."');
                        ?>
                    </div>

                </div>
            </div>



            <?php echo form_error('country_id');?>
            <?php echo form_hidden('country_id',set_value('country_id',$translation->country_id));?>
            <?php echo form_error('language_slug');?>
            <?php echo form_hidden('language_slug',set_value('language_slug',$translation->language_slug));?>
            <?php echo form_error('translation_id');?>
            <?php echo form_hidden('translation_id',set_value('translation_id',$translation->id));?>
            <?php
            $submit_button = 'Edit translation';
            echo form_submit('submit', $submit_button, 'class="btn btn-primary btn-lg btn-block"');?>
            <?php echo anchor('/admin/country/index/', 'Cancel','class="btn btn-default btn-lg btn-block"');?>
            <?php echo form_close();?>
        </div>
    </div>
</div>
