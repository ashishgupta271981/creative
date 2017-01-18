<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Flportfolio Controller
 *
 * @package     CFI
 * @subpackage  Api
 * @category    Detailed
 * @author      Deepak Patil
 * @created_at  Dec 01,2016
 *
 */
class Flportfolio extends Api_Controller
{
	/**
	 * Construction function
	 *
	 * @Controller  Flportfolio
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




	public function dashboard_get()
	{
		try
		{
			/*Load User Model*/
			$this->load->model('user_model');
			/*Initiate array for user details*/
			$user_details  = array();
			$user_details = $this->api_response['essentials'];
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





	/**
 	 * To get freelancer project details (portfolio listing)
 	 *
 	 * @Controller  Freelancers
 	 * @method    	list-projects [via GET]
 	 * @author      Deepak Patil
 	 * @created_at  Dec 01,2016
 	 * @purpose 	Its shows the freelancer dashboard data
 	 **/
	public function list_projects_get($pid=1)
	{
		try
		{
			$this->load->model('portfolio_project_model');
			$total_records = $this->portfolio_project_model->get_user_projects_count($this->user_id);
			if(!$total_records)
			{
				$lang_key = 'no_records';
				throw new Exception($this->lang->line($lang_key),$this->config->item($lang_key));
			}

			$records_per_page = $this->config->item('RPP_portfolio_project_list');
			$this->api_response['pages'] = $this->genrate_pages($records_per_page,$total_records,$pid);

			$limit = ($pid-1) * $records_per_page;
			/*Prepare response*/
			$this->api_response['response']  = $this->portfolio_project_model->get_user_projects($this->user_id,$records_per_page,$limit);

			if(! $this->api_response['response'])
			{
				$lang_key = 'no_records';
				throw new Exception($this->lang->line($lang_key),$this->config->item($lang_key));
			}

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
			$this->response([
					'status' 	=> false,
					'code' 		=> $e->getCode(),
					'message' 	=> $e->getMessage()
					], API_Controller::HTTP_OK);
		}
		finally
		{
			$code		= $e->getCode();
			$message	= "Sytax/Logical Error. Please contact CFI Tech Support.";
			$this->response([
					'status' 	=>false,
					'code' 		=> $code,
					'message' 	=> $message
					], API_Controller::HTTP_OK);
		}

	}

	public function project_details_get($project_id)
	{
		try
		{
			$this->load->model('portfolio_project_model');
			$this->load->model('portfolio_project_image_model');

			$projects = $this->portfolio_project_model->get_user_project_details($this->user_id,$project_id);

			if(! $projects[0])
			{
				$lang_key = 'no_records';
				throw new Exception($this->lang->line($lang_key),$this->config->item($lang_key));
			}
			$projects[0]->images = $this->portfolio_project_image_model->get_by_project($project_id);
			$this->api_response['response'] = $projects[0];
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
			$this->response([
					'status' 	=> false,
					'code' 		=> $e->getCode(),
					'message' 	=> $e->getMessage()
					], API_Controller::HTTP_OK);
		}
		finally
		{
			$code		= $e->getCode();
			$message	= "Sytax/Logical Error. Please contact CFI Tech Support.";
			$this->response([
					'status' 	=>false,
					'code' 		=> $code,
					'message' 	=> $message
					], API_Controller::HTTP_OK);

		}

	}


	public function create_project_post()
	{
		try
		{
			$this->load->model('portfolio_project_model');
			$this->load->model('portfolio_project_translation_model');
			$this->load->model('portfolio_project_image_model');
			if(empty($this->input->post('title')))
			{
				$lang_key = 'invalid_project_title';
				throw new Exception($this->lang->line($lang_key),$this->config->item($lang_key));
			}
			/*if(empty($this->input->post('skills')))
			{
				$lang_key = 'invalid_project_skill';
				throw new Exception($this->lang->line($lang_key),$this->config->item($lang_key));
			}*/
			$title 			= $this->input->post('title');
            $language_slug 	= $this->input->post('language_slug');
			if(empty($language_slug)){
				$language_slug = 'en';
			}
            $skills 		= $this->input->post('skills');
			$images = $this->input->post('uploaded_files');
			$featured_image ="";


			if(isset($images[0]))
			{
				$file_chunks = explode("/",$featured_image);
				$featured_image = $images[0]['name'];
			}

			$url			= $this->input->post('url');
			$media_url		= $this->input->post('media_url');
			$desc			= $this->input->post('desc');
			$published_at	= date("Y-m-d H:i:s");

			$start_date		= $this->input->post('start_date');
			$end_date		= $this->input->post('end_date');
			if(!empty($start_date))
			{
				$start_date	= date("Y-m-d H:i:s",strtotime($start_date));
			}

			if(!empty($end_date))
			{
				$end_date	= date("Y-m-d H:i:s",strtotime($end_date));
			}
            $insert_content 	= array(
										'user_id' => $this->user_id,
										'featured_image'	=> $featured_image,
										'published_at'		=> $published_at,
										'start_date'		=> $start_date,
										'end_date'			=> $end_date,
										'published'			=> 1
									);
			$insert_translation = array(
										'title' 		=> $title,
										'skills' 		=> $skills,
										'description' 	=> $desc,
										'project_url' 	=> $url,
										'media_url'		=> $media_url,
										'language_slug' => $language_slug
									);
            $project_id = $this->portfolio_project_model->insert($insert_content);
			if(! $project_id)
			{
				$lang_key = 'unable_add_project_db';
				throw new Exception($this->lang->line($lang_key),$this->config->item($lang_key));
			}
            $insert_translation['project_id'] = $project_id;
            if($translation_id = $this->portfolio_project_translation_model->insert($insert_translation));


			if(! $translation_id)
			{
				$this->db->trans_rollback();
				throw new Exception(null,1008);
			}
			if ($this->db->trans_status() === FALSE)
			{
        		$this->db->trans_rollback();
				throw new Exception(null,1009);
			}

			$num_imgs = count($images);
			if($num_imgs > 1)
			{
				$insert_images 	= array();
				for($_i=1;$_i<$num_imgs;$_i++)
				{
					$insert_images[] 	= array(
												'file_name'	=> $images[$_i]['name'],
												'project_id'		=> $project_id
											);
				}

				$this->portfolio_project_image_model->insert($insert_images);
			}

			$status 	= TRUE;
			$code		= 100;
			$message	= $project_id;
			$this->response([
							'status' 	=> $status,
							'code' 		=> $code,
							'message' 	=> $message
							], API_Controller::HTTP_OK);

		}
		catch(Exception $e)
		{
			$status 	= FALSE;
			$code		= $e->getCode();
			$message	= $this->_error[$code];
			$this->response([
								'status' 	=> $status,
								'code' 		=> $code,
								'message' 	=> $message
								], API_Controller::HTTP_NOT_FOUND);
	  	}
		finally
		{
			$status 	= FALSE;
			$code		= $e->getCode();
			$message	= "Sytax/Logical Error. Please contact developer.";
			$this->response([
								'status' 	=> $status,
								'code' 		=> $code,
								'message' 	=> $message
								], API_Controller::HTTP_NOT_FOUND);

		}

	}
	/** To upload image add project screen to server **/
	public function upload_project_image_post()
    {
		try
		{
			if(! isset($_FILES['file']))
			{
				throw new Exception(null,1001);
			}

			if($_FILES["file"]["error"] != 0)
			{
				throw new Exception(null,1002);
			}
			$cover_configs = $this->config->item('portfolio_project_featured');
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
				throw new Exception(null,1003);
					//$this->data['error'] = $this->upload->display_errors();
			}
			else
			{
				$data = array('upload_data' => $this->upload->data());
				if(($data["upload_data"]["image_height"] != $cover_configs['height']) or ($data["upload_data"]["image_width"] != $cover_configs['width']))
				{
					throw new Exception(null,1004);
				}
			}

			$status 	= TRUE;
			$code		= 100;
			$message	= $full_file_name;
			$this->response([
							'status' 	=> $status,
							'code' 		=> $code,
							'data' 	=> $message
							], API_Controller::HTTP_OK);
		}
		catch(Exception $e)
		{
			$status 	= FALSE;
			$code		= $e->getCode();
			$message	= $this->_error[$code];
			$this->response([
						'status' 	=> $status,
						'code' 		=> $code,
						'message' 	=> $message
						], API_Controller::HTTP_NOT_FOUND);
		}

     }

	public function index($content_type = 'page')
	{
        $list_content = $this->content_model->get_content_list($content_type);
        $this->data['content_type'] = $content_type;
        $this->data['contents'] = $list_content;
        $this->render('admin/contents/index_view');
	}

    public function create($content_type = 'page', $language_slug = NULL, $content_id = 0)
    {
        $language_slug = (isset($language_slug) && array_key_exists($language_slug, $this->langs)) ? $language_slug : $this->current_lang;

        $this->data['content_type'] = $content_type;
        $this->data['content_language'] = $this->langs[$language_slug]['name'];
        $this->data['language_slug'] = $language_slug;
        $content = $this->content_model->get($content_id);
        if($content_id != 0 && $content==FALSE)
        {
            $content_id = 0;
        }
        if($this->content_translation_model->where(array('content_id'=>$content_id,'language_slug'=>$language_slug))->get())
        {
            $this->postal->add('A translation for that content already exists.','error');
            redirect('admin/contents/index/'.$content_type, 'refresh');
        }
        $this->data['content'] = $content;
        $this->data['content_id'] = $content_id;
        $this->data['parents'] = $this->content_model->get_parents_list($content_type,$content_id,$language_slug);

        $rules = $this->content_model->rules;
        $this->form_validation->set_rules($rules['insert']);
        if($this->form_validation->run()===FALSE)
        {
            $this->render('admin/contents/create_view');
        }
        else
        {
            $content_type = $this->input->post('content_type');
            $parent_id = $this->input->post('parent_id');
            $title = $this->input->post('title');
            $short_title = (strlen($this->input->post('short_title')) > 0) ? $this->input->post('short_title') : $title;
            $slug = (strlen($this->input->post('slug')) > 0) ? url_title($this->input->post('slug'),'-',TRUE) : url_title(convert_accented_characters($title),'-',TRUE);
            $order = $this->input->post('order');
            $content = $this->input->post('content');
            $teaser = (strlen($this->input->post('teaser')) > 0) ? $this->input->post('teaser') : substr($content, 0, strpos($content, '<!--more-->'));
            if($teaser == 0) $teaser = '';
            $page_title = (strlen($this->input->post('page_title')) > 0) ? $this->input->post('page_title') : $title;
            $page_description = (strlen($this->input->post('page_description')) > 0) ? $this->input->post('page_description') : ellipsize($teaser, 160);
            $page_keywords = $this->input->post('page_keywords');
            $content_id = $this->input->post('content_id');
            $language_slug = $this->input->post('language_slug');
            $published_at = $this->input->post('published_at');
            if ($content_id == 0)
            {
                $insert_content = array('content_type'=>$content_type,'published_at'=>$published_at, 'parent_id' => $parent_id);
                $content_id = $this->content_model->insert($insert_content);
            }

            $insert_translation = array('content_id'=>$content_id,'title' => $title, 'short_title' => $short_title, 'teaser' => $teaser,'content' => $content,'page_title' => $page_title, 'page_description' => $page_description,'page_keywords' => $page_keywords,'language_slug' => $language_slug);

            if($translation_id = $this->content_translation_model->insert($insert_translation))
            {
                $this->content_model->update(array('published_at'=>$published_at,'parent_id'=>$parent_id, 'order'=>$order),$content_id);

                $insert_slug = array(
                    'content_type'=> $content_type,
                    'content_id'=>$content_id,
                    'translation_id'=>$translation_id,
                    'language_slug'=>$language_slug,
                    'url'=>$slug);
                $this->slug_model->verify_insert($insert_slug);
            }

            redirect('admin/contents/index/'.$content_type,'refresh');

        }


    }

    public function edit($language_slug, $content_id)
    {
        $content = $this->content_model->get($content_id);
        if($content == FALSE)
        {
            $this->postal->add('There is no content to translate.','error');
            redirect('admin/contents/index', 'refresh');
        }
        $content_type = $content->content_type;
        $translation = $this->content_translation_model->where(array('content_id'=>$content_id, 'language_slug'=>$language_slug))->get();
        $this->data['content_language'] = $this->langs[$language_slug]['name'];
        if($translation == FALSE)
        {
            $this->postal->add('There is no translation for that content.','error');
            redirect('admin/contents/index/'.$content_type, 'refresh');
        }

        $this->load->model('image_model');
        $images = $this->image_model->where('content_id',$content_id)->get_all();
        if($images!== FALSE)
        {
            $this->data['uploaded_images'] = $images;
        }

        $this->data['translation'] = $translation;
        $this->data['parents'] = $this->content_model->get_parents_list($content_type,$content_id,$language_slug);
        $this->data['content'] = $content;
        $this->data['slugs'] = $this->slug_model->where(array('translation_id'=>$translation->id))->get_all();
        $rules = $this->content_model->rules;
        $this->form_validation->set_rules($rules['update']);
        if($this->form_validation->run()===FALSE)
        {
            $this->render('admin/contents/edit_view');
        }
        else
        {
            $translation_id = $this->input->post('translation_id');
            if($translation = $this->content_translation_model->get($translation_id))
            {
                $parent_id = $this->input->post('parent_id');
                $content_type = $this->input->post('content_type');
                $title = $this->input->post('title');
                $short_title = $this->input->post('short_title');
                $slug = url_title(convert_accented_characters($this->input->post('slug')),'-',TRUE);
                $order = $this->input->post('order');
                $content = $this->input->post('content');
                $teaser = (strlen($this->input->post('teaser')) > 0) ? $this->input->post('teaser') : substr($content, 0, strpos($content, '<!--more-->'));
                $page_title = (strlen($this->input->post('page_title')) > 0) ? $this->input->post('page_title') : $title;
                $page_description = (strlen($this->input->post('page_description')) > 0) ? $this->input->post('page_description') : ellipsize($teaser, 160);
                $page_keywords = $this->input->post('page_keywords');
                $content_id = $this->input->post('content_id');
                $published_at = $this->input->post('published_at');
                $language_slug = $this->input->post('language_slug');

                $update_translation = array(
                    'title' => $title,
                    'short_title' => $short_title,
                    'teaser' => $teaser,
                    'content' => $content,
                    'page_title' => $page_title,
                    'page_description' => $page_description,
                    'page_keywords' => $page_keywords);

                if ($this->content_translation_model->update($update_translation, $translation_id))
                {
                    $update_content = array('parent_id' => $parent_id, 'published_at' => $published_at, 'order' => $order);

                    $this->content_model->update($update_content, $content_id);
                    if(strlen($slug)>0)
                    {
                        $new_slug = array(
                            'content_type' => $content_type,
                            'content_id' => $content_id,
                            'translation_id' => $translation_id,
                            'language_slug' => $language_slug,
                            'url' => $slug);
                        $this->slug_model->verify_insert($new_slug);
                    }
                    $this->postal->add('The translation was updated successfully.','success');
                }
            }
            else
            {
                $this->postal->add('There is no translation to update.','error');
            }
            redirect('admin/contents/index/'.$content_type,'refresh');
        }
    }
    public function publish($content_id, $published)
    {
        $content = $this->content_model->get($content_id);
        if( ($content != FALSE) && ($published==1 || $published==0))
        {
            if($this->content_model->update(array('published'=>$published),$content_id))
            {
                $this->postal->add('The published status was set.','success');
            }
            else
            {
                $this->postal->add('Couldn\'t set the published status.','error');
            }
        }
        else
        {
            $this->postal->add('Can\'t find the content or the published status isn\'t correctly set.','error');
        }
        redirect('admin/contents/index/'.$content->content_type,'refresh');
    }

    public function deleted($language_slug, $content_id)
    {
        if($content = $this->content_model->get($content_id))
        {
            if($language_slug=='all')
            {
                if($deleted_translations = $this->content_translation_model->where('content_id',$content_id)->delete())
                {
                    $deleted_slugs = $this->slug_model->where(array('content_type'=>$content->content_type,'content_id'=>$content_id))->delete();

                    $deleted_images = 0;
                    $this->load->model('image_model');
                    $images = $this->image_model->where(array('content_type'=>$content->content_type,'content_id'=>$content_id))->get_all();
                    if(!empty($images))
                    {
                        foreach($images as $image)
                        {
                            @unlink(FCPATH.'media/'.$image->file);
                        }
                        $deleted_images = $this->image_model->where(array('content_type'=>$content->content_type,'content_id'=>$content_id))->delete();
                    }

                    $this->load->model('keyword_model');
                    $deleted_keywords = $this->keyword_model->where(array('content_type'=>$content->content_type,'content_id'=>$content_id))->delete();

                    $this->load->model('keyphrase_model');
                    $deleted_keyphrases = $this->keyphrase_model->where(array('content_type'=>$content->content_type,'content_id'=>$content_id))->delete();

                    $deleted_pages = $this->content_model->delete($content_id);

                    $this->postal->add($deleted_pages.' page deleted. There were also '.$deleted_translations.' translations, '.$deleted_keywords.' keywords, '.$deleted_keyphrases.' key phrases, '.$deleted_slugs.' slugs and '.$deleted_images.' images deleted.','success');
                }
                else
                {
                    $deleted_pages = $this->content_model->delete($content_id);
                    $this->postal->add($deleted_pages.' page was deleted','success');
                }
                @unlink(FCPATH.'media/'.$this->featured_image.'/'.$content->featured_image);
            }
            else
            {
                if($this->content_translation_model->where(array('content_id'=>$content_id,'language_slug'=>$language_slug))->delete())
                {
                    $deleted_slugs = $this->slug_model->where(array('language_slug'=>$language_slug,'content_id'=>$content_id))->delete();

                    $this->load->model('keyword_model');
                    $deleted_keywords = $this->keyword_model->where(array('content_id'=>$content_id,'language_slug'=>$language_slug))->delete();

                    $this->load->model('keyphrase_model');
                    $deleted_keyphrases = $this->keyphrase_model->where(array('content_id'=>$content_id,'language_slug'=>$language_slug))->delete();

                    $this->postal->add('The translation, '.$deleted_keywords.' keywords, '.$deleted_keyphrases.' key phrases and '.$deleted_slugs.' slugs were deleted.','success');
                }
            }
        }
        else
        {
            $this->postal->add('There is no translation to delete.','error');
        }
        redirect('admin/contents/index/'.$content->content_type,'refresh');

    }
}
