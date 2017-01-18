<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * projectbudget_translation Model
 *
 * @package     CFI
 * @subpackage  admin
 * @category    Master
 * @author      Sandeep Panwar
 * @created_at  Dec 09,2016
 *
 */
class Projectbudget_translation_model extends MY_Model
{
    public $table = 'project_budget_translations';
    public $primary_key = 'id';
    public $before_create = array('created_by');
    public $before_update = array('updated_by');
    /**
	 * Construction function
	 *
	 * @Model  projectbudget_translation
	 * @method    	__construct
	 * @author      Sandeep Panwar
	 * @created_at  Dec 09,2016
	 * @purpose 	This is used to join budget and projectbudget_translation table.
	 */
    public function __construct()
    {
        $this->has_one['name'] = array('Projectbudget_model','id','project_budget_id');
        parent::__construct();
    }
	/*End of public function __construct() */

	

    /**
	 * Construction function
	 *
	 * @Model  projectbudget_translation
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
	 * @Model  projectbudget_translation
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