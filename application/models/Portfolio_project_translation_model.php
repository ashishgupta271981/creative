<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Portfolio_project_translation_model extends MY_Model
{

    public $table = 'portfolio_project_translations';
    public $primary_key = 'id';
    public $before_create = array('created_by');
    public $before_update = array('updated_by');

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
}
