<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class City_model extends MY_Model
{
    private $featured_image;
    public $before_create = array('created_by');
    public $before_update = array('updated_by');
    public $table = 'cities';
    public function __construct()
    {
        $this->featured_image = $this->config->item('cms_featured_image');
        $this->has_many['city_translations'] = array('City_translation_model','city_id','id');
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

    public function get_by_state($state_id=null,$language_slug = NULL)
    {
        $this->db->select('CT.city_id AS id,CT.name AS city, C.state_id AS state_id');

        if(!empty($language_slug))
        {
            $this->db->where('CT.language_slug',$language_slug);
        }
        if(!empty($state_id))
        {
            $this->db->where('C.state_id',$state_id);
        }
        $this->db->join('city_translations AS CT','C.id = CT.city_id','LEFT');
        $query = $this->db->get($this->table . " AS C");
        if($query->num_rows()>0)
        {
            return $query->result();
        }
        else
        {
            return FALSE;
        }
    }
	
	public function get_ajaxcity_list($state_id=null,$language_slug = NULL)
    {
        $this->db->select('CT.city_id AS id,CT.name AS name');

        if(!empty($language_slug))
        {
            $this->db->where('CT.language_slug',$language_slug);
        }
        if(!empty($state_id))
        {
            $this->db->where('C.state_id',$state_id);
        }
        $this->db->join('city_translations AS CT','C.id = CT.city_id','LEFT');
        $query = $this->db->get($this->table . " AS C");
        if($query->num_rows()>0)
        {
            return $query->result_array();
        }
        else
        {
            return FALSE;
        }
    }
}
