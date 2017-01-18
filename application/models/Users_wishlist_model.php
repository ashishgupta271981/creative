<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Users_wishlist_model extends MY_Model
{
    private $featured_image;
    //public $before_create = array('created_by');
    //public $before_update = array('updated_by');
    public $table = 'users_wishlist';

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
}
