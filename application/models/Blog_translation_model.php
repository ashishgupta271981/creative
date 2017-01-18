<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Blog_translation_model extends MY_Model
{

    public $table = 'blog_translations';
    public $primary_key = 'id';
    public $before_create = array('created_by');
    public $before_update = array('updated_by');

    public function __construct()
    {
        $this->has_one['blog'] = array('Blog_model','id','blog_id');
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

    public function get_blogs_for_widgets($num_of_blogs=3,$language_slug = NULL)
    {
        $this->db->select('BT.blog_id, BT.title, BT.content');
      //  $this->db->where('contents.content_type',$content_type);
        if(isset($language_slug))
        {
            $this->db->where('BT.language_slug',$language_slug);
        }
        $this->db->limit($num_of_blogs);
        $this->db->order_by('BT.blog_id','desc');
        $query = $this->db->get($this->table.' AS BT');
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
