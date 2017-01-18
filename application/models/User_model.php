<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends MY_Model
{
    private $featured_image;
    public $before_create = array('created_by');
    public $before_update = array('updated_by');
    public $table = 'users';

    public function __construct()
    {
        $this->featured_image = $this->config->item('cms_featured_image');
        //$this->has_many['group'] = array('Content_translation_model','content_id','id');
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

    public function get_user_location($user_id = 0,$language_slug = "en")
    {


        $this->db->select("CT.name AS country, ST.name AS state, CTY.name AS city");

        if($user_id > 0)
        {
            $this->db->where('U.id',$user_id);
        }
        $this->db->join('country_translations AS CT','U.country_id = CT.country_id','LEFT');
        $this->db->join('state_translations AS ST','U.state_id = ST.state_id','LEFT');
        $this->db->join('city_translations AS CTY','U.city_id = CTY.city_id','LEFT');
        //$this->db->order_by('P.id desc');
        $query = $this->db->get($this->table." AS U");
        if($query->num_rows()>0)
        {
            return $query->result();
        }
        else
        {
            return FALSE;
        }
    }

    public function get_user_skills($user_id = 0,$language_slug = "en")
    {

        $this->db->select("ST.name AS text, US.skill_id as id");
        if($user_id > 0)
        {
            $this->db->where('US.user_id',$user_id);
        }
        $this->db->join('skill_translations AS ST','US.skill_id = ST.skill_id','LEFT');
        $query = $this->db->get("users_skills AS US");
        if($query->num_rows()>0)
        {
            return $query->result();
        }
        else
        {
            return FALSE;
        }
    }

    public function get_user_avg_ratings($user_id = 0)
    {

        $this->db->select('avg(rating) AS ratings');

        if($user_id > 0)
        {
            $this->db->where('user_id',$user_id);
        }

        $query = $this->db->get("users_ratings");
        if($query->num_rows()>0)
        {
            $result = $query->result();
            return (int)$result[0]->ratings;
        }
        else
        {
            return 0;
        }
    }

    public function get_user_num_views($user_id = 0)
    {
        $this->db->select('count(*) AS num_views');

        if($user_id > 0)
        {
            $this->db->where('user_id',$user_id);
        }

        $query = $this->db->get("users_views");
        if($query->num_rows()>0)
        {
            $result = $query->result();
            return (int)$result[0]->num_views;
        }
        else
        {
            return 0;
        }
    }


    public function get_user_num_likes($user_id = 0)
    {
        $this->db->select('count(*) AS num_likes');

        if($user_id > 0)
        {
            $this->db->where('user_id',$user_id);
        }

        $query = $this->db->get("users_likes");
        if($query->num_rows()>0)
        {
            $result = $query->result();
            return (int)$result[0]->num_likes;
        }
        else
        {
            return 0;
        }
    }

    public function get_user_num_portfolio_projects($user_id = 0)
    {
        $this->db->select('count(*) AS num_projects');

        if($user_id > 0)
        {
            $this->db->where('user_id',$user_id);
        }

        $query = $this->db->get("portfolio_projects");
        if($query->num_rows()>0)
        {
            $result = $query->result();
            return (int)$result[0]->num_projects;
        }
        else
        {
            return 0;
        }
    }

    public function get_user_num_purchased_leads($user_id = 0)
    {
        $this->db->select('count(*) AS num_leads');

        if($user_id > 0)
        {
            $this->db->where('user_id',$user_id);
        }

        $query = $this->db->get("order_projects");
        if($query->num_rows()>0)
        {
            $result = $query->result();
            return (int)$result[0]->num_leads;
        }
        else
        {
            return 0;
        }
    }

    public function get_user_purchased_leads($user_id = 0)
    {
        $project_configs = $this->config->item('projects_feature');

        $this->db->select("P.id as project_id, concat('".addslashes($project_configs['path'])."', (P.featured_image)) AS featured_image, PT.title");

        if($user_id > 0)
        {
            $this->db->where('OP.user_id',$user_id);
        }
        $this->db->join('projects AS P','OP.project_id = P.id','LEFT');
        $this->db->join('project_translations AS PT','OP.project_id = PT.project_id','LEFT');
        //$this->db->order_by('P.id desc');
        $query = $this->db->get("order_projects AS OP");
        if($query->num_rows()>0)
        {
            return $query->result();
        }
        else
        {
            return FALSE;
        }
    }






}
