<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Portfolio_project_image_model extends MY_Model
{

    public $table = 'portfolio_project_images';
    public $primary_key = 'id';
    //public $before_create = array('created_by');
    //public $before_update = array('updated_by');

    public function __construct()
    {
        $this->has_one['portfolio_project'] = array('Portfolio_project_model','id','project_id');
        parent::__construct();
    }

    public function created_by($data)
    {
        $data['created_by'] = $this->user_id;
        return $data;
    }

    public function updated_by($data)
    {
        $data['updated_by'] = $this->user_id;
        return $data;
    }

    public function get_by_project($project_id=0)
    {

        $project_configs = $this->config->item('portfolio_project_featured');


        $this->db->select("PI.id as image_id, concat('".addslashes($project_configs['path'])."', (PI.file_name)) AS image_name");

        if($project_id > 0)
        {
            $this->db->where('PI.project_id',$project_id);
        }

        $this->db->order_by('PI.id desc');
        $query = $this->db->get($this->table." AS PI");
        if($query->num_rows()>0)
        {
            $list_content = array();
            return $query->result();
        }
        else
        {
            return FALSE;
        }
    }
}
