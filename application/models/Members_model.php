<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Members_model extends MY_Model
{
    private $featured_image;
    public $before_create = array('created_by');
    public $before_update = array('updated_by');
    public $table = 'users';

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

  


 public function get_members_list($group_id = '0')
    {
        
        $this->db->select('m.*');
        $this->db->order_by('m.id','desc');
        $this->db->join('users_groups AS UG','UG.user_id = m.id');

       if($group_id>0){
         $where = "UG.group_id ='".$group_id."'";        
        $this->db->where($where);
       }
        $table_name =$this->table.' as m';
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


     public function get_members_details($user_id = '0')
    {
        
        $this->db->select('m.*,UG.group_id');
        $this->db->order_by('m.id','desc');
        $this->db->join('users_groups AS UG','UG.user_id = m.id');

       if($user_id>0){
         $where = "m.id ='".$user_id."'";        
        $this->db->where($where);
       }
        $table_name =$this->table.' as m';
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
