<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Freelancers Controller
 *
 * @package     CFI
 * @subpackage  Api
 * @category    Detailed
 * @author      Deepak Patil
 * @created_at  Dec 01,2016
 *
 */
class Freelancers extends Api_Controller
{
	/**
	 * Construction function
	 *
	 * @Controller  Freelancers
	 * @method    	__construct
	 * @author      Deepak Patil
	 * @created_at  Dec 01,2016
	 * @purpose 	Check the User Authentication, validate user is freelancer and load the user profile into response
	 */
	function __construct()
	{
		try
		{
			parent::__construct();
			/*Check if we have valid User ID of Freelancer*/
			if($this->user_id < 1)
			{
				$lang_key = 'no_access';
				throw new Exception($this->lang->line($lang_key),$this->config->item($lang_key));
			}
			/*Check if its loggedin and with type of Freelancer user*/
       		if(!$this->ion_auth->in_group('freelancer'))
        	{
				/*If user is not freelancer then shw him no access page*/
				$lang_key = 'no_access';
				throw new Exception($this->lang->line($lang_key),$this->config->item($lang_key));
        	}

			$this->load->model('order_model');
			$this->api_response['essentials']->items_in_cart = $this->order_model->get_num_items_in_draft($this->user_id);

		}
		catch (Exception $e)
		{
			$this->response([
					'status' 	=> false,
					'code' 		=> $e->getCode(),
					'message' 	=> $e->getMessage()
				], API_Controller::HTTP_OK);
		}
	}



	/**
 	 * To get freelancer short and statistical details
 	 *
 	 * @Controller  Freelancers
 	 * @method    	dashboard [via GET]
 	 * @author      Deepak Patil
 	 * @created_at  Dec 01,2016
 	 * @purpose 	Its shows the freelancer dashboard data
 	 **/
	public function dashboard_get()
	{
		try
		{
			/*Load User Model*/
			$this->load->model('user_model');
			/*Initiate array for user details*/
			$user_details  = array();
			$user_details = $this->api_response['essentials'];
			/*Read freelancer city,state, country and assign it to user details arrays*/
			$user_location = $this->user_model->get_user_location($this->user_id);
			$user_details->country = $user_location[0]->country;
			$user_details->state = $user_location[0]->state;
			$user_details->city = $user_location[0]->city;
			/*Get typecasting string to numberic value which should be integer*/
			array_walk($user_details, array($this, 'utility_format_ids_integer'),$arrayName = array('exp_year','exp_month'));
			/*Read freelancer ratings and make avarage*/
			$user_details->ratings					= $this->user_model->get_user_avg_ratings($this->user_id);
			/*Read freelancer profile views */
			$user_details->num_views 				= $this->user_model->get_user_num_views($this->user_id);
			/*Read freelancer profile likes */
			$user_details->num_likes 				= $this->user_model->get_user_num_likes($this->user_id);
			/*Get freelancer portfolio projects count */
			$user_details->num_projects_in_portfolio = $this->user_model->get_user_num_portfolio_projects($this->user_id);
			/*Read freelancer purchased leads count */
			$user_details->num_download_leads		= $this->user_model->get_user_num_purchased_leads($this->user_id);
			/*Read freelancer purchased projects details */
			$user_details->download_leads			= $this->user_model->get_user_purchased_leads($this->user_id);

			/*Prepare response*/
			$this->api_response['response'] = $user_details;
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
 	 * To get freelancer profile details
 	 *
 	 * @Controller  Freelancers
 	 * @method    	profile-details [via GET]
 	 * @author      Deepak Patil
 	 * @created_at  Dec 03,2016
 	 * @purpose 	Its shows the freelancer profile data
 	 **/
	public function profile_details_get()
	{
		try
		{
			/*Load User Model*/
			$this->load->model('user_model');
			/*Initiate array for user details*/
			$user_details  = array();
			$user_details = $this->api_response['essentials'];
			/*Read freelancer city,state, country and assign it to user details arrays*/
			$user_location = $this->user_model->get_user_location($this->user_id);
			$user_details->country = $user_location[0]->country;
			$user_details->state = $user_location[0]->state;
			$user_details->city = $user_location[0]->city;
			/*Get typecasting string to numberic value which should be integer*/
			array_walk($user_details, array($this, 'utility_format_ids_integer'),$arrayName = array('exp_year','exp_month','city_id','state_id','country_id'));
			/*Read freelancer skills and assign it to user details arrays*/
			$user_skills = $this->user_model->get_user_skills($this->user_id);
			if($user_skills)
			{
				array_walk($user_skills, array($this, 'utility_format_ids_integer'));
				$user_details->skills = $user_skills;
			}
			/*Read freelancer ratings and make avarage*/
			$user_details->ratings					= $this->user_model->get_user_avg_ratings($this->user_id);
			/*Read freelancer purchased leads count */
			$user_details->num_download_leads		= $this->user_model->get_user_num_purchased_leads($this->user_id);

			/*Prepare response*/
			$this->api_response['response'] = $user_details;
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
 	 * To save updated freelancer profile details
 	 *
 	 * @Controller  Freelancers
 	 * @method    	profile-save [via POST]
 	 * @author      Deepak Patil
 	 * @created_at  Dec 03,2016
 	 * @purpose 	Its update freelancer profile data into DB
 	 **/
	public function profile_save_post()
	{
		$this->load->model('user_model');

		$update_profile['about'] = $this->input->post('about');
		$update_profile['address_1'] = $this->input->post('address_1');

		$update_profile['address_2'] = $this->input->post('address_2');

		$update_profile['city_id'] = $this->input->post('city_id');

		$update_profile['country_id'] = $this->input->post('country_id');

		$update_profile['email'] = $this->input->post('email');

		$update_profile['exp_month'] = $this->input->post('exp_month');

		$update_profile['exp_year'] = $this->input->post('exp_year');

		$update_profile['fb'] = $this->input->post('fb');

		$update_profile['first_name'] = $this->input->post('first_name');

		$update_profile['last_name'] = $this->input->post('last_name');

		$update_profile['ln'] = $this->input->post('ln');

		$update_profile['phone'] = $this->input->post('phone');

		$profile_skills = $this->input->post('skills');

		$update_profile['state_id'] = $this->input->post('state_id');

		$user_id = $this->input->post('user_id');




		if ($this->user_model->update($update_profile, $user_id))
		{
			$this->load->model('user_skill_model');
			$deleted_skills = $this->user_skill_model->where(array('user_id'=>$user_id))->delete();

			if(is_array($profile_skills))
			{
				$new_user_skills = array();
				$idx =0;
				foreach ($profile_skills as $key => $item)
				{
		        	if(isset($item['id']))
		        	{
		            	$new_user_skills[$idx]['skill_id'] = $item['id'];
		            	$new_user_skills[$idx]['user_id'] = $user_id;
						$idx++;
		        	}
				}
				$this->user_skill_model->insert($new_user_skills);
			}
		}
	}

	/** To upload image for user profile image **/
	public function upload_dp_post()
	{
		try
		{
			if(! isset($_FILES['file']))
			{
				$lang_key = 'missing_params';
				throw new Exception($this->lang->line($lang_key),$this->config->item($lang_key));
			}

			if($_FILES["file"]["error"] != 0)
			{
				$lang_key = 'uploding_wrong_file_format';
				throw new Exception($this->lang->line($lang_key),$this->config->item($lang_key));
			}
			$cover_configs = $this->config->item('profile_dp');
			$milliseconds 				= round(microtime(true) * 1000);
			$full_file_name 			= $cover_configs['path'];//'uploads/freelancers/profile/projects/';
			$config['upload_path']   	= './'.$full_file_name;
			$config['allowed_types']	= $cover_configs['allowed_types'];//'gif|jpg|png';
			$config['file_name']      	= $milliseconds .'_'.$_FILES["file"]['name'];
			$full_file_name 			.= $config['file_name'];
			$config['max_size']       	= $cover_configs['max_size'];
			$config['width']          	= $cover_configs['width'];
			$config['height']         	= $cover_configs['height'];
			$this->load->library('upload', $config);

			if ( ! $this->upload->do_upload('file'))
			{
				$lang_key = 'error_uploding_file';
				throw new Exception($this->lang->line($lang_key),$this->config->item($lang_key));
			}
			else
			{
				$data = array('upload_data' => $this->upload->data());
				if(($data["upload_data"]["image_height"] != $cover_configs['height']) or ($data["upload_data"]["image_width"] != $cover_configs['width']))
				{
					$lang_key = 'uploding_file_invalid_size';
					throw new Exception($this->lang->line($lang_key),$this->config->item($lang_key));
				}
			}


			$this->load->model('user_model');

			$update_dp = array('dp_image' => $config['file_name']);

			if (!$this->user_model->update($update_dp, $this->user_id))
			{
				$lang_key = 'unable_update';
				throw new Exception($this->lang->line($lang_key),$this->config->item($lang_key));
			}

			/*Prepare response*/
			$this->api_response['response'] =$full_file_name;
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

	/** To upload image for user profile cover **/
	public function upload_cover_post()
	{
		try
		{
			if(! isset($_FILES['file']))
			{
				$lang_key = 'missing_params';
				throw new Exception($this->lang->line($lang_key),$this->config->item($lang_key));
			}

			if($_FILES["file"]["error"] != 0)
			{
				$lang_key = 'uploding_wrong_file_format';
				throw new Exception($this->lang->line($lang_key),$this->config->item($lang_key));
			}
			$cover_configs = $this->config->item('profile_cover');
			$milliseconds 				= round(microtime(true) * 1000);
			$full_file_name 			= $cover_configs['path'];//'uploads/freelancers/profile/projects/';
			$config['upload_path']   	= './'.$full_file_name;
			$config['allowed_types']	= $cover_configs['allowed_types'];//'gif|jpg|png';
			$config['file_name']      	= $milliseconds .'_'.$_FILES["file"]['name'];
			$full_file_name 			.= $config['file_name'];
			$config['max_size']       	= $cover_configs['max_size'];
			$config['width']          	= $cover_configs['width'];
			$config['height']         	= $cover_configs['height'];
			$this->load->library('upload', $config);

			if ( ! $this->upload->do_upload('file'))
			{
				$lang_key = 'error_uploding_file';
				throw new Exception($this->lang->line($lang_key),$this->config->item($lang_key));
			}
			else
			{
				$data = array('upload_data' => $this->upload->data());
				if(($data["upload_data"]["image_height"] != $cover_configs['height']) or ($data["upload_data"]["image_width"] != $cover_configs['width']))
				{
					$lang_key = 'uploding_file_invalid_size';
					throw new Exception($this->lang->line($lang_key),$this->config->item($lang_key));
				}
			}

			$this->load->model('user_model');

			$update_cover = array('cover_image' => $config['file_name']);

			if (!$this->user_model->update($update_cover, $this->user_id))
			{
				$lang_key = 'unable_update';
				throw new Exception($this->lang->line($lang_key),$this->config->item($lang_key));
			}

			/*Prepare response*/
			$this->api_response['response'] =$full_file_name;
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
