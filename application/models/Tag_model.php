<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * tags Model
 *
 * @package     CFI
 * @subpackage  admin
 * @category    Master
 * @author      Amitesh Jain
 * @created_at  Dec 20,2016
 *
 */
class Tag_model extends MY_Model
{
    public $before_create = array('created_by');
    public $before_update = array('updated_by');
    public $table = 'tags';
    /**
	 * Construction function
	 *
	 * @Controller  tag
	 * @method    	__construct
	 * @author      Amitesh Jain
	 * @created_at  Dec 20,2016
	 * @purpose 	It will check how many translations a record has and join tables.
	 */
    public function __construct()
    {
        $this->has_many['tag_translations'] = array('Tag_translation_model','tag_id','id');
        parent::__construct();
    }
	/* End of public function __construct() */



    /**
	 * Construction function
	 *
	 * @Controller  tag
	 * @method    	created_by
	 * @author      Amitesh Jain
	 * @created_at  Dec 19,2016
	 * @params
	 * 				$data(type:array)
	 * @purpose 	It will enter into database who created a particular record.
	 */
    public function created_by($data)
    {
        $data['created_by'] = $this->user_id;
        return $data;
    }
	/* End of public function created_by($data) */



	/**
	 * Construction function
	 *
	 * @Controller  tag
	 * @method    	created_by
	 * @author      Amitesh jain
	 * @created_at  Dec 19,2016
	 * @params
	 * 				$data(type:array)
	 * @purpose 	It will enter into database who created a particular record.
	 */
    public function updated_by($data)
    {
        $data['updated_by'] = $this->user_id;
        return $data;
    }
	/* End of public function updated_by($data) */



	/**
	 * Construction function
	 *
	 * @Controller  Tag
	 * @method    	get_tag_list
	 * @author      Amitesh Jain
	 * @created_at  Dec 19,2016
	 * @params
	 * 				$language_slug(type:string, default:null)
	 * @purpose 	It will fetch all record of tag table.
	 */
    public function get_tag_list($language_slug = NULL)
    {
        //$this->db->select('tags.id as tag_id, tags.published, tags.published_at, tag_translations.id as translation_id, tag_translations.language_slug,  tag_translations.name as tag_name');
        $this->db->select('tags.id as tag_id, tags.published, tags.published_at, tag_translations.id as translation_id, tag_translations.language_slug,  tag_translations.name as tag_name');
        if(isset($language_slug))
        {
            $this->db->where('tag_translations.language_slug',$language_slug);
        }
        $this->db->join('tag_translations','tags.id = tag_translations.tag_id');
        $query = $this->db->get($this->table);
        if($query->num_rows()>0)
        {
            $list_content = array();
            foreach ($query->result() as $row)
            {
                if(!array_key_exists($row->tag_id,$list_content))
                {
                    $list_content[$row->tag_id] = array(
                        'tag_name' => $row->tag_name,
                        'published' => $row->published,
                        'published_at' => $row->published_at,
                        //'created_at' => $row->created_at,
                        //'last_update' => $page->updated_at,
                        //'deleted' => $page->deleted_at,
                        'translations' => array(),
                        'title' => '');
                }
                $list_content[$row->tag_id]['translations'][$row->language_slug] = array(
                            'translation_id' => $row->translation_id,
                            'tag_name' => $row->tag_name);
                            //'created_at' => $translation->created_at,
                            //'last_update' => $translation->updated_at,
                            //'deleted' => $translation->deleted_at);
                if ($row->language_slug == $_SESSION['default_lang'])
                {
                    $list_content[$row->tag_id]['title'] = $row->tag_name;
                }
                elseif (strlen($list_content[$row->tag_id]['title']) == 0)
                {
                    $list_content[$row->tag_id]['title'] = $row->tag_name;
                }
            }
            return $list_content;
        }
        else
        {
            return FALSE;
        }
    }
	/* End of public function get_tag_list($language_slug = NULL) */



    public $rules = array(
        'insert' => array(
            'name' => array('field'=>'name','label'=>'Name','rules'=>'trim|required|callback__unique_tag'),
            'published_at' => array('field'=>'published_at','label'=>'Published at','rules'=>'trim|datetime'),
            'language_slug' => array('field'=>'language_slug','label'=>'Language slug','rules'=>'trim|required')
        ),
        'update' => array(
            'name' => array('field'=>'name','label'=>'Name','rules'=>'trim|required'),
            'tag_id' => array('field'=>'tag_id', 'label'=>'Tag ID', 'rules'=>'trim|is_natural_no_zero|required'),
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
