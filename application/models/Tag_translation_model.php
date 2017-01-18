<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * tag_translation Model
 *
 * @package     CFI
 * @subpackage  admin
 * @category    Master
 * @author      Amitesh Jain
 * @created_at  Dec 19,2016
 *
 */
class Tag_translation_model extends MY_Model
{
    public $table = 'tag_translations';
    public $primary_key = 'id';
    public $before_create = array('created_by');
    public $before_update = array('updated_by');
    /**
	 * Construction function
	 *
	 * @Model  tag_translation
	 * @method    	__construct
	 * @author      Amitesh Jain
	 * @created_at  Dec 20,2016
	 * @purpose 	This is used to join tag and tag_translation table.
	 */
    public function __construct()
    {
        $this->has_one['name'] = array('tag_model','id','tag_id');
        parent::__construct();
    }
	/*End of public function __construct() */



    /**
	 * Construction function
	 *
	 * @Model  tag_translation
	 * @method    	created_by
	 * @author      Amitesh Jain
	 * @params
	 *				$data(type:array)
	 * @created_at  Dec 20,2016
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
	 * @Model  tag_translation
	 * @method    	updated_by
	 * @author      Amitesh Jain
	 * @created_at  Dec 20,2016
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
