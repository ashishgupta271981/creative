<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Coupon_model extends MY_Model
{
    private $featured_image;
    public $before_create = array('created_by');
    public $before_update = array('updated_by');
    public $table = 'coupons';
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

    public function get_coupon($coupon_code)
    {
        $this->db->select('*');
        $this->db->where('code',$coupon_code);


        $query = $this->db->get($this->table);
        if($query->num_rows()>0)
        {
            return $query->result();
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
}
