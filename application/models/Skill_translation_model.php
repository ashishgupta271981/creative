<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Skill_translation_model extends MY_Model
{

    public $table = 'skill_translations';
    public $primary_key = 'id';
    public $before_create = array('created_by');
    public $before_update = array('updated_by');

    public function __construct()
    {
        $this->has_one['skills'] = array('Skill_model','id','skill_id');
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

    public function get_list($language_slug = NULL)
    {

        $this->db->select("name AS text, skill_id as id");

        $query = $this->db->get($this->table);
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
