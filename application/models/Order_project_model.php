<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Order_project_model extends MY_Model
{

    public $table = 'order_projects';
    public $primary_key = 'id';
    public $before_create = array('created_by');
    public $before_update = array('updated_by');

    public function __construct()
    {
        $this->has_one['order'] = array('Order_model','id','order_id');
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

    public function get_project_in_current_order($order_id,$user_id,$project_id)
    {
        $this->db->select('id');
        $this->db->where('order_id',$order_id);
        $this->db->where('user_id',$user_id);
        $this->db->where('project_id',$project_id);
        $query = $this->db->get($this->table);
        if($query->num_rows()>0)
        {
            $row = $query->result();
            return $row[0]->id;
        }
        else
        {
            return 0;
        }
    }

    public function add_project($order_id,$user_id,$project_id,$qty=1)
    {
        $order_data  = array(
                                'user_id' => $user_id,
                                'order_id'  => $order_id,
                                'project_id'  => $project_id,
                                'quantity'  => $qty
                        );
        $this->db->set($order_data);
        $this->db->insert($this->table);
        return $this->db->insert_id();
    }

    public function get_shop_items($order_id)
    {
        $project_configs = $this->config->item('projects_feature');


        $this->db->select("P.id as project_id, PT.title, OP.lead_price, DATE_FORMAT(P.expire_at,'%M %d %Y') as expiry, PB.budget,PW.mode".
                          ", concat('".addslashes($project_configs['path'])."', (COALESCE(P.featured_image, 'default_project.jpg'))) AS featured_image");

        $this->db->join("orders AS O","OP.order_id = O.id AND O.status='draft'","RIGHT");
        $this->db->join('project_translations AS PT','OP.project_id = PT.project_id','LEFT');
        $this->db->join('projects AS P','OP.project_id = P.id','LEFT');
        $this->db->join('project_budget_translations AS PB','P.project_budget_id = PB.project_budget_id','LEFT');
        $this->db->join('project_work_mode_translations AS PW','P.project_work_mode_id = PW.project_work_mode_id','LEFT');
        $where = "OP.order_id ='".$order_id."'";
        $this->db->where($where);
        $query = $this->db->get($this->table." AS OP");
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
