<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Blogs extends Admin_Controller
{

	function __construct()
	{
		parent::__construct();
        if(!$this->ion_auth->in_group('admin'))
        {
            $this->postal->add('You are not allowed to visit the Contents page','error');
            redirect('admin','refresh');
        }
        $this->load->model('blog_model');
        $this->load->model('blog_translation_model');
        $this->load->model('slug_model');
        $this->load->model('language_model');
        $this->load->library('form_validation');
        $this->load->helper('text');
		$this->featured_image = $this->config->item('cms_featured_image');
	}

	public function index($content_type = 'blog')
	{
        $list_content = $this->blog_model->get_blogs_list();
        $this->data['content_type'] = $content_type;
        $this->data['contents'] = $list_content;
        $this->render('admin/blogs/index_view');
	}

    public function create($content_type = 'blog', $language_slug = NULL, $content_id = 0)
    {
        $language_slug = (isset($language_slug) && array_key_exists($language_slug, $this->langs)) ? $language_slug : $this->current_lang;

        $this->data['content_type'] = $content_type;
        $this->data['content_language'] = $this->langs[$language_slug]['name'];
        $this->data['language_slug'] = $language_slug;
        $content = $this->blog_model->get($content_id);
        if($content_id != 0 && $content==FALSE)
        {
            $content_id = 0;
        }
        if($this->blog_translation_model->where(array('blog_id'=>$content_id,'language_slug'=>$language_slug))->get())
        {
            $this->postal->add('A translation for that content already exists.','error');
            redirect('admin/blogs/index/'.$content_type, 'refresh');
        }
        $this->data['content'] = $content;
        $this->data['content_id'] = $content_id;
        $this->data['parents'] = $this->blog_model->get_category_list($content_type,$content_id,$language_slug);

        $rules = $this->blog_model->rules;
        $this->form_validation->set_rules($rules['insert']);
        if($this->form_validation->run()===FALSE)
        {
            $this->render('admin/blogs/create_view');
        }
        else
        {
			//print_r($this->input->post());exit;
            $content_type = $this->input->post('content_type');
            $parent_id = $this->input->post('parent_id');
            $title = $this->input->post('title');
            $short_title = (strlen($this->input->post('short_title')) > 0) ? $this->input->post('short_title') : $title;
            $slug = (strlen($this->input->post('slug')) > 0) ? url_title($this->input->post('slug'),'-',TRUE) : url_title(convert_accented_characters($title),'-',TRUE);
            $order = $this->input->post('order');
            $content = $this->input->post('content');
            $page_title = (strlen($this->input->post('page_title')) > 0) ? $this->input->post('page_title') : $title;
            $page_description = (strlen($this->input->post('page_description')) > 0) ? $this->input->post('page_description') : ellipsize($content, 160);
            $page_keywords = $this->input->post('page_keywords');
            $content_id = $this->input->post('content_id');
            $language_slug = $this->input->post('language_slug');
            $published_at = $this->input->post('published_at');
			
			$tags = $this->input->post('tags');
			
			
            if ($content_id == 0)
            {
                $insert_content = array('content_type'=>$content_type,'published_at'=>$published_at, 'parent_id' => $parent_id);
                $content_id = $this->blog_model->insert($insert_content);
            }

            $insert_translation = array('blog_id'=>$content_id,'title' => $title, 'short_title' => $short_title, 'content' => $content,'page_title' => $page_title, 'page_description' => $page_description,'page_keywords' => $page_keywords,'language_slug' => $language_slug);

            if($translation_id = $this->blog_translation_model->insert($insert_translation))
            {
                $this->blog_model->update(array('published_at'=>$published_at,'parent_id'=>$parent_id, 'order'=>$order),$content_id);

                $insert_slug = array(
                    'content_type'=> $content_type,
                    'content_id'=>$content_id,
                    'translation_id'=>$translation_id,
                    'language_slug'=>$language_slug,
                    'url'=>$slug);
                $this->slug_model->verify_insert($insert_slug);
				
				$this->TagsInsertUpdate($tags,$content_id);				
				
            }

            redirect('admin/blogs/index/'.$content_type,'refresh');

        }


    }

    public function edit($language_slug, $content_id)
    {
			$content_type='blog';
			$content = $this->blog_model->get($content_id);
			$tags = $this->blog_model->get_tags_list($content_id);
			$tags_list =implode(',',$tags);
			
        if($content == FALSE)
        {
            $this->postal->add('There is no content to translate.','error');
            redirect('admin/blogs/index', 'refresh');
        }
      //  $content_type = $content_type;
        $translation = $this->blog_translation_model->where(array('blog_id'=>$content_id, 'language_slug'=>$language_slug))->get();
        $this->data['content_language'] = $this->langs[$language_slug]['name'];
        if($translation == FALSE)
        {
            $this->postal->add('There is no translation for that content.','error');
            redirect('admin/blogs/index/'.$content_type, 'refresh');
        }

        $this->load->model('image_model');
        $images = $this->image_model->where('content_id',$content_id)->get_all();
        if($images!== FALSE)
        {
            $this->data['uploaded_images'] = $images;
        }

  			$this->data['content_type'] = $content_type;
			$this->data['tags_list'] = $tags_list;
			  $this->data['translation'] = $translation;
			  
        $this->data['parents'] = $this->blog_model->get_category_list($content_type,$content_id,$language_slug);
        $this->data['content'] = $content;
        $this->data['slugs'] = $this->slug_model->where(array('translation_id'=>$translation->id))->get_all();
        $rules = $this->blog_model->rules;
        $this->form_validation->set_rules($rules['update']);
        if($this->form_validation->run()===FALSE)
        {
            $this->render('admin/blogs/edit_view');
        }
        else
        {
            $translation_id = $this->input->post('translation_id');
            if($translation = $this->blog_translation_model->get($translation_id))
            {
                $parent_id = $this->input->post('parent_id');
                $content_type = $this->input->post('content_type');
                $title = $this->input->post('title');
                $short_title = $this->input->post('short_title');
                $slug = url_title(convert_accented_characters($this->input->post('slug')),'-',TRUE);
                $order = $this->input->post('order');
                $content = $this->input->post('content');
                $page_title = (strlen($this->input->post('page_title')) > 0) ? $this->input->post('page_title') : $title;
                $page_description = (strlen($this->input->post('page_description')) > 0) ? $this->input->post('page_description') : ellipsize($content, 160);
                $page_keywords = $this->input->post('page_keywords');
                $content_id = $this->input->post('content_id');
                $published_at = $this->input->post('published_at');
                $language_slug = $this->input->post('language_slug');
				$tags = $this->input->post('tags');

                $update_translation = array(
                    'title' => $title,
                    'short_title' => $short_title,
                    'content' => $content,
                    'page_title' => $page_title,
                    'page_description' => $page_description,
                    'page_keywords' => $page_keywords);

                if ($this->blog_translation_model->update($update_translation, $translation_id))
                {
                    $update_content = array('parent_id' => $parent_id, 'published_at' => $published_at, 'order' => $order);

                    $this->blog_model->update($update_content, $content_id);
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
					
					$this->TagsInsertUpdate($tags,$content_id);
					
                    $this->postal->add('The translation was updated successfully.','success');
                }
            }
            else
            {
                $this->postal->add('There is no translation to update.','error');
            }
			
			
			
            redirect('admin/blogs/index/'.$content_type,'refresh');
        }
    }
	
	public function TagsAllList(){
		
		$alltags = $this->blog_model->get_tags_alllist();
		echo json_encode($alltags);
		
	}
	
	public function TagsInsertUpdate($tags,$blogs_id){
		
			$tags_array =array();
			$tags_array = explode(',',$tags);
			$published_at = date('YY-mm-dd h:s');
			
			$where = array('blogs_id'=>$blogs_id);
			$this->db->delete('blogs_tags',$where);
				
			foreach($tags_array as $tags_val){
				
				$blogs_id =$blogs_id;
				$flags = $this->blog_model->get_tags_exit($tags_val);
				
				
				if($flags <= 0){
					$this->load->model(array('tag_model','tag_translation_model'));
					$insert_tag = array('published_at'=>$published_at);
					$tag_id = $this->tag_model->insert($insert_tag);
				
					$insert_translation = array('tag_id'=>$tag_id,'name' => $tags_val);
					
					 if($translation_id = $this->tag_translation_model->insert($insert_translation))
						{
							$this->tag_model->update(array('published_at'=>$published_at),$tag_id);
						}
					$data_insert =array(
										'blogs_id'=>$blogs_id,
										'tags_id'=>$tag_id,
										);
					$this->db->insert('blogs_tags',$data_insert);	
					
				}else{
					$data_insert =array(
										'blogs_id'=>$blogs_id,
										'tags_id'=>$flags,
										);
					$this->db->insert('blogs_tags',$data_insert);
				}  
				  
				
			}
	}
	
    public function publish($content_id, $published)
    {
        $content = $this->blog_model->get($content_id);
				$content_type='blog';
        if( ($content != FALSE) && ($published==1 || $published==0))
        {
            if($this->blog_model->update(array('published'=>$published),$content_id))
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
        redirect('admin/blogs/index/'.$content_type,'refresh');
    }

    public function delete($language_slug, $content_id)
    {
				$content_type='blog';

			  if($content = $this->blog_model->get($content_id))
        {
            if($language_slug=='all')
            {
                if($deleted_translations = $this->blog_translation_model->where('blog_id',$content_id)->delete())
                {
                    $deleted_slugs = $this->slug_model->where(array('content_type'=>$content_type,'content_id'=>$content_id))->delete();

                    $deleted_images = 0;
                    $this->load->model('image_model');
                    $images = $this->image_model->where(array('content_type'=>$content_type,'content_id'=>$content_id))->get_all();
                    if(!empty($images))
                    {
                        foreach($images as $image)
                        {
                            @unlink(FCPATH.'media/'.$image->file);
                        }
                        $deleted_images = $this->image_model->where(array('content_type'=>$content_type,'content_id'=>$content_id))->delete();
                    }

                    $this->load->model('keyword_model');
                    $deleted_keywords = $this->keyword_model->where(array('content_type'=>$content_type,'content_id'=>$content_id))->delete();

                    $this->load->model('keyphrase_model');
                    $deleted_keyphrases = $this->keyphrase_model->where(array('content_type'=>$content_type,'content_id'=>$content_id))->delete();

                    $deleted_pages = $this->blog_model->delete($content_id);

                    $this->postal->add($deleted_pages.' page deleted. There were also '.$deleted_translations.' translations, '.$deleted_keywords.' keywords, '.$deleted_keyphrases.' key phrases, '.$deleted_slugs.' slugs and '.$deleted_images.' images deleted.','success');
                }
                else
                {
                    $deleted_pages = $this->blog_model->delete($content_id);
                    $this->postal->add($deleted_pages.' page was deleted','success');
                }
                @unlink(FCPATH.'media/'.$this->featured_image.'/'.$content->featured_image);
            }
            else
            {
                if($this->blog_translation_model->where(array('blog_id'=>$content_id,'language_slug'=>$language_slug))->delete())
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
        redirect('admin/blogs/index/'.$content_type,'refresh');

    }


		public function featured($content_id)
		{


				$this->data['upload_errors'] = '';
				$content_type='blog';
				$content = $this->blog_model->get($content_id);
				if($content === FALSE)
				{
						$this->postal->add('There is no content with that ID','error');
						redirect('admin/blogs/index/blog');
				}
				$this->data['content_type'] = $content_type;
				$this->data['content'] = $content;
				$rules = $this->blog_model->rules;
				$this->form_validation->set_rules($rules['insert_featured']);
				if($this->form_validation->run()===FALSE)
				{
						$this->render('admin/blogs/upload_featured_view');
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
								$this->render('admin/blogs/upload_featured_view');
						}
						else
						{
								$content_id = $this->input->post('content_id');
								$content = $this->blog_model->get($content_id);
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
										if($this->blog_model->update(array('featured_image'=>$processed_image[0][$this->featured_image]['file_name']),$content->id))
										{
												$this->postal->add('The featured image was successfully uploaded.','success');
										}
										redirect('admin/blogs/index/'.$content_type);
								}
								else
								{
										print_r($this->image_nation->get_errors());
								}
						}
				}

		}

		public function delete_featured($content_id)
		{
				$content_type='blog';
				$content = $this->blog_model->get($content_id);
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
						if($this->blog_model->update(array('featured_image'=>''),$id))
						{
								$this->postal->add('The featured image was removed.','success');
								redirect('admin/blogs/index/'.$content_type);
						}

				}
		}

}
