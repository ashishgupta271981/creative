<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Blog_model extends MY_Model
{
    private $featured_image;
    public $before_create = array('created_by');
    public $before_update = array('updated_by');
    public $table = 'blogs';
    public function __construct()
    {
        $this->featured_image = $this->config->item('cms_featured_image');
        $this->has_many['blog_translations'] = array('Blog_translation_model','blog_id','id');
        parent::__construct();
    }

    public function created_by($data)
    {
        $data['created_by'] = $this->user_id;
        return $data;
    }

    public function updated_by($data)
    {
        $data['updated_by'] = $this->user_id;
        return $data;
    }


    


    public function get_blogs_list($language_slug = NULL)
    {
        $this->db->select('blogs.id as blogs_id, blogs.parent_id, blogs.featured_image, blogs.order, blogs.published, blogs.published_at, blog_translations.id as translation_id, blog_translations.language_slug, blog_translations.short_title as translation_title, blog_translations.rake as translation_rake');
      //  $this->db->where('contents.content_type',$content_type);
        if(isset($language_slug))
        {
            $this->db->where('blog_translations.language_slug',$language_slug);
        }
        $this->db->join('blog_translations','blogs.id = blog_translations.blog_id');
        $this->db->order_by('blogs.id','desc');
        $query = $this->db->get($this->table);
        if($query->num_rows()>0)
        {
            $list_content = array();
            foreach ($query->result() as $row)
            {
                if(!array_key_exists($row->blogs_id,$list_content))
                {
                    $featured_image = '';
                    if (strlen($row->featured_image) > 0) $featured_image = site_url('media/' . $this->featured_image . '/' . $row->featured_image);
                    $list_content[$row->blogs_id] = array(
                        'published' => $row->published,
                        'published_at' => $row->published_at,
                        //'created_at' => $row->created_at,
                        'featured_image' => $featured_image,
                        //'last_update' => $page->updated_at,
                        //'deleted' => $page->deleted_at,
                        'translations' => array(),
                        'title' => '');
                }
                $list_content[$row->blogs_id]['translations'][$row->language_slug] = array(
                            'translation_id' => $row->translation_id,
                            'title' => $row->translation_title,
                            'rake' => $row->translation_rake);
                            //'created_at' => $translation->created_at,
                            //'last_update' => $translation->updated_at,
                            //'deleted' => $translation->deleted_at);
                if ($row->language_slug == $_SESSION['default_lang'])
                {
                    $list_content[$row->blogs_id]['title'] = $row->translation_title;
                }
                elseif (strlen($list_content[$row->blogs_id]['title']) == 0)
                {
                    $list_content[$row->blogs_id]['title'] = $row->translation_title;
                }
            }
            return $list_content;
        }
        else
        {
            return FALSE;
        }
    }

    public function get_category_list($content_type,$content_id,$language_slug)
    {
        $this->db->select('contents.id, content_translations.short_title');
        $this->db->order_by('short_title','asc');
        $this->db->join('content_translations','contents.id = content_translations.content_id','right');
      //  $this->db->where('contents.id > ',$content_id);
        $this->db->where('contents.content_type','category');
        $this->db->where('content_translations.language_slug',$language_slug);
        $query = $this->db->get('contents');
        $parents = array('0'=>'No Category');
        if($query->num_rows()>0)
        {
            foreach($query->result() as $row)
            {
                $parents[$row->id] = $row->short_title;
            }
        }
        //echo $this->db->last_query();
        return $parents;
    }


	public function get_tags_exit($tags)
    {
		$flags = 0;
        $this->db->select('tag_id,name');
        $this->db->where('name',$tags);
        $query = $this->db->get('tag_translations');
        if($query->num_rows()>0)
        {
            foreach($query->result() as $row)
            {

                $flags = $row->tag_id;
            }
        }
        //echo $this->db->last_query();
        return $flags;
    }

	public function get_tags_list($blogs_id)
    {
		$parents = array();
        $this->db->select('bt.tags_id,tt.name');
        $this->db->where('blogs_id',$blogs_id);
		$this->db->join('tag_translations tt','tt.tag_id = bt.tags_id');
        $query = $this->db->get('blogs_tags as bt');
        if($query->num_rows()>0)
        {
            foreach($query->result_array() as $row)
            {

               $parents[] = $row['name'];
            }
        }
        //echo $this->db->last_query();
        return $parents;
    }


	public function get_tags_alllist()
    {
		$parents = array();
        $this->db->select('tt.name');
        $query = $this->db->get('tag_translations as tt');
        if($query->num_rows()>0)
        {
            foreach($query->result_array() as $row)
            {

               $parents[] = $row['name'];
            }
        }
        //echo $this->db->last_query();
        return $parents;
    }



    public $rules = array(
        'insert' => array(
            'parent_id' => array('field'=>'parent_id','label'=>'Category ID','rules'=>'trim|is_natural|required'),
            'title' => array('field'=>'title','label'=>'Title','rules'=>'trim|required'),
            'short_title' => array('field'=>'short_title','label'=>'Short title','rules'=>'trim'),
            'slug' => array('field'=>'slug', 'label'=>'Slug', 'rules'=>'trim'),
            'order' => array('field'=>'order','label'=>'Order','rules'=>'trim|is_natural'),
            'teaser' => array('field'=>'teaser','label'=>'Teaser','rules'=>'trim'),
            'content' => array('field'=>'content','label'=>'Content','rules'=>'trim'),
            'page_title' => array('field'=>'page_title','label'=>'Page title','rules'=>'trim'),
            'page_description' => array('field'=>'page_description','label'=>'Page description','rules'=>'trim'),
            'page_keywords' => array('field'=>'page_keywords','label'=>'Page keywords','rules'=>'trim'),
            'content_id' => array('field'=>'content_id', 'label'=>'Content ID', 'rules'=>'trim|is_natural|required'),
            'content_type' => array('field'=>'content_type','label'=>'Content type','rules'=>'trim|required'),
            'published_at' => array('field'=>'published_at','label'=>'Published at','rules'=>'trim|datetime'),
            'language_slug' => array('field'=>'language_slug','label'=>'Language slug','rules'=>'trim|required')
        ),
        'update' => array(
          'parent_id' => array('field'=>'parent_id','label'=>'Category ID','rules'=>'trim|is_natural|required'),
            'title' => array('field'=>'title','label'=>'Title','rules'=>'trim|required'),
            'short_title' => array('field'=>'short_title','label'=>'Short title','rules'=>'trim'),
            'slug' => array('field'=>'slug', 'label'=>'Slug', 'rules'=>'trim'),
            'order' => array('field'=>'order','label'=>'Order','rules'=>'trim|is_natural'),
            'teaser' => array('field'=>'teaser','label'=>'Teaser','rules'=>'trim'),
            'content' => array('field'=>'content','label'=>'Content','rules'=>'trim'),
            'page_title' => array('field'=>'page_title','label'=>'Page title','rules'=>'trim|required'),
            'page_description' => array('field'=>'page_description','label'=>'Page description','rules'=>'trim'),
            'page_keywords' => array('field'=>'page_keywords','label'=>'Page keywords','rules'=>'trim'),
            'translation_id' => array('field'=>'translation_id', 'label'=>'Translation ID', 'rules'=>'trim|is_natural_no_zero|required'),
            'content_id' => array('field'=>'content_id', 'label'=>'Content ID', 'rules'=>'trim|is_natural_no_zero|required'),
            'content_type' => array('field'=>'content_type','label'=>'Content type','rules'=>'trim|required'),
            'published_at' => array('field'=>'published_at','label'=>'Published at','rules'=>'trim|datetime'),
            'language_slug' => array('field'=>'language_slug','label'=>'language_slug','rules'=>'trim|required')
        ),
        'insert_featured' => array(
            'file_name' => array('field'=>'file_name','label'=>'File name','rules'=>'trim'),
            // there where two typos in here 'Contend ID' and 'tirm'
            'content_id' => array('field'=>'content_id','label'=>'Content ID','rules'=>'trim|is_natural_no_zero|required')
        )
    );
}
