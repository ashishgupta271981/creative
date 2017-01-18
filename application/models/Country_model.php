<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Country_model extends MY_Model
{
    private $featured_image;
    public $before_create = array('created_by');
    public $before_update = array('updated_by');
    public $table = 'countries';
    public function __construct()
    {
        $this->featured_image = $this->config->item('cms_featured_image');
        $this->has_many['country_translations'] = array('Ccountry_translation_model','country_id','id');
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

    public function get_all($language_slug = NULL)
    {
        $this->db->select('CT.country_id AS id,CT.name AS country');

        if(isset($language_slug))
        {
            $this->db->where('ST.language_slug',$language_slug);
        }


        $this->db->join('country_translations AS CT','C.id = CT.country_id','LEFT');
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
	
	 public function get_country_list($language_slug = NULL)
    {
        $this->db->select('CT.country_id AS id,CT.name AS name');

        if(isset($language_slug))
        {
            $this->db->where('ST.language_slug',$language_slug);
        }


        $this->db->join('country_translations AS CT','C.id = CT.country_id','LEFT');
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
