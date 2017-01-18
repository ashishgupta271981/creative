<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Languages Controller
 *
 * @package     CFI
 * @subpackage  admin
 * @category    Master
 * @author      Amitesh Jain
 * @created_at  Dec 20,2016
 *
 */
class Tags extends Admin_Controller
{
	/**
	 * Construction function
	 *
	 * @Controller  Tags
	 * @method    	__construct
	 * @author      Amitesh Jain
	 * @created_at  Dec 20,2016
	 * @purpose 	Check the Admin user Authentication and load the the tag model, tag transaltion model, form validation library and language model
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
		/* Load the tag model, tag transaltion model,form validation library and language model */
        $this->load->model('tag_model');
        $this->load->model('tag_translation_model');
        $this->load->model('language_model');
        $this->load->library('form_validation');
	}
	/* End of function __construct() */



	 /**
 	 * To lists the Tags
 	 *
 	 * @Controller  Tags
 	 * @method    	index [default method]
 	 * @author      Amitesh Jain
 	 * @created_at  Dec 20,2016
 	 * @purpose 	Its shows the list of Tags with add new tag button
 	 **/
	public function index()
	{
        $list_tag = $this->tag_model->get_tag_list();

        $this->data['tags'] = $list_tag;
        $this->render('admin/tag/index_view');
	}
	/* End of function index() */


	/**
	* To create new tag
	*
	* @Controller  Tags
	* @method    	create
	* @params
	*				$content_type (type:string,default:tag)
	*				$language_slug (type:string,default:null)
	*				$tag_id (type:integer,default:0)
	* @author      Amitesh Jain
	* @created_at  Dec 20,2016
	* @purpose 	Its create new tag
	**/
    public function create($language_slug = NULL, $tag_id = 0)
    {
        $language_slug = (isset($language_slug) && array_key_exists($language_slug, $this->langs)) ? $language_slug : $this->current_lang;

        //$this->data['content_type'] = $content_type;
        $this->data['content_language'] = $this->langs[$language_slug]['name'];
        $this->data['language_slug'] = $language_slug;
        $content = $this->tag_model->get($tag_id);
        if($tag_id != 0 && $content==FALSE)
        {
            $tag_id = 0;
        }
        if($this->tag_translation_model->where(array('tag_id'=>$tag_id,'language_slug'=>$language_slug))->get())
        {
            $this->postal->add('A translation for that content already exists.','error');
            redirect('admin/tags/index/', 'refresh');
        }
        //$this->data['content'] = $content;
        $this->data['tag_id'] = $tag_id;

        $rules = $this->tag_model->rules;
        $this->form_validation->set_rules($rules['insert']);
        if($this->form_validation->run()===FALSE)
        {
            $this->render('admin/tag/create_view');
        }
        else
        {
            //$content_type = $this->input->post('content_type');
            $name = $this->input->post('name');
            $tag_id = $this->input->post('tag_id');
            $language_slug = $this->input->post('language_slug');
            $published_at = $this->input->post('published_at');
            if ($tag_id == 0)
            {
                $insert_tag = array('published_at'=>$published_at);
                $tag_id = $this->tag_model->insert($insert_tag);
            }

            $insert_translation = array('tag_id'=>$tag_id,'name' => $name,'language_slug' => $language_slug);

            if($translation_id = $this->tag_translation_model->insert($insert_translation))
            {
                $this->tag_model->update(array('published_at'=>$published_at),$tag_id);
            }
            redirect('admin/tags/index/','refresh');

        }


    }
	/* End of public function create($content_type = 'tag', $language_slug = NULL, $tag_id = 0) */



	/**
	* To edit tag
	*
	* @Controller  Tags
	* @method    	edit
	* @params
	*				$language_slug (type:string,default:null)
	*				$tag_id (type:integer,default:0)
	* @author      Amitesh Jain
	* @created_at  Dec 20,2016
	* @purpose 	Its edit a tag
	**/
    public function edit($language_slug, $tag_id)
    {
        $content = $this->tag_model->get($tag_id);
        if($content == FALSE)
        {
            $this->postal->add('There is no content to translate.','error');
            redirect('admin/tags/index', 'refresh');
        }
       // $content_type = $content->content_type;
        $translation = $this->tag_translation_model->where(array('tag_id'=>$tag_id, 'language_slug'=>$language_slug))->get();
        $this->data['content_language'] = $this->langs[$language_slug]['name'];
        if($translation == FALSE)
        {
            $this->postal->add('There is no translation for that content.','error');
            redirect('admin/tags/index/', 'refresh');
        }

        $this->data['translation'] = $translation;
        $this->data['content'] = $content;
        $rules = $this->tag_model->rules;
        $this->form_validation->set_rules($rules['update']);
        if($this->form_validation->run()===FALSE)
        {
            $this->render('admin/tag/edit_view');
        }
        else
        {
            $translation_id = $this->input->post('translation_id');
            if($translation = $this->tag_translation_model->get($translation_id))
            {
                $tag_name = $this->input->post('name');
                $tag_id = $this->input->post('tag_id');
                $published_at = $this->input->post('published_at');
                $language_slug = $this->input->post('language_slug');

                $update_translation = array(
                    'name' => $tag_name);

                if ($this->tag_translation_model->update($update_translation, $translation_id))
                {

                    //$this->tag_model->update($update_content, $content_id);
                    $this->postal->add('The translation was updated successfully.','success');
                }
            }
            else
            {
                $this->postal->add('There is no translation to update.','error');
            }
            redirect('admin/tags/index/','refresh');
        }
    }
	/* End of public function edit($language_slug, $tag_id) */



	/**
	* To publish a tag
	*
	* @Controller  Tags
	* @method    	publish
	* @params
	*				$tag_id (type:integer)
	*				$published (type:integer)
	* @author      Amitesh Jain
	* @created_at  Dec 20,2016
	* @purpose 	Its edit a tag
	**/
    public function publish($tag_id, $published)
    {
        $content = $this->tag_model->get($tag_id);
        if( ($content != FALSE) && ($published==1 || $published==0))
        {
            if($this->tag_model->update(array('published'=>$published),$tag_id))
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
        redirect('admin/tags/index/','refresh');
    }
	/* End of public function publish($tag_id, $published) */



	/**
	* To delete a tag
	*
	* @Controller  Tags
	* @method    	delete
	* @params
	*				$language_slug (type:string)
	*				$tag_id (type:integer)
	* @author      Amitesh Jain
	* @created_at  Dec 20,2016
	* @purpose 	Its delete a tag
	**/
    public function delete($language_slug, $tag_id)
    {
        if($content = $this->tag_model->get($tag_id))
        {
            if($language_slug=='all')
            {
                if($deleted_translations = $this->tag_translation_model->where('tag_id',$tag_id)->delete())
                {
                    $this->postal->add('Tag deleted. There were also '.$deleted_translations.' translations','success');
                }
                else
                {
                    $deleted_pages = $this->tag_model->delete($tag_id);
                    $this->postal->add($deleted_pages.' page was deleted','success');
                }
            }
            else
            {
                $this->tag_translation_model->where(array('tag_id'=>$tag_id,'language_slug'=>$language_slug))->delete();
            }
        }
        else
        {
            $this->postal->add('There is no translation to delete.','error');
        }
        redirect('admin/tags/index/','refresh');

    }
	/* End of public function delete($language_slug, $tag_id) */



	/**
	* To check a tag should add only once
	*
	* @Controller  Tags
	* @method    	_unique_tag
	* @params
	*				$tag_name (type:string)
	* @author      Amitesh Jain
	* @created_at  Dec 20,2016
	* @purpose 	Its check a entered tag is already stored in databse or not.
	**/
	public function _unique_tag($tag_name)
	{
        $language_slug = (isset($language_slug) && array_key_exists($language_slug, $this->langs)) ? $language_slug : $this->current_lang;
		$return_value = $this->tag_translation_model->where(array('name'=>$tag_name,'language_slug'=>$language_slug))->get();
		$this->db->last_query();
        if ($return_value)
        {
            $this->form_validation->set_message('_unique_tag', 'This tag already exist. Please add another one.');
            return FALSE;
        }
        else
        {
            return TRUE;
        }
	}
	/* End of public function _unique_tag($tag_name) */

}
