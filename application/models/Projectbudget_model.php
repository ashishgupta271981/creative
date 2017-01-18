<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * budget Model
 *
 * @package     CFI
 * @subpackage  admin
 * @category    Master
 * @author      Sandeep Panwar
 * @created_at  Dec 09,2016
 *
 */
class Projectbudget_model extends MY_Model
{
    public $before_create = array('created_by');
    public $before_update = array('updated_by');
    public $table = 'project_budgets';
    /**
	 * Construction function
	 *
	 * @Controller  budget
	 * @method    	__construct
	 * @author      Sandeep Panwar
	 * @created_at  Dec 09,2016
	 * @purpose 	It will check how many translations a record has and join tables.
	 */
    public function __construct()
    {
        $this->has_many['projectbudget_translations'] = array('projectbudget_translation_model','project_work_mode_id','id');
        parent::__construct();
    }
	/* End of public function __construct() */

	
	
    /**
	 * Construction function
	 *
	 * @Controller  budget
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
	 * @Controller  budget
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
    public function get_projectbudget_list($language_slug = NULL)
    {
		
        $this->db->select('pb.id, pb.published, pb.published_at,pbt.budget,pbt.language_slug,pbt.id as translation_id');
        if(isset($language_slug))
        {
            $this->db->where('pbt.language_slug',$language_slug);
        }
        $this->db->join('project_budget_translations pbt','pb.id = pbt.project_budget_id');
		$this->db->order_by('pb.id','asc');
		$table_name =$this->table.' as pb';
        $query = $this->db->get($table_name);
		
        if($query->num_rows()>0)
        {
            $list_content = array();
            foreach ($query->result() as $row)
            {
				
			
                if(!array_key_exists($row->id,$list_content))
                {
                    $list_content[$row->id] = array(
                        'mode' => $row->budget,
                        'published' => $row->published,
                        'published_at' => $row->published_at,
                        'translations' => array(),
                        'title' => '');
                }
                $list_content[$row->id]['translations'][$row->language_slug] = array(
                            'translation_id' => $row->translation_id,
                            'mode' => $row->budget);
                            
                if ($row->language_slug == $_SESSION['default_lang'])
                {
                    $list_content[$row->id]['title'] = $row->budget;
                }
                elseif (strlen($list_content[$row->id]['title']) == 0)
                {
                    $list_content[$row->id]['title'] = $row->budget;
                }
            }
			
			//echo "<pre>";print_r($list_content);exit;
            return $list_content;
        }
        else
        {
            return FALSE;
        }
    }
	/* End of public function get_projectbudget_list($language_slug = NULL) */



    public $rules = array(
        'insert' => array(
            'name' => array('field'=>'name','label'=>'Name','rules'=>'trim|required|callback__unique_value'),
            'published_at' => array('field'=>'published_at','label'=>'Published at','rules'=>'trim|datetime'),
            'language_slug' => array('field'=>'language_slug','label'=>'Language slug','rules'=>'trim|required')
        ),
        'update' => array(
            'name' => array('field'=>'name','label'=>'Name','rules'=>'trim|required'),
            'id' => array('field'=>'id', 'label'=>'ID', 'rules'=>'trim|is_natural_no_zero|required'),
            'published_at' => array('field'=>'published_at','label'=>'Published at','rules'=>'trim|datetime'),
            'language_slug' => array('field'=>'language_slug','label'=>'language_slug','rules'=>'trim|required')
        ),
        'insert_featured' => array(
            'file_name' => array('field'=>'file_name','label'=>'File name','rules'=>'trim'),
            // there where two typos in here 'Contend ID' and 'tirm' 
            'content_id' => array('field'=>'content_id','label'=>'Content ID','rules'=>'trim|is_natural_no_zero|required')
        )
    );
	
}
