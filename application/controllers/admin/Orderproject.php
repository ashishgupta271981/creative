<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Languages Controller
 *
 * @package     CFI
 * @subpackage  admin
 * @category    Master
 * @author      TBW
 * @created_at  Dec 12,2016
 *
 */
class Orderproject extends Admin_Controller
{
	/**
	 * Construction function
	 *
	 * @Controller  project work Mode
	 * @method    	__construct
	 * @author      projectmode
	 * @created_at  jan 1 2017
	 * @purpose 	Check the Admin user Authentication and load the the project Mode model, Mode transaltion model, form validation library and language model
	 */
	function __construct()
	{
		parent::__construct();
		/*Check if its loggedin and with type of admin user*/
        if(!$this->ion_auth->in_group('admin'))
        {
			/*If not admin user or not logedin user show error message and redirect to admin login*/
            $this->postal->add('You are not allowed to visit the Contents page','error');
            redirect('admin','refresh');
        }
		/* Load the Mode model, Mode transaltion model,form validation library and language model */
        $this->load->model('orderproject_model');
        $this->load->model('language_model');
        $this->load->library('form_validation');
	}
	/* End of function __construct() */



	 /**
 	 * To lists the Mode
 	 *
 	 * @Controller  Mode
 	 * @method    	index [default method]
 	 * @author      TBW
 	 * @created_at  Dec 12,2016
 	 * @purpose 	Its shows the list of Mode with add new Mode button
 	 **/
	public function index()
	{
        $list_orders = $this->orderproject_model->get_orderproject_list();
        $this->data['content_type'] = 'orderproject';
		$this->data['content_title'] = 'All Order';
        $this->data['list_orders'] = $list_orders;
        $this->render('admin/orderproject/index_view');
	}
	/* End of function index() */


    public function view($id)
    {
        $content = $this->orderproject_model->get_orderproject_details($id);
        $project_list = $this->orderproject_model->get_orderproject_listdetails($id);
        $this->data['translation'] = $content;
        $this->data['project_list'] = $project_list;
        $this->render('admin/orderproject/details_view');

    }

	
}