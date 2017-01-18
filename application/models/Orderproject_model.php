<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Mode Model
 *
 * @package     CFI
 * @subpackage  admin
 * @category    Master
 * @author      Sandeep Panwar
 * @created_at  Dec 09,2016
 *
 */
class Orderproject_model extends MY_Model
{
    public $before_create = array('created_by');
    public $before_update = array('updated_by');
    public $table = 'orders';
    /**
	 * Construction function
	 *
	 * @Controller  mode
	 * @method    	__construct
	 * @author      Sandeep Panwar
	 * @created_at  Dec 09,2016
	 * @purpose 	It will check how many translations a record has and join tables.
	 */
    public function __construct()
    {
        parent::__construct();
    }
	/* End of public function __construct() */

	
	
    /**
	 * Construction function
	 *
	 * @Controller  mode
	 * @method    	created_by
	 * @author      Sandeep Panwar
	 * @created_at  Dec 09,2016
	 * @params      
	 * 				$data(type:array)
	 * @purpose 	It will enter into database who created a particular record.
	 */
    public function created_by($data)
    {
        $data['created_by'] = $this->user_id;
        return $data;
    }
	/* End of public function created_by($data) */

    
	
	/**
	 * Construction function
	 *
	 * @Controller  mode
	 * @method    	created_by
	 * @author      Sandeep Panwar
	 * @created_at  Dec 09,2016
	 * @params      
	 * 				$data(type:array)
	 * @purpose 	It will enter into database who created a particular record.
	 */
    public function updated_by($data)
    {
        $data['updated_by'] = $this->user_id;
        return $data;
    }
	/* End of public function updated_by($data) */



	/**
	 * Construction function
	 *
	 * @Controller  mode
	 * @method    	get_projectmode_list
	 * @author      Sandeep Panwar
	 * @created_at  Dec 09,2016
	 * @params      
	 * 				$language_slug(type:string, default:null)
	 * @purpose 	It will fetch all record of mode table.
	 */
    public function get_orderproject_list()
    {
		
        $this->db->select('o.*');
        $this->db->order_by('o.id','desc');
		$table_name =$this->table.' as o';
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
	/* End of public function get_projectmode_list($language_slug = NULL) */


/**
     * Construction function
     *
     * @Controller  mode
     * @method      get_orderproject_details
     * @author      Sandeep Panwar
     * @created_at  Dec 09,2016
     * @params      
     *              $language_slug(type:string, default:null)
     * @purpose     It will fetch all record of mode table.
     */
    public function get_orderproject_details($id)
    {
        
        $this->db->select('o.*');
        $this->db->order_by('o.id','desc');
        $table_name =$this->table.' as o';
        $where = "o.id ='".$id."'";        
        $this->db->where($where);

        $query = $this->db->get($table_name);
        
        if($query->num_rows()>0)
        {
            $list_content = $query->result_array();
           // echo "<pre>";print_r($list_content);exit;
            return $list_content[0];
        }
        else
        {
            return FALSE;
        }
    }
    /* End of public function get_projectmode_list($language_slug = NULL) */



/**
     * Construction function
     *
     * @Controller  mode
     * @method      get_orderproject_details
     * @author      Sandeep Panwar
     * @created_at  Dec 09,2016
     * @params      
     *              $language_slug(type:string, default:null)
     * @purpose     It will fetch all record of mode table.
     */
    public function get_orderproject_listdetails($id)
    {
        
        $this->db->select('op.*,PT.title');
        $this->db->order_by('op.id','desc');

        $this->db->join('project_translations AS PT','op.project_id = PT.project_id');

        $where = "op.order_id ='".$id."'";        
        $this->db->where($where);

        $query = $this->db->get('order_projects as op');
        
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
    /* End of public function get_orderproject_listdetails($language_slug = NULL) */
	
}
