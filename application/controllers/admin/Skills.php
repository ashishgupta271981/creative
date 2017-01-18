<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Languages Controller
 *
 * @package     CFI
 * @subpackage  admin
 * @category    Master
 * @author      Sandeep Panwar
 * @created_at  Dec 12,2016
 *
 */
class Skills extends Admin_Controller
{
	/**
	 * Construction function
	 *
	 * @Controller  Skills
	 * @method    	__construct
	 * @author      Sandeep Panwar
	 * @created_at  Dec 12,2016
	 * @purpose 	Check the Admin user Authentication and load the the skill model, skill transaltion model, form validation library and language model
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
		/* Load the skill model, skill transaltion model,form validation library and language model */
        $this->load->model('skill_model');
        $this->load->model('skill_translation_model');
        $this->load->model('language_model');
        $this->load->library('form_validation');
	}
	/* End of function __construct() */



	 /**
 	 * To lists the skills
 	 *
 	 * @Controller  Skills
 	 * @method    	index [default method]
 	 * @author      Sandeep Panwar
 	 * @created_at  Dec 12,2016
 	 * @purpose 	Its shows the list of skills with add new Skill button
 	 **/
	public function index()
	{
        $list_skills = $this->skill_model->get_skill_list();
        $this->data['content_type'] = 'skill';
        $this->data['skills'] = $list_skills;
        $this->render('admin/skills/index_view');
	}
	/* End of function index() */


	/**
	* To create new skill
	*
	* @Controller  Skills
	* @method    	create
	* @params
	*				$content_type (type:string,default:skill)
	*				$language_slug (type:string,default:null)
	*				$skill_id (type:integer,default:0)
	* @author      Sandeep Panwar
	* @created_at  Dec 12,2016
	* @purpose 	Its create new skill
	**/
    public function create($content_type = 'skill', $language_slug = NULL, $skill_id = 0)
    {
        $language_slug = (isset($language_slug) && array_key_exists($language_slug, $this->langs)) ? $language_slug : $this->current_lang;

        $this->data['content_type'] = $content_type;
        $this->data['content_language'] = $this->langs[$language_slug]['name'];
        $this->data['language_slug'] = $language_slug;
        $content = $this->skill_model->get($skill_id);
        if($skill_id != 0 && $content==FALSE)
        {
            $skill_id = 0;
        }
        if($this->skill_translation_model->where(array('skill_id'=>$skill_id,'language_slug'=>$language_slug))->get())
        {
            $this->postal->add('A translation for that content already exists.','error');
            redirect('admin/skills/index/'.$content_type, 'refresh');
        }
        $this->data['content'] = $content;
        $this->data['skill_id'] = $skill_id;

        $rules = $this->skill_model->rules;
        $this->form_validation->set_rules($rules['insert']);
        if($this->form_validation->run()===FALSE)
        {
            $this->render('admin/skills/create_view');
        }
        else
        {  
            $content_type = $this->input->post('content_type');
            $name = $this->input->post('name');
            $skill_id = $this->input->post('skill_id');
            $language_slug = $this->input->post('language_slug');
            $published_at = $this->input->post('published_at');
            if ($skill_id == 0)
            {
                $insert_skill = array('published_at'=>$published_at);
                $skill_id = $this->skill_model->insert($insert_skill);
            }

            $insert_translation = array('skill_id'=>$skill_id,'name' => $name,'language_slug' => $language_slug);

            if($translation_id = $this->skill_translation_model->insert($insert_translation))
            {
                $this->skill_model->update(array('published_at'=>$published_at),$skill_id);
            }
            redirect('admin/skills/index/'.$content_type,'refresh');

        }


    }
	/* End of public function create($content_type = 'skill', $language_slug = NULL, $skill_id = 0) */



	/**
	* To edit skill
	*
	* @Controller  Skills
	* @method    	edit
	* @params
	*				$language_slug (type:string,default:null)
	*				$skill_id (type:integer,default:0)
	* @author      Sandeep Panwar
	* @created_at  Dec 12,2016
	* @purpose 	Its edit a skill
	**/
    public function edit($language_slug, $skill_id)
    {
        $content = $this->skill_model->get($skill_id);
        if($content == FALSE)
        {
            $this->postal->add('There is no content to translate.','error');
            redirect('admin/skills/index', 'refresh');
        }
       // $content_type = $content->content_type;
        $translation = $this->skill_translation_model->where(array('skill_id'=>$skill_id, 'language_slug'=>$language_slug))->get();
        $this->data['content_language'] = $this->langs[$language_slug]['name'];
        if($translation == FALSE)
        {
            $this->postal->add('There is no translation for that content.','error');
            redirect('admin/skills/index/'.$content_type, 'refresh');
        }

        $this->data['translation'] = $translation;
        $this->data['content'] = $content;
        $rules = $this->skill_model->rules;
        $this->form_validation->set_rules($rules['update']);
        if($this->form_validation->run()===FALSE)
        {
            $this->render('admin/skills/edit_view');
        }
        else
        {
            $translation_id = $this->input->post('translation_id');
            if($translation = $this->skill_translation_model->get($translation_id))
            {
                $skill_name = $this->input->post('name');
                $skill_id = $this->input->post('skill_id');
                $published_at = $this->input->post('published_at');
                $language_slug = $this->input->post('language_slug');

                $update_translation = array(
                    'name' => $skill_name);

                if ($this->skill_translation_model->update($update_translation, $translation_id))
                {

                    //$this->skill_model->update($update_content, $content_id);
                    $this->postal->add('The translation was updated successfully.','success');
                }
            }
            else
            {
                $this->postal->add('There is no translation to update.','error');
            }
            redirect('admin/skills/index/','refresh');
        }
    }
	/* End of public function edit($language_slug, $skill_id) */



	/**
	* To publish a skill
	*
	* @Controller  Skills
	* @method    	publish
	* @params
	*				$skill_id (type:integer)
	*				$published (type:integer)
	* @author      Sandeep Panwar
	* @created_at  Dec 12,2016
	* @purpose 	Its edit a skill
	**/
    public function publish($skill_id, $published)
    {
        $content = $this->skill_model->get($skill_id);
        if( ($content != FALSE) && ($published==1 || $published==0))
        {
            if($this->skill_model->update(array('published'=>$published),$skill_id))
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
        redirect('admin/skills/index/','refresh');
    }
	/* End of public function publish($skill_id, $published) */



	/**
	* To delete a skill
	*
	* @Controller  Skills
	* @method    	delete
	* @params
	*				$language_slug (type:string)
	*				$skill_id (type:integer)
	* @author      Sandeep Panwar
	* @created_at  Dec 12,2016
	* @purpose 	Its delete a skill
	**/
    public function delete($language_slug, $skill_id)
    {
        if($content = $this->skill_model->get($skill_id))
        {
            if($language_slug=='all')
            {
                if($deleted_translations = $this->skill_translation_model->where('skill_id',$skill_id)->delete())
                {
                    $this->postal->add('Skill deleted. There were also '.$deleted_translations.' translations','success');
                }
                else
                {
                    $deleted_pages = $this->skill_model->delete($skill_id);
                    $this->postal->add($deleted_pages.' page was deleted','success');
                }
            }
            else
            {
                $this->skill_translation_model->where(array('skill_id'=>$skill_id,'language_slug'=>$language_slug))->delete();
            }
        }
        else
        {
            $this->postal->add('There is no translation to delete.','error');
        }
        redirect('admin/skills/index/','refresh');

    }
	/* End of public function delete($language_slug, $skill_id) */



	/**
	* To check a skill should add only once
	*
	* @Controller  Skills
	* @method    	_unique_skill
	* @params
	*				$skill_name (type:string)
	* @author      Sandeep Panwar
	* @created_at  Dec 12,2016
	* @purpose 	Its check a entered skill is already stored in databse or not.
	**/
	public function _unique_skill($skill_name)
	{
        $language_slug = (isset($language_slug) && array_key_exists($language_slug, $this->langs)) ? $language_slug : $this->current_lang;
		$return_value = $this->skill_translation_model->where(array('name'=>$skill_name,'language_slug'=>$language_slug))->get();
		$this->db->last_query();
        if ($return_value)
        {
            $this->form_validation->set_message('_unique_skill', 'This skill already exist. Please add another one.');
            return FALSE;
        }
        else
        {
            return TRUE;
        }		
	}
	/* End of public function _unique_skill($skill_name) */
	
}