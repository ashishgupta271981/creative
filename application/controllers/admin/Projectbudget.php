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
class Projectbudget extends Admin_Controller
{
	/**
	 * Construction function
	 *
	 * @Controller  projectbudget
	 * @method    	__construct
	 * @author      projectbudget
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
        $this->load->model('projectbudget_model');
        $this->load->model('projectbudget_translation_model');
        $this->load->model('language_model');
        $this->load->library('form_validation');
	}
	/* End of function __construct() */



	 /**
 	 * To lists the budget
 	 *
 	 * @Controller  Mode
 	 * @method    	index [default method]
 	 * @author      TBW
 	 * @created_at  Dec 12,2016
 	 * @purpose 	Its shows the list of budget with add new budget button
 	 **/
	public function index()
	{
        $list_projectbudget = $this->projectbudget_model->get_projectbudget_list();
        $this->data['content_type'] = 'projectbudget';
		$this->data['content_title'] = 'Project Budget';
        $this->data['projectbudget'] = $list_projectbudget;
        $this->render('admin/projectbudget/index_view');
	}
	/* End of function index() */


	/**
	* To create new budget
	*
	* @Controller  budget
	* @method    	create
	* @params
	*				$content_type (type:string,default:budget)
	*				$language_slug (type:string,default:null)
	*				$id (type:integer,default:0)
	* @author      TBW
	* @created_at  Dec 12,2016
	* @purpose 	Its create new budget
	**/
    public function create($content_type = 'projectbudget', $language_slug = NULL, $id = 0)
    {
        $language_slug = (isset($language_slug) && array_key_exists($language_slug, $this->langs)) ? $language_slug : $this->current_lang;

        $this->data['content_type'] = $content_type;
		$this->data['content_title'] = 'Project Budget';
        $this->data['content_language'] = $this->langs[$language_slug]['name'];
        $this->data['language_slug'] = $language_slug;
        $content = $this->projectbudget_model->get($id);
        if($id != 0 && $content==FALSE)
        {
            $id = 0;
        }
        if($this->projectbudget_translation_model->where(array('project_budget_id'=>$id,'language_slug'=>$language_slug))->get())
        {
		
            $this->postal->add('A translation for that content already exists.','error');
            redirect('admin/projectbudget/index/'.$content_type, 'refresh');
        }
        $this->data['content'] = $content;
        $this->data['id'] = $id;

        $rules = $this->projectbudget_model->rules;
        $this->form_validation->set_rules($rules['insert']);
        if($this->form_validation->run()===FALSE)
        {
            $this->render('admin/projectbudget/create_view');
        }
        else
        {  
            $content_type = $this->input->post('content_type');
            $name = $this->input->post('name');
            $id = $this->input->post('id');
            $language_slug = $this->input->post('language_slug');
            $published_at = $this->input->post('published_at');
            if ($id == 0)
            {
                $insert = array('published_at'=>$published_at);
                $id = $this->projectbudget_model->insert($insert);
            }

            $insert_translation = array('project_budget_id'=>$id,'budget' => $name,'language_slug' => $language_slug);

            if($translation_id = $this->projectbudget_translation_model->insert($insert_translation))
            {
                $this->projectbudget_model->update(array('published_at'=>$published_at),$id);
            }
            redirect('admin/projectbudget/index/'.$content_type,'refresh');

        }


    }
	/* End of public function create($content_type = 'budget', $language_slug = NULL, $id = 0) */



	/**
	* To edit budget
	*
	* @Controller  budget
	* @method    	edit
	* @params
	*				$language_slug (type:string,default:null)
	*				$id (type:integer,default:0)
	* @author      TBW
	* @created_at  Dec 12,2016
	* @purpose 	Its edit a budget
	**/
    public function edit($language_slug, $id)
    {
        $content = $this->projectbudget_model->get($id);
        if($content == FALSE)
        {
            $this->postal->add('There is no content to translate.','error');
            redirect('admin/projectbudget/index', 'refresh');
        }
       
        $translation = $this->projectbudget_translation_model->where(array('project_budget_id'=>$id, 'language_slug'=>$language_slug))->get();
        $this->data['content_language'] = $this->langs[$language_slug]['name'];
        if($translation == FALSE)
        {
            $this->postal->add('There is no translation for that content.','error');
            redirect('admin/projectbudget/index/'.$content_type, 'refresh');
        }

        $this->data['translation'] = $translation;
        $this->data['content'] = $content;
		$this->data['content_title'] = 'Project Budget';
        $rules = $this->projectbudget_model->rules;
        $this->form_validation->set_rules($rules['update']);
        if($this->form_validation->run()===FALSE)
        {
            $this->render('admin/projectbudget/edit_view');
        }
        else
        {
            $translation_id = $this->input->post('translation_id');
            if($translation = $this->projectbudget_translation_model->get($translation_id))
            {
                $name = $this->input->post('name');
                $id = $this->input->post('id');
                $published_at = $this->input->post('published_at');
                $language_slug = $this->input->post('language_slug');

                $update_translation = array(
                    'budget' => $name);

                if ($this->projectbudget_translation_model->update($update_translation, $translation_id))
                {
					$this->postal->add('The translation was updated successfully.','success');
                }
            }
            else
            {
                $this->postal->add('There is no translation to update.','error');
            }
            redirect('admin/projectbudget/index/','refresh');
        }
    }
	/* End of public function edit($language_slug, $id) */



	/**
	* To publish a budget
	*
	* @Controller  budget
	* @method    	publish
	* @params
	*				$id (type:integer)
	*				$published (type:integer)
	* @author      TBW
	* @created_at  Dec 12,2016
	* @purpose 	Its edit a budget
	**/
    public function publish($id, $published)
    {
        $content = $this->projectbudget_model->get($id);
        if( ($content != FALSE) && ($published==1 || $published==0))
        {
            if($this->projectbudget_model->update(array('published'=>$published),$id))
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
        redirect('admin/projectbudget/index/','refresh');
    }
	/* End of public function publish($id, $published) */



	/**
	* To delete a budget
	*
	* @Controller  budget
	* @method    	delete
	* @params
	*				$language_slug (type:string)
	*				$id (type:integer)
	* @author      TBW
	* @created_at  Dec 12,2016
	* @purpose 	Its delete a budget
	**/
    public function delete($language_slug, $id)
    {
        if($content = $this->projectbudget_model->get($id))
        {
            if($language_slug=='all')
            {
                if($deleted_translations = $this->projectbudget_translation_model->where('project_budget_id',$id)->delete())
                {
                    $this->postal->add('Project Budget deleted. There were also '.$deleted_translations.' translations','success');
                }
                else
                {
                    $deleted_pages = $this->projectbudget_model->delete($id);
                    $this->postal->add($deleted_pages.' page was deleted','success');
                }
            }
            else
            {
                $this->projectbudget_translation_model->where(array('project_budget_id'=>$id,'language_slug'=>$language_slug))->delete();
            }
        }
        else
        {
            $this->postal->add('There is no translation to delete.','error');
        }
        redirect('admin/projectbudget/index/','refresh');

    }
	/* End of public function delete($language_slug, $id) */



	/**
	* To check a budget should add only once
	*
	* @Controller  budget
	* @method    	_unique_value
	* @params
	*				$name (type:string)
	* @author      TBW
	* @created_at  Dec 12,2016
	* @purpose 	Its check a entered budget is already stored in databse or not.
	**/
	public function _unique_value($name)
	{
        $language_slug = (isset($language_slug) && array_key_exists($language_slug, $this->langs)) ? $language_slug : $this->current_lang;
		$return_value = $this->projectbudget_translation_model->where(array('budget'=>$name,'language_slug'=>$language_slug))->get();
		$this->db->last_query();
        if ($return_value)
        {
            $this->form_validation->set_message('_unique_value', 'This project Budget already exist. Please add another one.');
            return FALSE;
        }
        else
        {
            return TRUE;
        }		
	}
	/* End of public function _unique_value($name) */
	
}