<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Skill_model extends MY_Model
{
    private $featured_image;
    public $before_create = array('created_by');
    public $before_update = array('updated_by');
    public $table = 'skills';
    public function __construct()
    {
        $this->featured_image = $this->config->item('cms_featured_image');
        $this->has_many['skill_translations'] = array('Skill_translation_model','skill_id','id');
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

	
	
    /**
     * Construction function
     *
     * @Controller  Skill
     * @method      get_skill_list
     * @author      Sandeep Panwar
     * @created_at  Dec 09,2016
     * @params      
     *              $language_slug(type:string, default:null)
     * @purpose     It will fetch all record of skill table.
     */
    public function get_skill_list($language_slug = NULL)
    {
        $this->db->select('skills.id as skill_id, skills.published, skills.published_at, skill_translations.id as translation_id, skill_translations.language_slug,  skill_translations.name as skill_name');
        if(isset($language_slug))
        {
            $this->db->where('skill_translations.language_slug',$language_slug);
        }
        $this->db->join('skill_translations','skills.id = skill_translations.skill_id');
        $query = $this->db->get($this->table);
        if($query->num_rows()>0)
        {
            $list_content = array();
            foreach ($query->result() as $row)
            {
                if(!array_key_exists($row->skill_id,$list_content))
                {
                    $list_content[$row->skill_id] = array(
                        'skill_name' => $row->skill_name,
                        'published' => $row->published,
                        'published_at' => $row->published_at,
                        //'created_at' => $row->created_at,
                        //'last_update' => $page->updated_at,
                        //'deleted' => $page->deleted_at,
                        'translations' => array(),
                        'title' => '');
                }
                $list_content[$row->skill_id]['translations'][$row->language_slug] = array(
                            'translation_id' => $row->translation_id,
                            'skill_name' => $row->skill_name);
                            //'created_at' => $translation->created_at,
                            //'last_update' => $translation->updated_at,
                            //'deleted' => $translation->deleted_at);
                if ($row->language_slug == $_SESSION['default_lang'])
                {
                    $list_content[$row->skill_id]['title'] = $row->skill_name;
                }
                elseif (strlen($list_content[$row->skill_id]['title']) == 0)
                {
                    $list_content[$row->skill_id]['title'] = $row->skill_name;
                }
            }
            return $list_content;
        }
        else
        {
            return FALSE;
        }
    }
    /* End of public function get_skill_list($language_slug = NULL) */


	
     /**
     * Construction function
     *
     * @Controller  Skill
     * @method      get_skill_list
     * @author      Sandeep Panwar
     * @created_at  Dec 09,2016
     * @params      
     *              $language_slug(type:string, default:null)
     * @purpose     It will fetch all record of skill table.
     */
    public function search_get_skill_list($category_id ='0',$language_slug = NULL)
    {
        $this->db->select('S.id as skill_id, S.published, S.published_at, ST.id as translation_id, ST.language_slug,  ST.name as skill_name');
        if(isset($language_slug))
        {
            $this->db->where('ST.language_slug',$language_slug);
        }
        if($category_id > '0'){
             $this->db->where('CS.category_id',$category_id);
        }
       
        $this->db->join('categories_skills as CS','S.id = CS.skill_id');
        $this->db->join('skill_translations as ST','S.id = ST.skill_id');
        $query = $this->db->get($this->table.' as S');
        //echo $this->db->last_query();exit;
        if($query->num_rows()>0)
        {
            $list_content = array();
            foreach ($query->result() as $row)
            {
                if(!array_key_exists($row->skill_id,$list_content))
                {
                    $list_content[$row->skill_id] = array(
                        'skill_name' => $row->skill_name,
                        'published' => $row->published,
                        'published_at' => $row->published_at,
                        //'created_at' => $row->created_at,
                        //'last_update' => $page->updated_at,
                        //'deleted' => $page->deleted_at,
                        'translations' => array(),
                        'title' => '');
                }
                $list_content[$row->skill_id]['translations'][$row->language_slug] = array(
                            'translation_id' => $row->translation_id,
                            'skill_name' => $row->skill_name);
                            //'created_at' => $translation->created_at,
                            //'last_update' => $translation->updated_at,
                            //'deleted' => $translation->deleted_at);
                if ($row->language_slug == $_SESSION['default_lang'])
                {
                    $list_content[$row->skill_id]['title'] = $row->skill_name;
                }
                elseif (strlen($list_content[$row->skill_id]['title']) == 0)
                {
                    $list_content[$row->skill_id]['title'] = $row->skill_name;
                }
            }
            return $list_content;
        }
        else
        {
            return FALSE;
        }
    }
    /* End of public function get_skill_list($language_slug = NULL) */
 public $rules = array(
        'insert' => array(
            'name' => array('field'=>'name','label'=>'Name','rules'=>'trim|required|callback__unique_skill'),
            'published_at' => array('field'=>'published_at','label'=>'Published at','rules'=>'trim|datetime'),
            'language_slug' => array('field'=>'language_slug','label'=>'Language slug','rules'=>'trim|required')
        ),
        'update' => array(
            'name' => array('field'=>'name','label'=>'Name','rules'=>'trim|required'),
            'skill_id' => array('field'=>'skill_id', 'label'=>'Skill ID', 'rules'=>'trim|is_natural_no_zero|required'),
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
