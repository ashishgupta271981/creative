<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class State_model extends MY_Model
{
    private $featured_image;
    public $before_create = array('created_by');
    public $before_update = array('updated_by');
    public $table = 'states';
    public function __construct()
    {
        $this->featured_image = $this->config->item('cms_featured_image');
        $this->has_many['state_translations'] = array('State_translation_model','state_id','id');
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

    public function get_by_country($country_id=null,$language_slug = NULL)
    {
        $this->db->select('ST.state_id AS id,ST.name AS state, S.country_id AS country_id');

        if(isset($language_slug))
        {
            $this->db->where('ST.language_slug',$language_slug);
        }
        $this->db->where('S.country_id',$country_id);

        $this->db->join('state_translations AS ST','S.id = ST.state_id','LEFT');
        $query = $this->db->get($this->table . " AS S");
        if($query->num_rows()>0)
        {
            return $query->result();
        }
        else
        {
            return FALSE;
        }
    }
	
	  public function get_ajaxstate_list($country_id='0',$language_slug = NULL)
    {
        $this->db->select('ST.state_id AS id,ST.name AS name');

        if(isset($language_slug))
        {
            $this->db->where('ST.language_slug',$language_slug);
        }
		if($country_id > '0'){
			
			$this->db->where('S.country_id',$country_id);
		}
        

        $this->db->join('state_translations AS ST','S.id = ST.state_id');
        $query = $this->db->get($this->table . " AS S");
		
		//echo $this->db->last_query();exit;
		
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
