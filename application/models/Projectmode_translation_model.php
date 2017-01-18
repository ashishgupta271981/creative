<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * projectmode_translation Model
 *
 * @package     CFI
 * @subpackage  admin
 * @category    Master
 * @author      Sandeep Panwar
 * @created_at  Dec 09,2016
 *
 */
class Projectmode_translation_model extends MY_Model
{
    public $table = 'project_work_mode_translations';
    public $primary_key = 'id';
    public $before_create = array('created_by');
    public $before_update = array('updated_by');
    /**
	 * Construction function
	 *
	 * @Model  projectmode_translation
	 * @method    	__construct
	 * @author      Sandeep Panwar
	 * @created_at  Dec 09,2016
	 * @purpose 	This is used to join skill and projectmode_translation table.
	 */
    public function __construct()
    {
        $this->has_one['name'] = array('skill_model','id','skill_id');
        parent::__construct();
    }
	/*End of public function __construct() */

	

    /**
	 * Construction function
	 *
	 * @Model  projectmode_translation
	 * @method    	created_by
	 * @author      Sandeep Panwar
	 * @params
	 *				$data(type:array)
	 * @created_at  Dec 09,2016
	 * @purpose 	It will insert entry who created the particular record.
	 */
    public function created_by($data)
    {
        $data['created_by'] = $this->user_id;
        return $data;
    }
	/*End of public function created_by($data)  */
	
	
	
    /**
	 * Construction function
	 *
	 * @Model  projectmode_translation
	 * @method    	updated_by
	 * @author      Sandeep Panwar
	 * @created_at  Dec 09,2016
	 * @params
	 *				$data(type:array)
	 * @purpose 	It will insert entry who update the particular record.
	 */
    public function updated_by($data)
    {
        $data['updated_by'] = $this->user_id;
        return $data;
    }
	/*End of public function updated_by($data)  */
}