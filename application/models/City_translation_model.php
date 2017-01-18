<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class City_translation_model extends MY_Model
{

    public $table = 'city_translations';
    public $primary_key = 'id';
    public $before_create = array('created_by');
    public $before_update = array('updated_by');

    public function __construct()
    {
        $this->has_one['city'] = array('City_model','id','city_id');
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

    public function getCityNameById($id='')
    {
        $this->db->select("name");
        $this->db->where("city_id",$id);
        $query = $this->db->get($this->table)->row();
        return $query;
    }
}
