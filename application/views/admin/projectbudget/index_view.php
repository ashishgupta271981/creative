<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="container" style="margin-top:60px;">
    <div class="row">
        <div class="col-lg-12">
            <?php
            if(sizeof($langs)>1) {
                ?>
                <div class="btn-group">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"
                            aria-expanded="false">Add <?php echo $content_title;;?> <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                        <?php
                        foreach ($langs as $slug => $language) {
                            echo '<li>' . anchor('admin/projectbudget/create/' . $content_type . '/' . $slug, $language['name']) . '</li>';
                        }
                        ?>
                    </ul>
                </div>
            <?php
            }
            else
            {
                echo anchor('admin/projectbudget/create/'.$content_type.'/'.$current_lang['slug'],'Add '.$content_title,'class="btn btn-primary"');
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
            echo '<th rowspan="2">'.ucfirst($content_title).' name</th>';
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
            if(!empty($projectbudget))
            {
					//print_r($contents);

                foreach($projectbudget as $id => $value)
                {
                    echo '<tr>';
                    echo '<td>'.$id.'</td><td>'.$value['title'].'</td>';
                    foreach($langs as $slug=>$language)
                    {
                        echo '<td>';
                        if(array_key_exists($slug,$value['translations']))
                        {
                            echo anchor('admin/projectbudget/edit/'.$slug.'/'.$id,'<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>');
                            echo ' '.anchor('admin/projectbudget/delete/'.$slug.'/'.$id,'<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>','onclick="return confirm(\'Are you sure you want to delete?\')"');
                            $rakestyle = '';
                           
                        }
                        else
                        {
                            echo anchor('admin/projectbudget/create/'.$value['content_type'].'/'.$slug.'/'.$id,'<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>');
                        }
                        echo '</td>';
                    }
                    echo '<td>';
                    echo anchor('admin/projectbudget/delete/all/'.$id,'<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>','onclick="return confirm(\'Are you sure you want to delete?\')"');
                    $publish = ($value['published']=='1') ? 0 : 1;
                    $style = ($value['published']=='1') ? '' : ' style="color: red;"';
                    $icon = ($value['published'] == '1') ? 'up' : 'down';
                    echo ' '.anchor('admin/projectbudget/publish/'.$id.'/'.$publish,'<span class="glyphicon glyphicon-thumbs-'.$icon.'" aria-hidden="true"'.$style.'></span>');
                    echo '<br />'.$value['published_at'];
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