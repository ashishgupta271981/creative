<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="container" style="margin-top:60px;">
    <div class="row">
        <div class="col-lg-2 col-sm-2">
            <?php
            if(sizeof($langs)>1) {
                ?>
                <div class="btn-group">
                    <button type="button" class="btn btn-primary dropdown-toggle col-sm-12" data-toggle="dropdown"
                            aria-expanded="false">Add <?php echo $content_title;;?> <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                        <?php
                        foreach ($langs as $slug => $language) {
                            echo '<li>' . anchor('admin/project/create/' . $content_type . '/' . $slug, $language['name']) . '</li>';
                        }
                        ?>
                    </ul>
                </div>
            <?php
            }
            else
            {
                echo anchor('admin/project/create/'.$content_type.'/'.$current_lang['slug'],'Add '.$content_title,'class="btn btn-primary col-sm-12"');
            }
            ?>
			 
        </div>
		<div class="col-lg-8 col-sm-8">
		 <form class="form-horizonta" role="search" action="<?php echo base_url().'admin/project/index';?>" method="get">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="Search Project Name......." name="search_string" id="search_string" value="<?php echo $this->input->get_post('search_string')?>">
            <div class="input-group-btn">
                <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
            </div>
			
        </div>
        </form>
		<div class="col-md-5 col-md-offset-5">				 
                        
                                      
			 </div>
			 
		</div>
		<div class="col-sm-2 col-lg-2">
		<?php if($this->input->get_post('search_string')!=''){?>
			<div class=" btn btn-primary col-sm-12"><a href="<?php echo base_url().'admin/project/index/';?>"><b style="color:#fff">Show All</b></a></div>
			<?php }?>
		</div>
    </div>
    <div class="row">
        <div class="col-lg-12" style="margin-top: 10px;">
            <?php
            echo '<table class="table table-hover table-bordered table-condensed">';
            echo '<thead>';
            echo '<tr>';
            echo '<th rowspan="2">ID</th>';
            echo '<th rowspan="2">'.ucfirst($content_title).' name</th>';
			echo '<th rowspan="2">Featured</th>';
            foreach($langs as $slug => $language)
            {
                echo '<th>'.$slug.'</th>';
            }
            echo '<th>All</th>';
            echo '</tr>';
            echo '<tr>';
            foreach($langs as $slug => $language)
            {
                echo '<th>Operations</th>';
            }
            echo '<th>Operations</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            if(!empty($project))
            {
					//print_r($project);

                foreach($project as $id => $value)
                {
                    echo '<tr>';
                    echo '<td>'.$id.'</td><td>'.$value['title'].'</td>';
					echo '<td>';
                    if(strlen($value['featured_image'])>0)
                    {
                        echo anchor($value['featured_image'],'<span class="glyphicon glyphicon-picture"></span>','target="_blank"');
                        echo ' '.anchor('admin/project/delete_featured/'.$id,'<span class="glyphicon
                            glyphicon-remove" aria-hidden="true"></span>','onclick="return confirm(\'Are you sure you want to delete?\')"');
                    }
                    else
                    {
                        echo anchor('admin/project/featured/'.$id,'<span class="glyphicon glyphicon-plus"></span>');
                    }
                    echo '</td>';
                    foreach($langs as $slug=>$language)
                    {
                        echo '<td>';
                        if(array_key_exists($slug,$value['translations']))
                        {
                            echo anchor('admin/project/edit/'.$slug.'/'.$id,'<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>');
                            echo ' '.anchor('admin/project/delete/'.$slug.'/'.$id,'<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>','onclick="return confirm(\'Are you sure you want to delete?\')"');
                            $rakestyle = '';
                           
                        }
                        else
                        {
                            echo anchor('admin/project/create/'.$value['content_type'].'/'.$slug.'/'.$id,'<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>');
                        }
                        echo '</td>';
                    }
                    echo '<td>';
					echo anchor('admin/project/view/all/'.$id,'<span class="glyphicon glyphicon-zoom-in" aria-hidden="true"></span>','');
                    echo anchor('admin/project/delete/all/'.$id,'<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>','onclick="return confirm(\'Are you sure you want to delete?\')"');
                    $publish = ($value['published']=='1') ? 0 : 1;
                    $style = ($value['published']=='1') ? '' : ' style="color: red;"';
                    $icon = ($value['published'] == '1') ? 'up' : 'down';
                    echo ' '.anchor('admin/project/publish/'.$id.'/'.$publish,'<span class="glyphicon glyphicon-thumbs-'.$icon.'" aria-hidden="true"'.$style.'></span>');
                    echo '<br />'.$value['published_at'];
                    echo '</td>';
                    echo '</tr>';
                }
            }else{
				
				echo '<tr><td colspan="5"><center><b>No Record here......</b></center></td></tr>';
			}
			
            echo '</tbody>';
            echo '</table>';
			if (strlen($pagination)) {
			echo '<div class="col-md-5 col-md-offset-5">'.$pagination.'</div>';
			}
            ?>
			
        </div>
    </div>
</div>