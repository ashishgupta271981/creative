<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="container" style="margin-top:60px;">
    <div class="row">
        <div class="col-lg-12">
            <h1>Add <?php echo $content_title;?> in <?php echo strtolower($content_language);?></h1>
            <?php echo form_open();?>
            <div class="form-group">
                <?php
                echo form_label('Name','name');
                echo form_error('name');
                echo form_input('name',set_value('name'),'class="form-control"');
                ?>
            </div>
			 <div class="form-group">
               
                <?php
                echo form_label('Budget','project_budget_id');
                echo form_error('project_budget_id');
                echo form_dropdown('project_budget_id',$budgets,set_value('project_budget_id'),'class="form-control"');
                ?>
            </div>
			<div class="form-group">
               
                <?php
                echo form_label('Mode','project_work_mode_id');
                echo form_error('project_work_mode_id');
                echo form_dropdown('project_work_mode_id',$modes,set_value('project_work_mode_id'),'class="form-control"');
                ?>
            </div>
			<div class="row">
                <div class="col-lg-6">
                   
                    <div class="form-group">
                        <?php
                        echo form_label('Description','description');
                        echo form_error('description');
                        echo form_textarea('description',set_value('description'),'class="form-control"');
                        ?>
                    </div>
                   
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <?php
                        echo form_label('Page title','page_title');
                        echo form_error('page_title');
                        echo form_input('page_title',set_value('page_title'),'class="form-control" placeholder="SEO..."');
                        ?>
                    </div>
                    <div class="form-group">
                        <?php
                        echo form_label('Page keywords','page_keywords');
                        echo form_error('page_keywords');
                        echo form_input('page_keywords',set_value('page_keywords'),'class="form-control" placeholder="SEO..."');
                        ?>
                    </div>
                    <div class="form-group">
                        <?php
                        echo form_label('Page description','page_description');
                        echo form_error('page_description');
                        echo form_textarea('page_description',set_value('page_description'),'class="form-control" placeholder="SEO..."');
                        ?>
                    </div>
                </div>
            </div>

			<?php
                echo '<div class="form-group">';
                echo form_error('published_at');
                echo form_hidden('published_at', date('Y-m-d H:i:s'));
                echo '</div>';
			?>
            <?php echo form_error('id');?>
            <?php echo form_hidden('id',set_value('id',$id));?>
            <?php echo form_error('language_slug');?>
            <?php echo form_hidden('language_slug',set_value('language_slug',$language_slug));?>
            <?php
            $submit_button = 'Add '.$content_title;
            if($id!=0) $submit_button = 'Add translation';
            echo form_submit('submit', $submit_button, 'class="btn btn-primary btn-lg btn-block"');?>
            <?php echo anchor('/admin/projectmode/index/'.$content_type, 'Cancel','class="btn btn-default btn-lg btn-block"');?>
            <?php echo form_close();?>
        </div>
    </div>
</div>