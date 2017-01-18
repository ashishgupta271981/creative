<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Widgets Controller
 *
 * @package     CFI
 * @subpackage  Api
 * @category    Detailed
 * @author      Deepak Patil
 * @created_at  Jan 04,2017
 *
 */
class Widgets extends Api_Controller
{
	/**
	 * Construction function
	 *
	 * @Controller  Widgets
	 * @method    	__construct
	 * @author      Deepak Patil
	 * @created_at  Jan 06,2017
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
	 * To get list of past transactions
	 *
	 * @Controller  Orders
	 * @method    	transactions [via GET]
	 * @param		page ID of type integer
	 * @author      Deepak Patil
	 * @created_at  Jan 04,2017
	 * @purpose 	Its shows the freelancer transactions
	 **/
	public function blogs_get()
	{
		try
		{
			/*Load User Model*/
			$this->load->model('blog_translation_model');
			$this->api_response['response'] = $this->blog_translation_model->get_blogs_for_widgets();

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
	 * To add new item into shop cart
	 *
	 * @Controller  Shopcart
	 * @method    	add-to-cart [via GET]
	 * @param	project ID of type integer
	 * @author      Deepak Patil
	 * @created_at  Dec 29,2016
	 * @purpose 	Its add item into the freelancer shop cart
	 **/
	public function add_to_cart_get($project_id,$qty=1)
	{
		try
		{
			/*Load Order Model*/
			$this->load->model('order_model');
			$order_id = $this->order_model->get_current_order($this->user_id);
			if(!$order_id)
			{
				$order_id = $this->order_model->create_order($this->user_id);

			}
			/*Load Order Projects Model*/
			$this->load->model('order_project_model');
			$ordered_project = $this->order_project_model->get_project_in_current_order($order_id,$this->user_id,$project_id);
			if(!$ordered_project)
			{
				$ordered_project = $this->order_project_model->add_project($order_id,$this->user_id,$project_id,$qty);

			}
			if((int) $ordered_project < 1)
			{
				$lang_key = 'no_access';
				throw new Exception($this->lang->line($lang_key,$this->config->item($lang_key)));
			}
			/*Load User Wish List*/
			$this->load->model('users_wishlist_model');

			$record_data = array(
				'user_id' => $this->user_id,
				'project_id' => $project_id
			);
			$this->users_wishlist_model->where($record_data)->delete();
			$this->api_response['response'] = $ordered_project;
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
	 * To add new item into shop cart
	 *
	 * @Controller  Shopcart
	 * @method    	add-to-cart [via GET]
	 * @param	project ID of type integer
	 * @author      Deepak Patil
	 * @created_at  Dec 29,2016
	 * @purpose 	Its add item into the freelancer shop cart
	 **/
	public function remove_from_cart_get($project_id)
	{
		try
		{
			/*Load Order Model*/
			$this->load->model('order_model');
			$order_id = $this->order_model->get_current_order($this->user_id);
			if(!$order_id)
			{
				$lang_key = 'no_access';
				throw new Exception($this->lang->line($lang_key,$this->config->item($lang_key)));

			}
			/*Load Order Projects Model*/
			$this->load->model('order_project_model');

			$record_data = array(
				'user_id' => $this->user_id,
				'project_id' => $project_id,
				'order_id' => $order_id
			);
			$this->order_project_model->where($record_data)->delete();
			$this->api_response['response'] = $project_id;
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
	 * To get projects short and statistical details
	 *
	 * @Controller  Shopcart
	 * @method    	search [via GET]
	 * @param	$q [of string data type]
	 * @author      Deepak Patil
	 * @created_at  Dec 25,2016
	 * @purpose 	Its shows the freelancer dashboard data
	 **/
	public function wish_list_addnew_get($project_id=0,$like_status=0)
	{
		try
		{
			if($this->user_id < 1)
			{
				$lang_key = 'no_access';
				throw new Exception($this->lang->line($lang_key,$this->config->item($lang_key)));
			}

			/*Check if we have valid User ID of Freelancer*/
			if($project_id < 1)
			{
				$lang_key = 'no_access';
				throw new Exception($this->lang->line($lang_key,$this->config->item($lang_key)));
			}

			/*Load User Model*/
			$this->load->model('users_wishlist_model');

			$record_data = array(
				'user_id' => $this->user_id,
				'project_id' => $project_id
			);
			if($like_status)
			{
				$this->users_wishlist_model->where($record_data)->delete();
				$staus = 1;
			}
			else
			{
				$this->users_wishlist_model->insert($record_data);
				$staus = 0;
			}

			$this->api_response['response']	= $staus;

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
	 * To get projects added into shop-cart
	 *
	 * @Controller  Shopcart
	 * @method    	shop-cart [via GET]
	 * @param		n/a
	 * @author      Deepak Patil
	 * @created_at  Dec 29,2016
	 * @purpose 	Its shows the projects added into cart
	 **/
	public function shop_cart_get()
	{
		try
		{

			/*Load Order Model*/
			$this->load->model('order_model');
			$this->load->model('order_project_model');
			$order_id = $this->order_model->get_current_order($this->user_id);
			if(!$order_id)
			{
				$lang_key = 'no_access';
				throw new Exception($this->lang->line($lang_key,$this->config->item($lang_key)));

			}

			$shop_items = $this->order_project_model->get_shop_items($order_id);
			if(!$shop_items)
			{
				$lang_key = 'no_access';
				throw new Exception($this->lang->line($lang_key,$this->config->item($lang_key)));
			}


			/*Prepare response*/
			$this->api_response['response'] = $shop_items;

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
	 * To get coupon-details into shop-cart
	 *
	 * @Controller  Shopcart
	 * @method    	coupon-details [via GET]
	 * @param		n/a
	 * @author      Deepak Patil
	 * @created_at  Dec 29,2016
	 * @purpose 	Its shows the coupon-details
	 **/
	public function coupon_details_get($coupon_code)
	{
		try
		{

			/*Load Order Model*/
			$this->load->model('coupon_model');

			$coupons = $this->coupon_model->get_coupon($coupon_code);
			if(!$coupons)
			{
				$lang_key = 'no_access';
				throw new Exception($this->lang->line($lang_key,$this->config->item($lang_key)));
			}


			/*Prepare response*/
			$this->api_response['response'] = $coupons[0];

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
	 * Set up payment checkout
	 *
	 * @Controller  Shopcart
	 * @method    	coupon-details [via GET]
	 * @param		n/a
	 * @author      Deepak Patil
	 * @created_at  Dec 29,2016
	 * @purpose 	Its shows the coupon-details
	 **/
	public function checkout_get($sub_total,$discount,$app_url=null)
	{
		try
		{
			if(empty($app_url))
			{
				$app_url = site_url('freelancer/#/');
			}
			$payu = $this->config->item('payu');

			$merchant_key  	= $payu->merchant_key;
			$salt          	= $payu->salt;
			$payu_base_url 	= $payu->base_url; // For Test environment
			$action        	= $payu->action;
			$success 		= $app_url.$payu->success;
			$failure 		= $app_url.$payu->failure;

			$txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);

			$hash         = '';
			$hashSequence = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";

			$amount = $sub_total - $discount;
			$this->load->model('order_model');
			$this->load->model('order_project_model');

			$order_id = $this->order_model->get_current_order($this->user_id);
			if(!$order_id)
			{
				$lang_key = 'no_access';
				throw new Exception($this->lang->line($lang_key,$this->config->item($lang_key)));

			}

			$order_data = array(
		        'transaction_id' => $txnid,
		        'total' => $amount,
		        'subtotal' => $sub_total,
				'coupon_discount' => $discount
			);

			$order_id = $this->order_model->updateOrder($order_id,$order_data);

			$data = array('key' => $merchant_key,
						  'txnid' => $txnid,
						  'amount' => $amount,
						  'productinfo' => "Testing product info",
						  'firstname' => $this->api_response['essentials']->first_name,
						  'email' => $this->api_response['essentials']->email,
						  'phone' => $this->api_response['essentials']->phone,
						  'surl' => $success,
						  'furl' => $failure
						 );

			if(empty($data['key'])
		          || empty($data['txnid'])
		          || empty($data['amount'])
		          || empty($data['firstname'])
		          || empty($data['email'])
		          || empty($data['phone'])
		          || empty($data['productinfo'])
		          || empty($data['surl'])
		          || empty($data['furl'])
	  			)
			{
				$lang_key = 'no_access';
				throw new Exception($this->lang->line($lang_key,$this->config->item($lang_key)));
			}




			$hashVarsSeq = explode('|', $hashSequence);
	    	$hash_string = '';
			foreach($hashVarsSeq as $hash_var) {
		      	$hash_string .= isset($data[$hash_var]) ? $data[$hash_var] : '';
		      	$hash_string .= '|';
	    	}
		    $hash_string .= $salt;
		    $hash = strtolower(hash('sha512', $hash_string));
			$data['hash'] = $hash;
			$data['action'] = $action;


			/*Prepare response*/
			$this->api_response['response'] = $data;

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
