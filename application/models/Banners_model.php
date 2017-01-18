<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Banners_model extends MY_Model
{
    private $featured_image;
    public $before_create = array('created_by');
    public $before_update = array('updated_by');
    public $table = 'banners';

    public function __construct()
    {
        $this->featured_image = $this->config->item('cms_featured_image');
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

  


 public function get_banners_list()
    {
        
        $this->db->select('b.*');
        $this->db->order_by('b.id','desc');
    
        $table_name =$this->table.' as b';
        $query = $this->db->get($table_name);
        
        if($query->num_rows()>0)
        {
            $list_content = $query->result_array();
            //echo "<pre>";print_r($list_content);exit;
            return $list_content;
        }
        else
        {
            return FALSE;
        }
    }


     public function get_banners_details($banner_id = '0')
    {
        
        $this->db->select('b.*');
        $this->db->order_by('b.id','desc');
        
       if($banner_id>0){
         $where = "b.id ='".$banner_id."'";        
        $this->db->where($where);
       }
        $table_name =$this->table.' as b';
        $query = $this->db->get($table_name);
        
        if($query->num_rows()>0)
        {
            $list_content = $query->result();
            //echo "<pre>";print_r($list_content);exit;
            return $list_content[0];
        }
        else
        {
            return FALSE;
        }
    }


    public function groups($group_id = '0')
    {
        $this->db->select('g.*');
        $this->db->order_by('g.id','asc');
        if($group_id>0){
         $where = "g.id ='".$group_id."'";        
         $this->db->where($where);
        }
        $query = $this->db->get('groups as g');
        if($group_id =='0'){
        $groups = array('0'=>'No groups');
        }else{
           $groups = array(); 
        }

        if($query->num_rows()>0)
        {
            foreach($query->result() as $row)
            {
                $groups[$row->id] = $row->name;
            }
        }
        //echo $this->db->last_query();exit;
        return $groups;
    }





}
