<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Project_skill_model extends MY_Model
{
    private $featured_image;
    //public $before_create = array('created_by');
    //public $before_update = array('updated_by');
    public $table = 'projects_skills';

    public function __construct()
    {
        $this->featured_image = $this->config->item('cms_featured_image');
        //$this->has_many['group'] = array('Content_translation_model','content_id','id');
        parent::__construct();
    }

    public function created_by($data)
    {
        //$data['created_by'] = $this->user_id;
        return $data;
    }

    public function updated_by($data)
    {
        //$data['updated_by'] = $this->user_id;
        return $data;
    }
    function get_projects_skills($project_id)
    {
        $this->db->select("ST.name");
        $this->db->join('skill_translations AS ST','ST.skill_id=PS.skill_id');
        $where = "PS.project_id='".$project_id."'";
        $this->db->where($where);
        $query = $this->db->get($this->table." AS PS");
        if($query->num_rows()>0)
        {

            $list_skills = '';
            foreach ($query->result() as $row)
            {
                $list_skills .= $row->name.' | ';
            }
            $list_skills = trim($list_skills," | ");
            return $list_skills;
        }
        else
        {
            return FALSE;
        }

    }
}
