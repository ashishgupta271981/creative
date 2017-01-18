<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Country extends Admin_Controller
{

	function __construct()
	{
		parent::__construct();
        if(!$this->ion_auth->in_group('admin'))
        {
            $this->postal->add('You are not allowed to visit the Contents page','error');
            redirect('admin','refresh');
        }
        $this->load->model('country_model');
        $this->load->model('country_translation_model');
      //  $this->load->model('slug_model');
        $this->load->model('language_model');
        $this->load->library('form_validation');
        $this->load->helper('text');
	}

	public function index()
	{
        $list_content = $this->country_model->get_country_list();
      //  $this->data['content_type'] = $content_type;
        $this->data['contents'] = $list_content;
        $this->render('admin/country/index_view');
	}

    public function create($language_slug = NULL, $country_id = 0)
    {
        $language_slug = (isset($language_slug) && array_key_exists($language_slug, $this->langs)) ? $language_slug : $this->current_lang;

        //$this->data['content_type'] = $content_type;
				$this->data['country_id'] = $country_id;
        $this->data['content_language'] = $this->langs[$language_slug]['name'];
        $this->data['language_slug'] = $language_slug;
        $country = $this->country_model->get($country_id);
        if($country_id != 0 && $country==FALSE)
        {
            $content_id = 0;
        }
        if($this->country_translation_model->where(array('country_id'=>$country_id,'language_slug'=>$language_slug))->get())
        {
            $this->postal->add('A translation for that content already exists.','error');
            redirect('admin/country/index/', 'refresh');
        }
        $this->data['country'] = $country;
        $this->data['country_id'] = $country_id;
        //$this->data['parents'] = $this->content_model->get_parents_list($content_type,$content_id,$language_slug);

        $rules = $this->country_model->rules;
        $this->form_validation->set_rules($rules['insert']);
        if($this->form_validation->run()===FALSE)
        {
            $this->render('admin/country/create_view');
        }
        else
        {
          //  $content_type = $this->input->post('content_type');
          //  $parent_id = $this->input->post('parent_id');
            $name = $this->input->post('name');
            $sort_name = (strlen($this->input->post('sort_name')) > 0) ? $this->input->post('sort_name') : $title;
            //$slug = (strlen($this->input->post('slug')) > 0) ? url_title($this->input->post('slug'),'-',TRUE) : url_title(convert_accented_characters($title),'-',TRUE);
            //$order = $this->input->post('order');
            //$content = $this->input->post('content');
            //$teaser = (strlen($this->input->post('teaser')) > 0) ? $this->input->post('teaser') : substr($content, 0, strpos($content, '<!--more-->'));
            //if($teaser == 0) $teaser = '';
            //$page_title = (strlen($this->input->post('page_title')) > 0) ? $this->input->post('page_title') : $title;
            //$page_description = (strlen($this->input->post('page_description')) > 0) ? $this->input->post('page_description') : ellipsize($teaser, 160);
            //$page_keywords = $this->input->post('page_keywords');
            //$content_id = $this->input->post('content_id');
            $language_slug = $this->input->post('language_slug');
            $published_at = $this->input->post('published_at');
            if ($country_id == 0)
            {
                $insert_country = array('published_at'=>$published_at);
                $country_id = $this->country_model->insert($insert_country);
            }

            $insert_translation = array('country_id'=>$country_id,'name' => $name, 'sort_name' => $sort_name,'language_slug' => $language_slug);

            if($translation_id = $this->country_translation_model->insert($insert_translation))
            {
                $this->country_model->update(array('published_at'=>$published_at),$country_id);

            }

            redirect('admin/country/index/','refresh');

        }


    }

    public function edit($language_slug, $country_id)
    {
        $content = $this->country_model->get($country_id);
        if($content == FALSE)
        {
            $this->postal->add('There is no content to translate.','error');
            redirect('admin/country/index', 'refresh');
        }
        //$content_type = $content->content_type;
        $translation = $this->country_translation_model->where(array('country_id'=>$country_id, 'language_slug'=>$language_slug))->get();
        $this->data['content_language'] = $this->langs[$language_slug]['name'];
        if($translation == FALSE)
        {
            $this->postal->add('There is no translation for that content.','error');
            redirect('admin/country/index/', 'refresh');
        }

    //    $this->load->model('image_model');
    //    $images = $this->image_model->where('content_id',$content_id)->get_all();
      //  if($images!== FALSE)
      //  {
        //    $this->data['uploaded_images'] = $images;
      //  }

        $this->data['translation'] = $translation;
        //$this->data['parents'] = $this->content_model->get_parents_list($content_type,$content_id,$language_slug);
        $this->data['country'] = $content;
      //  $this->data['slugs'] = $this->slug_model->where(array('translation_id'=>$translation->id))->get_all();
        $rules = $this->country_model->rules;
        $this->form_validation->set_rules($rules['update']);
        if($this->form_validation->run()===FALSE)
        {
            $this->render('admin/country/edit_view');
        }
        else
        {
            $country_id = $this->input->post('country_id');
            if($translation = $this->country_translation_model->get($country_id))
            {
              //  $parent_id = $this->input->post('parent_id');
              //  $content_type = $this->input->post('content_type');
                $name = $this->input->post('name');
                $sort_name = $this->input->post('sort_name');
            //    $slug = url_title(convert_accented_characters($this->input->post('slug')),'-',TRUE);
              //  $order = $this->input->post('order');
            //    $content = $this->input->post('content');
              //  $teaser = (strlen($this->input->post('teaser')) > 0) ? $this->input->post('teaser') : substr($content, 0, strpos($content, '<!--more-->'));
              //  $page_title = (strlen($this->input->post('page_title')) > 0) ? $this->input->post('page_title') : $title;
              //  $page_description = (strlen($this->input->post('page_description')) > 0) ? $this->input->post('page_description') : ellipsize($teaser, 160);
              //  $page_keywords = $this->input->post('page_keywords');
                $country_id = $this->input->post('country_id');
              //  $published_at = $this->input->post('published_at');
                $language_slug = $this->input->post('language_slug');

                $update_country = array(
                    'name' => $name,
                    'sort_name' => $sort_name);
                    //'teaser' => $teaser,
                    //'content' => $content,
                    //'page_title' => $page_title,
                    //'page_description' => $page_description,
                    //'page_keywords' => $page_keywords);

                if ($this->country_translation_model->update($update_country, $country_id))
                {
                  //  $update_country = array('parent_id' => $parent_id, 'published_at' => $published_at, 'order' => $order);

                    $this->country_model->update($update_country, $country_id);

                    $this->postal->add('The translation was updated successfully.','success');
                }
            }
            else
            {
                $this->postal->add('There is no translation to update.','error');
            }
            redirect('admin/country/index/','refresh');
        }
    }
    public function publish($country_id, $published)
    {
        $content = $this->country_model->get($country_id);
        if( ($content != FALSE) && ($published==1 || $published==0))
        {
            if($this->country_model->update(array('published'=>$published),$country_id))
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
        redirect('admin/country/index/'.$content->content_id,'refresh');
    }

    public function delete($language_slug, $country_id)
    {
        if($country = $this->country_model->get($country_id))
        {
            if($language_slug=='all')
            {
                if($deleted_translations = $this->country_translation_model->where('country_id',$country_id)->delete())
                {
                   // $deleted_slugs = $this->slug_model->where(array('content_type'=>$content->content_type,'content_id'=>$content_id))->delete();

                    //$deleted_images = 0;
                    //$this->load->model('image_model');
                    //$images = $this->image_model->where(array('content_type'=>$content->content_type,'content_id'=>$content_id))->get_all();
                    //if(!empty($images))
                    //{
                      //  foreach($images as $image)
                        //{
                          //  @unlink(FCPATH.'media/'.$image->file);
                        //}
                        //$deleted_images = $this->image_model->where(array('content_type'=>$content->content_type,'content_id'=>$content_id))->delete();
                   // }

                   // $this->load->model('keyword_model');
                   // $deleted_keywords = $this->keyword_model->where(array('content_type'=>$content->content_type,'content_id'=>$content_id))->delete();

                   // $this->load->model('keyphrase_model');
                   // $deleted_keyphrases = $this->keyphrase_model->where(array('content_type'=>$content->content_type,'content_id'=>$content_id))->delete();

                    //$deleted_pages = $this->content_model->delete($content_id);

                    //$this->postal->add($deleted_pages.' page deleted. There were also '.$deleted_translations.' translations, '.$deleted_keywords.' keywords, '.$deleted_keyphrases.' key phrases, '.$deleted_slugs.' slugs and '.$deleted_images.' images deleted.','success');
                }
                else
                {
                    $deleted_pages = $this->country_model->delete($country_id);
                    $this->postal->add($deleted_pages.' page was deleted','success');
                }
                //@unlink(FCPATH.'media/'.$this->featured_image.'/'.$content->featured_image);
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
        redirect('admin/country/index/'.$content->content_type,'refresh');

    }
}
