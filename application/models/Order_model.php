<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Order_model extends MY_Model
{
    private $featured_image;
    public $before_create = array('created_by');
    public $before_update = array('updated_by');
    public $table = 'orders';
    public function __construct()
    {
        $this->featured_image = $this->config->item('cms_featured_image');
        $this->has_many['order_projects'] = array('Order_project_model','order_id','id');
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


    public function get_num_items_in_draft($user_id)
    {
        $this->db->select('O.id');
        $this->db->where('O.status','draft');
        $this->db->where('O.user_id',$user_id);
        $this->db->join("order_projects AS OP", "OP.order_id = O.id", 'LEFT');
        $query = $this->db->get($this->table." AS O");
        return $query->num_rows();
    }

    public function get_current_order($user_id)
    {
        $this->db->select('id as order_id');
        $this->db->where('status','draft');


        $query = $this->db->get($this->table);
        if($query->num_rows()>0)
        {
            $row = $query->result();
            return $row[0]->order_id;
        }
        else
        {
            return 0;
        }
    }

    public function create_order($user_id)
    {
        $order_data  = array(
                                'user_id' => $user_id,
                                'status'  => 'draft',
                        );
        $this->db->set($order_data);
        $this->db->insert($this->table);
        return $this->db->insert_id();
    }
    public function updateOrder($id,$order_data)
    {
        $this->db->where('id', $id);
        $this->db->update($this->table, $order_data);
    }

    public function get_total_transactions($user_id)
    {
        $this->db->select("OP.id");

        $this->db->join('order_projects AS OP','O.id = OP.order_id');

        $where = "O.status LIKE 'completed' AND O.user_id='".$user_id."' ";
        $this->db->where($where);
        $query = $this->db->get($this->table." AS O");
        return $query->num_rows();
    }

    public function get_transactions($user_id,$sort_on=1,$num_records=10,$start=0)
    {

        $profile_configs = $this->config->item('profile_dp');

        $this->db->select("P.id as project_id, PT.title,DATE_FORMAT(P.published_at,'%M %d %Y') as published".
                          ",DATE_FORMAT(O.ordered_on,'%M %d %Y') as order_date,concat(U.first_name,' ', (U.last_name)) AS full_name, OP.lead_price".
                          ", concat('".addslashes($profile_configs['path'])."', (COALESCE(U.dp_image, 'default_dp.jpg'))) AS dp_image");
        $this->db->join('order_projects AS OP','OP.order_id=O.id','LEFT');
        $this->db->join('project_translations AS PT','OP.project_id = PT.project_id','LEFT');
        $this->db->join('projects AS P','P.id = OP.project_id','LEFT');
        $this->db->join('users AS U','U.id = OP.user_id','LEFT');
        $where = "O.status LIKE 'completed' AND O.user_id='".$user_id."' ";
        $this->db->where($where);
        $this->db->limit($num_records,$start);
        if($sort_on == 2)
        {
            $this->db->order_by('OP.lead_price desc');
        }
        else if($sort_on == 3)
        {
            $this->db->order_by('O.ordered_on desc');
        }
        else
        {
            $this->db->order_by('PT.title desc');
        }
        $query = $this->db->get($this->table." AS O");
        if($query->num_rows()>0)
        {
            $list_content = array();
            return $query->result();
        }
        else
        {
            return FALSE;
        }
    }
}
