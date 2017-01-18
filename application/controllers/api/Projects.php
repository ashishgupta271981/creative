<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Prjects Controller
 *
 * @package     CFI
 * @subpackage  Api
 * @category    Detailed
 * @author      Deepak Patil
 * @created_at  Dec 25,2016
 *
 */
class Projects extends Api_Controller
{
	/**
	 * Construction function
	 *
	 * @Controller  Projects
	 * @method    	__construct
	 * @author      Deepak Patil
	 * @created_at  Dec 25,2016
	 * @purpose 	Check the User Authentication, validate user is freelancer and load the user profile into response
	 */
	function __construct()
	{
		try
		{
			parent::__construct();
			/*Check if its loggedin and with type of Freelancer user*/
       		if(!$this->ion_auth->in_group('freelancer'))
        	{
				/*If user is not freelancer then shw him no access page*/
				$lang_key = 'no_access';
				throw new Exception($this->lang->line($lang_key),$this->config->item($lang_key));
        	}
		}
		catch (Exception $e)
		{
			$this->response([
					'status' 	=> false,
					'code' 		=> $e->getCode(),
					'message' 	=> $e->getMessage()
				], API_Controller::HTTP_OK);
		}
		/*echo "<pre>";
		print_r($this->session->userdata);
		exit;*/
	}


	/**
 	 * To get projects short and statistical details
 	 *
 	 * @Controller  Projects
 	 * @method    	search [via GET]
	 * @param	$q [of string data type]
 	 * @author      Deepak Patil
 	 * @created_at  Dec 25,2016
 	 * @purpose 	Its shows the freelancer dashboard data
 	 **/
	public function search_get($q=null,$pid=1,$is_featured)
	{
		try
		{
			/*Check if we have valid User ID of Freelancer*/
			if(empty($q))
			{
				$lang_key = 'no_access';
				throw new Exception($this->lang->line($lang_key,$this->config->item($lang_key)));
			}
			$is_featured = (int) $is_featured;
			/*Load User Model*/
			$this->load->model('project_model');
			$total_records = $this->project_model->get_search_total_records($q,$is_featured);
			if(!$total_records)
			{
				$lang_key = 'no_access';
				throw new Exception($this->lang->line($lang_key,$this->config->item($lang_key)));
			}

			$records_per_page = $this->config->item('RPP_project_search');
			$this->api_response['pages'] =$this->genrate_pages($records_per_page,$total_records,$pid);

			$limit = ($pid-1) * $records_per_page;
			/*Prepare response*/
			$this->api_response['response'] = $this->project_model->get_search($q,$is_featured,$records_per_page,$limit);

			/*Send success response*/
			$lang_key = 'universal_success';
			$this->response([
						'id'		=> $lang_key,
						'status' 	=> true,
						'code' 		=> $this->config->item($lang_key),
						'data' 	=> $this->api_response
						], API_Controller::HTTP_OK);
		}
		catch (Exception $e)
		{
			/*Send error response, this is due tovalidations,checks failed*/
			$this->response([
					'status' 	=> false,
					'code' 		=> $e->getCode(),
					'message' 	=> $e->getMessage()
				], API_Controller::HTTP_OK);
		}
		finally
		{
			/*Send error response when something went horrible */
			$lang_key = 'universal_error';
			$this->response([
					'id'		=> $lang_key,
					'status' 	=> false,
					'code' 		=> $this->config->item($lang_key),
					'message' 	=> $this->lang->line($lang_key)
				], API_Controller::HTTP_OK);

		}
	}


	/**
	 * To get project details
	 *
	 * @Controller  Projects
	 * @method    	details [via GET]
	 * @param	$q [of string data type]
	 * @author      Deepak Patil
	 * @created_at  Jan 07,2017
	 * @purpose 	Its shows the project data
	 **/
	public function details_get($project_id)
	{
		try
		{
			/*Check if we have valid project ID*/
			if(empty($project_id))
			{
				$lang_key = 'no_access';
				throw new Exception($this->lang->line($lang_key,$this->config->item($lang_key)));
			}

			/*Load Project Model*/
			$this->load->model('project_model');
			$this->load->model('project_skill_model');

			$project = $this->project_model->get_details($project_id);
			$project[0]->lead_cost = $this->config->item('lead_cost');
			$project[0]->skills = $this->project_skill_model->get_projects_skills($project_id);
			/*Prepare response*/
			$this->api_response['response'] = $project[0];

			/*Send success response*/
			$lang_key = 'universal_success';
			$this->response([
						'id'		=> $lang_key,
						'status' 	=> true,
						'code' 		=> $this->config->item($lang_key),
						'data' 	=> $this->api_response
						], API_Controller::HTTP_OK);
		}
		catch (Exception $e)
		{
			/*Send error response, this is due tovalidations,checks failed*/
			$this->response([
					'status' 	=> false,
					'code' 		=> $e->getCode(),
					'message' 	=> $e->getMessage()
				], API_Controller::HTTP_OK);
		}
		finally
		{
			/*Send error response when something went horrible */
			$lang_key = 'universal_error';
			$this->response([
					'id'		=> $lang_key,
					'status' 	=> false,
					'code' 		=> $this->config->item($lang_key),
					'message' 	=> $this->lang->line($lang_key)
				], API_Controller::HTTP_OK);

		}
	}

}
