<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="container" style="margin-top:60px;">
    <div class="row">
        <div class="col-lg-12">
            <?php
            if(sizeof($langs)>1) {
                ?>
                <div class="btn-group">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"
                            aria-expanded="false">Add <?php echo $content_type;?> <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                        <?php
                        foreach ($langs as $slug => $language) {
                            echo '<li>' . anchor('admin/skills/create/' . $content_type . '/' . $slug, $language['name']) . '</li>';
                        }
                        ?>
                    </ul>
                </div>
            <?php
            }
            else
            {
                echo anchor('admin/skills/create/'.$content_type.'/'.$current_lang['slug'],'Add '.$content_type,'class="btn btn-primary"');
            }
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12" style="margin-top: 10px;">
            <?php
            echo '<table class="table table-hover table-bordered table-condensed">';
            echo '<thead>';
            echo '<tr>';
            echo '<th rowspan="2">ID</th>';
            echo '<th rowspan="2">'.ucfirst($content_type).' name</th>';
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
            if(!empty($skills))
            {
					//print_r($contents);

                foreach($skills as $skill_id => $skills)
                {
                    echo '<tr>';
                    echo '<td>'.$skill_id.'</td><td>'.$skills['title'].'</td>';
                    foreach($langs as $slug=>$language)
                    {
                        echo '<td>';
                        if(array_key_exists($slug,$skills['translations']))
                        {
                            echo anchor('admin/skills/edit/'.$slug.'/'.$skill_id,'<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>');
                            echo ' '.anchor('admin/skills/delete/'.$slug.'/'.$skill_id,'<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>','onclick="return confirm(\'Are you sure you want to delete?\')"');
                            $rakestyle = '';
                            /*echo '<br />'.$page['translations'][$slug]['created_at'];
                            echo '<br />'.$page['translations'][$slug]['last_update'];*/
                        }
                        else
                        {
                            echo anchor('admin/skills/create/'.$skills['content_type'].'/'.$slug.'/'.$skill_id,'<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>');
                        }
                        echo '</td>';
                    }
                    echo '<td>';
                    echo anchor('admin/skills/delete/all/'.$skill_id,'<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>','onclick="return confirm(\'Are you sure you want to delete?\')"');
                    $publish = ($skills['published']=='1') ? 0 : 1;
                    $style = ($skills['published']=='1') ? '' : ' style="color: red;"';
                    $icon = ($skills['published'] == '1') ? 'up' : 'down';
                    echo ' '.anchor('admin/skills/publish/'.$skill_id.'/'.$publish,'<span class="glyphicon glyphicon-thumbs-'.$icon.'" aria-hidden="true"'.$style.'></span>');
                    echo '<br />'.$skills['published_at'];
                    echo '</td>';
                    echo '</tr>';
                }
            }
            echo '</tbody>';
            echo '</table>';
            ?>
        </div>
    </div>
</div>