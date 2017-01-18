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
class Project extends Admin_Controller
{
	/**
	 * Construction function
	 *
	 * @Controller  project
	 * @method    	__construct
	 * @author      project
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
        $this->load->model(array('project_model','project_translation_model','language_model'));
        $this->load->library(array('form_validation','pagination'));
		$this->featured_image = $this->config->item('cms_featured_image');
		$this->load->helper('text');
		
		
	}

	/* End of function __construct() */



	 /**
 	 * To lists the project
 	 *
 	 * @Controller  project
 	 * @method    	index [default method]
 	 * @author      TBW
 	 * @created_at  Dec 12,2016
 	 * @purpose 	Its shows the list of project with add new project button
 	 **/
	public function index()
	{
		$q =$this->input->get_post('search_string');
		$num_records= 10;
		$start=($this->uri->segment(4)>0)?$this->uri->segment(4):0;

		$url = base_url().'admin/project/index/';  
        	
		$total_rows = $this->project_model->admin_get_project_total_records($q);
		
		$array_pagination =array(
								'num_records'=>$num_records,	
								'total_rows'=>$total_rows,
								'base_url'=>$url,
								'q'=>$q,
								'uri_segment'=>4
								); 
		$this->data['pagination'] = $this->PaginationViewData($array_pagination);

		$list_project = $this->project_model->admin_get_project_list($q,$num_records,$start);
		
		$this->data['content_type'] = 'project';
		$this->data['content_title'] = 'Project';
        $this->data['project'] = $list_project;
        $this->render('admin/project/index_view');
		
		
	}


	public function PaginationViewData($array_pagination){

		/* This Application Must Be Used With BootStrap 3 *  */
		$config['full_tag_open'] = '<div><ul class="pagination pagination-small pagination-centered">';
		$config['full_tag_close'] = '</ul></div>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
		$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
		$config['next_tag_open'] = "<li>";
		$config['next_tagl_close'] = "</li>";
		$config['prev_tag_open'] = "<li>";
		$config['prev_tagl_close'] = "</li>";
		$config['first_tag_open'] = "<li>";
		$config['first_tagl_close'] = "</li>";
		$config['last_tag_open'] = "<li>";
		$config['last_tagl_close'] = "</li>";
		$config['uri_segment'] = $array_pagination['uri_segment'];
		$config['per_page'] = $array_pagination['num_records'];
		$config['total_rows'] = $array_pagination['total_rows'];
		$q = $array_pagination['q'];
		$url = $array_pagination['base_url'];
		if($q!=''){
			$getData = array('search_string'=>$q);
			$config['base_url'] = $url;   
			$config['suffix'] = '?'.http_build_query($getData,'',"&amp;");
			$config['first_url'] = $config['base_url'].'?search_string='.$q;
	    }else{
		   	$config['base_url'] = $url;  
	    }
		$this->pagination->initialize($config);
		$pagination = $this->pagination->create_links();
		return $pagination;

	}
	/* End of function index() */


	/**
	* To create new project
	*
	* @Controller  project
	* @method    	create
	* @params
	*				$content_type (type:string,default:project)
	*				$language_slug (type:string,default:null)
	*				$id (type:integer,default:0)
	* @author      TBW
	* @created_at  Dec 12,2016
	* @purpose 	Its create new project
	**/
    public function create($content_type = 'project', $language_slug = NULL, $id = 0)
    {
        $language_slug = (isset($language_slug) && array_key_exists($language_slug, $this->langs)) ? $language_slug : $this->current_lang;

        $this->data['content_type'] = $content_type;
		$this->data['content_title'] = 'Project';
        $this->data['content_language'] = $this->langs[$language_slug]['name'];
        $this->data['language_slug'] = $language_slug;
        $content = $this->project_model->get($id);
        if($id != 0 && $content==FALSE)
        {
            $id = 0;
        }
        if($this->project_translation_model->where(array('project_id'=>$id,'language_slug'=>$language_slug))->get())
        {
		
            $this->postal->add('A translation for that content already exists.','error');
            redirect('admin/project/index/'.$content_type, 'refresh');
        }
        $this->data['content'] = $content;
        $this->data['id'] = $id;
		$this->data['budgets'] = $this->project_model->admin_get_budget_list();
		$this->data['modes'] = $this->project_model->admin_get_mode_list();	

        $rules = $this->project_model->rules;
        $this->form_validation->set_rules($rules['insert']);
        if($this->form_validation->run()===FALSE)
        {
            $this->render('admin/project/create_view');
        }
        else
        {  
            $content_type = $this->input->post('content_type');
            $name = $this->input->post('name');
            $id = $this->input->post('id');
            $language_slug = $this->input->post('language_slug');
            $published_at = $this->input->post('published_at');
			$project_budget_id = $this->input->post('project_budget_id');
			$project_work_mode_id = $this->input->post('project_work_mode_id');	
			$description = $this->input->post('description');
			$page_title = (strlen($this->input->post('page_title')) > 0) ? $this->input->post('page_title') : $title;
            $page_description = (strlen($this->input->post('page_description')) > 0) ? $this->input->post('page_description') : ellipsize($description, 160);
            $page_keywords = $this->input->post('page_keywords');

            if ($id == 0)
            {
                $insert = array(
								'published_at'=>$published_at,
								'project_budget_id' => $project_budget_id,
								'project_work_mode_id' => $project_work_mode_id,
								);
                $id = $this->project_model->insert($insert);
            }

            $insert_translation = array(
								'project_id'=>$id,
								'title' => $name,
								'language_slug' => $language_slug,
								'description' => $description,
								'page_title' => $page_title,
								'page_description' => $page_description,
								'page_keywords' => $page_keywords
							);

            if($translation_id = $this->project_translation_model->insert($insert_translation))
            {
                $this->project_model->update(array('published_at'=>$published_at),$id);
            }
            redirect('admin/project/index/'.$content_type,'refresh');

        }


    }
	/* End of public function create($content_type = 'project', $language_slug = NULL, $id = 0) */



	/**
	* To edit project
	*
	* @Controller  project
	* @method    	edit
	* @params
	*				$language_slug (type:string,default:null)
	*				$id (type:integer,default:0)
	* @author      TBW
	* @created_at  Dec 12,2016
	* @purpose 	Its edit a project
	**/




    public function edit($language_slug, $id)
    {
        $content = $this->project_model->get($id);
        if($content == FALSE)
        {
            $this->postal->add('There is no content to translate.','error');
            redirect('admin/project/index', 'refresh');
        }
       
        $translation = $this->project_translation_model->where(array('project_id'=>$id, 'language_slug'=>$language_slug))->get();
        $this->data['content_language'] = $this->langs[$language_slug]['name'];
        if($translation == FALSE)
        {
            $this->postal->add('There is no translation for that content.','error');
            redirect('admin/project/index/'.$content_type, 'refresh');
        }

        $this->data['translation'] = $translation;
        $this->data['content'] = $content;
		$this->data['content_title'] = 'Project';

	    $this->data['budgets'] = $this->project_model->admin_get_budget_list();
		$this->data['modes'] = $this->project_model->admin_get_mode_list();	
        $rules = $this->project_model->rules;
        $this->form_validation->set_rules($rules['update']);
        if($this->form_validation->run()===FALSE)
        {
            $this->render('admin/project/edit_view');
        }
        else
        {
            $translation_id = $this->input->post('translation_id');
            if($translation = $this->project_translation_model->get($translation_id))
            {
                $name = $this->input->post('name');
                $id = $this->input->post('id');
                $published_at = $this->input->post('published_at');
                $language_slug = $this->input->post('language_slug');
				$project_budget_id = $this->input->post('project_budget_id');
				$project_work_mode_id = $this->input->post('project_work_mode_id');
				$description = $this->input->post('description');
				$page_title = (strlen($this->input->post('page_title')) > 0) ? $this->input->post('page_title') : $name;
                $page_description = (strlen($this->input->post('page_description')) > 0) ? $this->input->post('page_description') : ellipsize($description, 160);
                $page_keywords = $this->input->post('page_keywords');

                $update_translation = array(
                    'title' => $name,
					'description' => $description,
					'page_title' => $page_title,
                    'page_description' => $page_description,
                    'page_keywords' => $page_keywords
					);

                if ($this->project_translation_model->update($update_translation, $translation_id))
                {
					$update_content = array(
									'project_budget_id' => $project_budget_id,
									'project_work_mode_id' => $project_work_mode_id,
									);

                    $this->project_model->update($update_content, $id);
					// echo $this->db->last_query();exit;
					$this->postal->add('The translation was updated successfully.','success');
                }
            }
            else
            {
                $this->postal->add('There is no translation to update.','error');
            }
            redirect('admin/project/index/','refresh');
        }
    }
	/* End of public function edit($language_slug, $id) */



	/**
	* To publish a project
	*
	* @Controller  project
	* @method    	publish
	* @params
	*				$id (type:integer)
	*				$published (type:integer)
	* @author      TBW
	* @created_at  Dec 12,2016
	* @purpose 	Its edit a project
	**/
    public function publish($id, $published)
    {
	
        $content = $this->project_model->get($id);

        if( ($content != FALSE) && ($published==1 || $published==0))
        {
            if($this->project_model->update(array('published'=>$published),$id))
            {
				//echo $id;echo $this->db->last_query();exit;
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
        redirect('admin/project/index/','refresh');
    }
	/* End of public function publish($id, $published) */



	/**
	* To delete a project
	*
	* @Controller  project
	* @method    	delete
	* @params
	*				$language_slug (type:string)
	*				$id (type:integer)
	* @author      TBW
	* @created_at  Dec 12,2016
	* @purpose 	Its delete a project
	**/
    public function delete($language_slug, $id)
    {
        if($content = $this->project_model->get($id))
        {
            if($language_slug=='all')
            {
                if($deleted_translations = $this->project_translation_model->where('project_id',$id)->delete())
                {
                    $this->postal->add('Project deleted. There were also '.$deleted_translations.' translations','success');
                }
                else
                {
                    $deleted_pages = $this->project_model->delete($id);
                    $this->postal->add($deleted_pages.' page was deleted','success');
                }
            }
            else
            {
                $this->project_translation_model->where(array('project_id'=>$id,'language_slug'=>$language_slug))->delete();
            }
        }
        else
        {
            $this->postal->add('There is no translation to delete.','error');
        }
        redirect('admin/project/index/','refresh');

    }
	/* End of public function delete($language_slug, $id) */



	/**
	* To check a project should add only once
	*
	* @Controller  project
	* @method    	_unique_value
	* @params
	*				$name (type:string)
	* @author      TBW
	* @created_at  Dec 12,2016
	* @purpose 	Its check a entered project is already stored in databse or not.
	**/
	public function _unique_value()
	{
		$name = $this->input->post('name');
		$translation_id = ($this->input->post('translation_id'))?$this->input->post('translation_id'):0;
        $language_slug = (isset($language_slug) && array_key_exists($language_slug, $this->langs)) ? $language_slug : $this->current_lang;
		
		if($translation_id>0){
			$where =array('id!='=>$translation_id,'title'=>$name,'language_slug'=>$language_slug);
					
		}else{
			$where =array('title'=>$name,'language_slug'=>$language_slug);
		}
		$return_value = $this->project_translation_model->where($where)->get();
		
		$this->db->last_query();
        if ($return_value)
        {
            $this->form_validation->set_message('_unique_value', 'This project already exist. Please add another one.');
            return FALSE;
        }
        else
        {
            return TRUE;
        }		
	}
	/* End of public function _unique_value($name) */
	

		public function featured($project_id)
		{


				$this->data['upload_errors'] = '';
				$content_type='project';
				$content = $this->project_model->get($project_id);
				if($content === FALSE)
				{
						$this->postal->add('There is no content with that ID','error');
						redirect('admin/project/index/');
				}
				$this->data['content_type'] = $content_type;
				$this->data['content'] = $content;
				$rules = $this->project_model->rules;
				$this->form_validation->set_rules($rules['insert_featured']);
				if($this->form_validation->run()===FALSE)
				{
						$this->render('admin/project/upload_featured_view');
				}
				else
				{
						$config = array(
								'upload_path' => './uploads/',
								'allowed_types' => 'jpg|gif|png',
								'max_size' => '2048',
								'multi' => 'all'
						);
						$this->load->library('upload',$config);
						if(!$this->upload->do_upload('featured_image'))
						{
								$this->data['upload_errors'] = $this->upload->display_errors();
								$this->render('admin/project/upload_featured_view');
						}
						else
						{
								$content_id = $this->input->post('content_id');
								$content = $this->project_model->get($content_id);
								$image_data = $this->upload->data();
								$this->load->library('image_nation');
								$this->image_nation->source($image_data['file_name']);
								$this->image_nation->clear_sizes();
								$dimensions = array(
										$this->featured_image => array(
												'master_dim'    =>  'width',
												'keep_aspect_ratio' => FALSE,
												'style'         =>  array('vertical'=>'center','horizontal'=>'center'),
												'overwrite'     =>  FALSE,
												'quality'       =>  '70%'
										)
								);
								$file_name = url_title($this->input->post('file_name'),'-',TRUE);
								if(strlen($file_name)>0) $dimensions['400x350']['file_name'] = $file_name;
								$this->image_nation->add_size($dimensions);
								$this->image_nation->process();
								if(!$this->image_nation->get_errors())
								{
										$processed_image = $this->image_nation->get_processed();
										//print_r($processed_image);
										if($this->project_model->update(array('featured_image'=>$processed_image[0][$this->featured_image]['file_name']),$content->id))
										{
												$this->postal->add('The featured image was successfully uploaded.','success');
										}
										redirect('admin/project/index/'.$content_type);
								}
								else
								{
										print_r($this->image_nation->get_errors());
								}
						}
				}

		}

		public function delete_featured($project_id)
		{
				$content_type='project';
				$content = $this->project_model->get($project_id);
				if($content===FALSE)
				{
						$this->postal->add('There is no content there.','error');
						redirect('admin');
				}
				else
				{
						$id = $content->id;
						$file_name = $content->featured_image;
						@unlink(FCPATH.'media/'.$this->featured_image.'/'.$file_name);
						if($this->project_model->update(array('featured_image'=>''),$id))
						{
								$this->postal->add('The featured image was removed.','success');
								redirect('admin/project/index/'.$content_type);
						}

				}
		}


	public function view($language_slug, $id)
    {
        $content = $this->project_model->admin_get_project_details($id);
		$this->data['translation'] = $content[$id];
		$this->render('admin/project/details_view');

	}


}