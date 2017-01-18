<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Portfolio_project_model extends MY_Model
{
    private $featured_image;
    public $before_create = array('created_by');
    public $before_update = array('updated_by');
    public $table = 'portfolio_projects';

    public function __construct()
    {
        $this->featured_image = $this->config->item('cms_featured_image');
        $this->has_many['translations'] = array('Content_translation_model','content_id','id');
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


    public function get_user_projects_count($freelancer = 0,$language_slug = NULL)
    {
        $this->db->select("P.id as project_id");
        if(isset($language_slug))
        {
            $this->db->where('PT.language_slug',$language_slug);
        }
        $this->db->join('portfolio_project_translations AS PT','P.id = PT.project_id');
        $where = "P.user_id =".$freelancer;
        $this->db->where($where);
        $this->db->order_by('P.id desc');
        $query = $this->db->get($this->table." AS P");
        return $query->num_rows();
    }


    public function get_user_projects($freelancer = 0,$num_records=10,$start=0,$language_slug = NULL)
    {
        $project_configs = $this->config->item('portfolio_project_featured');


        $this->db->select("P.id as project_id, PT.title".
                        ", concat('".addslashes($project_configs['path'])."', (COALESCE(P.featured_image, 'default_project.jpg'))) AS featured_image");
        if(isset($language_slug))
        {
            $this->db->where('PT.language_slug',$language_slug);
        }
        $this->db->join('portfolio_project_translations AS PT','P.id = PT.project_id');
        $where = "P.user_id =".$freelancer;
        $this->db->where($where);
        $this->db->order_by('P.id desc');
        $this->db->limit($num_records,$start);
        $query = $this->db->get($this->table." AS P");
        if($query->num_rows()>0)
        {
            return $query->result();
        }
        else
        {
            return FALSE;
        }
    }

    public function get_user_project_details($user_id = 0,$project_id = 0,$language_slug = NULL)
    {
        $project_configs = $this->config->item('portfolio_project_featured');


        $this->db->select("P.id as project_id, concat('".addslashes($project_configs['path'])."', (P.featured_image)) AS featured_image".
                          ", DATE_FORMAT(start_date,'%d %b %Y') AS start_date, DATE_FORMAT(end_date,'%d %b %Y') AS end_date, PT.title".
                          ", PT.skills, PT.description, PT.project_url,PT.media_url");
        if(isset($language_slug))
        {
            $this->db->where('PT.language_slug',$language_slug);
        }
        if($user_id > 0)
        {
            $this->db->where('P.user_id',$user_id);
        }
        if($project_id > 0)
        {
            $this->db->where('P.id',$project_id);
        }
        $this->db->join('portfolio_project_translations AS PT','P.id = PT.project_id');
        $this->db->order_by('P.id desc');
        $query = $this->db->get($this->table." AS P");
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
