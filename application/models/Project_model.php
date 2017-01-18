<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Project_model extends MY_Model
{
    private $featured_image;
    public $before_create = array('created_by');
    public $before_update = array('updated_by');
    public $table = 'projects';

    public function __construct()
    {
        $this->featured_image = $this->config->item('cms_featured_image');
        $this->has_many['project_translations'] = array('Project_translation_model','project_id','id');
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

    public function get_search_total_records($q,$is_featured)
    {
        $this->db->select("P.id as project_id");

        $this->db->join('project_translations AS PT','P.id = PT.project_id');
        $this->db->order_by('P.id desc');
        $where = "(PT.title LIKE '%".$q."%' OR PT.description LIKE '%".$q."%') AND P.is_featured ='".$is_featured."'";
        $this->db->where($where);
        $query = $this->db->get($this->table." AS P");
        return $query->num_rows();
    }

    public function get_search($q,$is_featured=1,$num_records=10,$start=0)
    {

        $project_configs = $this->config->item('projects_feature');


        $this->db->select("P.id as project_id, PT.title, PT.description, COALESCE(UW.status,'0') AS liked, PB.budget,PW.mode".
                          ",DATE_FORMAT(P.published_at,'%M %d %Y') as published, DATE_FORMAT(P.expire_at,'%M %d %Y') as expiry".
                          ", concat('".addslashes($project_configs['path'])."', (COALESCE(P.featured_image, 'default_project.jpg'))) AS featured_image");

        $this->db->join('project_translations AS PT','P.id = PT.project_id');
        $this->db->join('users_wishlist AS UW','P.id = UW.project_id AND P.user_id = UW.user_id','LEFT');
        $this->db->join('project_budget_translations AS PB','P.project_budget_id = PB.project_budget_id','LEFT');
        $this->db->join('project_work_mode_translations AS PW','P.project_work_mode_id = PW.project_work_mode_id','LEFT');
        $where = "(PT.title LIKE '%".$q."%' OR PT.description LIKE '%".$q."%') AND P.is_featured ='".$is_featured."'";
        $this->db->where($where);
        $this->db->limit($num_records,$start);
        $this->db->order_by('P.id desc');
        $query = $this->db->get($this->table." AS P");
        if($query->num_rows()>0)
        {
            $list_content = array();
            return $query->result();
        }
        else
        {
            return FALSE;
        }
    }


    public function get_details($project_id)
    {
        $project_configs = $this->config->item('projects_feature');
        $this->db->select("P.id as project_id, PT.title, PT.description, COALESCE(UW.status,'0') AS liked, PB.budget,PW.mode".
                          ",DATE_FORMAT(P.published_at,'%M %d %Y') as published, DATE_FORMAT(P.expire_at,'%M %d %Y') as expiry".
                          ", concat('".addslashes($project_configs['path'])."', (COALESCE(P.featured_image, 'default_project.jpg'))) AS featured_image");
        $this->db->join('project_translations AS PT','P.id = PT.project_id');
        $this->db->join('users_wishlist AS UW','P.id = UW.project_id AND P.user_id = UW.user_id','LEFT');
        $this->db->join('project_budget_translations AS PB','P.project_budget_id = PB.project_budget_id','LEFT');
        $this->db->join('project_work_mode_translations AS PW','P.project_work_mode_id = PW.project_work_mode_id','LEFT');
        $where = "P.id ='".$project_id."'";
        $this->db->where($where);
        
        $this->db->order_by('P.id desc');
        $query = $this->db->get($this->table." AS P");
        if($query->num_rows()>0)
        {
            $list_content = array();
            return $query->result();
        }
        else
        {
            return FALSE;
        }
    }

    public function get_wish_listed_total_records($user_id)
    {
        $this->db->select("id");
        $where = "user_id ='".$user_id."'";
        $this->db->where($where);
        $query = $this->db->get("users_wishlist");
        return $query->num_rows();
    }

    public function get_wish_listed($user_id,$num_records=10,$start=0)
    {

        $project_configs = $this->config->item('projects_feature');


        $this->db->select("P.id as project_id, PT.title, COALESCE(UW.status,'0') AS liked".
                          ", concat('".addslashes($project_configs['path'])."', (COALESCE(P.featured_image, 'default_project.jpg'))) AS featured_image");

        $this->db->join('project_translations AS PT','P.id = PT.project_id');
        $this->db->join('users_wishlist AS UW','P.id = UW.project_id AND P.user_id = UW.user_id','RIGHT');
        $where = "UW.user_id ='".$user_id."'";
        $this->db->where($where);
        $this->db->limit($num_records,$start);
        $this->db->order_by('P.id desc');
        $query = $this->db->get($this->table." AS P");
        if($query->num_rows()>0)
        {
            return $query->result();
        }
        else
        {
            return FALSE;
        }
    }


/*------------------------------------- --------------------------------*/
/*------------------------admin section --------------------------------*/
/*----------------------------------------------------------------------*/

 public function admin_get_project_total_records($q)
    {
        $this->db->select("P.id as project_id");
        $this->db->join('project_translations AS PT','P.id = PT.project_id');       
        $this->db->join('project_budget_translations AS PB','P.project_budget_id = PB.project_budget_id','LEFT');
        $this->db->join('project_work_mode_translations AS PW','P.project_work_mode_id = PW.project_work_mode_id','LEFT');      
        $where = "PT.title LIKE '%".$q."%' ";
        $this->db->where($where);
        $this->db->order_by('P.id desc');
        $query = $this->db->get($this->table." AS P");
        return $query->num_rows();
    }

 public function admin_get_project_list($q,$num_records=5,$start=0)
    {

        $project_configs = $this->config->item('projects_feature');


        $this->db->select("P.id as project_id, P.featured_image,PT.title".
                          ", PT.description,PB.budget,PW.mode".
                          ",P.published".
                          ",PT.id as translation_id ,P.published_at,PT.language_slug".
                           ",DATE_FORMAT(P.expire_at,'%M %d %Y') as expiry".
                          ", concat('".addslashes($project_configs['path'])."', (COALESCE(P.featured_image, 'default_project.jpg'))) AS featured_image");

        $this->db->join('project_translations AS PT','P.id = PT.project_id');
        $this->db->join('project_budget_translations AS PB','P.project_budget_id = PB.project_budget_id','LEFT');
        $this->db->join('project_work_mode_translations AS PW','P.project_work_mode_id = PW.project_work_mode_id','LEFT');
        $where = "PT.title LIKE '%".$q."%' ";
        $this->db->where($where);
        $this->db->limit($num_records,$start);
        $this->db->order_by('P.id desc');
        $query = $this->db->get($this->table." AS P");
        

        if($query->num_rows()>0)
        {
            $list_content = array();
            //return $query->result_array();

            foreach ($query->result() as $row)
            {
                
            
                if(!array_key_exists($row->project_id,$list_content))
                {
                     $featured_image = '';
                    if (strlen($row->featured_image) > 0) $featured_image = site_url('media/' . $this->featured_image . '/' . $row->featured_image);

                    $list_content[$row->project_id] = array(
                        'title' => $row->title,
                        'budget' => $row->budget,
                        'mode' => $row->mode,
                        'featured_image' =>$featured_image,
                        'published' => $row->published,
                        'published_at' => $row->published_at,
                        'translations' => array(),
                        'title' => '');
                }
                $list_content[$row->project_id]['translations'][$row->language_slug] = array(
                            'translation_id' => $row->translation_id,
                            'mode' => $row->title);
                            
                if ($row->language_slug == $_SESSION['default_lang'])
                {
                    $list_content[$row->project_id]['title'] = $row->title;
                }
                elseif (strlen($list_content[$row->project_id]['title']) == 0)
                {
                    $list_content[$row->project_id]['title'] = $row->title;
                }
            }

             return $list_content;


        }
        else
        {
            return FALSE;
        }
    }



public function admin_get_project_details($id)
    {

        $project_configs = $this->config->item('projects_feature');


        $this->db->select("P.id as project_id, P.featured_image,PT.title".
                          ", PT.description,PB.budget,PW.mode".
                          ",P.published".
                          ",PT.id as translation_id ,P.published_at,PT.language_slug".
                           ",DATE_FORMAT(P.expire_at,'%M %d %Y') as expiry".
                          ", concat('".addslashes($project_configs['path'])."', (COALESCE(P.featured_image, 'default_project.jpg'))) AS featured_image");

        $this->db->join('project_translations AS PT','P.id = PT.project_id');
        $this->db->join('project_budget_translations AS PB','P.project_budget_id = PB.project_budget_id','LEFT');
        $this->db->join('project_work_mode_translations AS PW','P.project_work_mode_id = PW.project_work_mode_id','LEFT');
        $where = "P.id ='".$id."'";
        
        $this->db->where($where);
       
        $this->db->order_by('P.id desc');
        $query = $this->db->get($this->table." AS P");
        if($query->num_rows()>0)
        {
            $list_content = array();
            //return $query->result_array();

            foreach ($query->result() as $row)
            {
                
            
                if(!array_key_exists($row->project_id,$list_content))
                {
                     $featured_image = '';
                    if (strlen($row->featured_image) > 0) $featured_image = site_url('media/' . $this->featured_image . '/' . $row->featured_image);

                    $list_content[$row->project_id] = array(
                        'title' => $row->title,
                        'budget' => $row->budget,
                        'mode' => $row->mode,
                        'featured_image' =>$featured_image,
                        'description' =>$row->description,  
                        'published' => $row->published,
                        'published_at' => $row->published_at,
                        'translations' => array(),
                        'title' => '');
                }
                $list_content[$row->project_id]['translations'][$row->language_slug] = array(
                            'translation_id' => $row->translation_id,
                            'mode' => $row->title);
                            
                if ($row->language_slug == $_SESSION['default_lang'])
                {
                    $list_content[$row->project_id]['title'] = $row->title;
                }
                elseif (strlen($list_content[$row->project_id]['title']) == 0)
                {
                    $list_content[$row->project_id]['title'] = $row->title;
                }
            }

             return $list_content;


        }
        else
        {
            return FALSE;
        }
    }


    public function admin_get_budget_list($language_slug='en')
    {
        $this->db->select('pb.id, pbt.budget');
        $this->db->order_by('pbt.budget','asc');
        $this->db->join('project_budget_translations as pbt','pbt.project_budget_id = pb.id','right');
        $this->db->where('pbt.language_slug',$language_slug);
        $query = $this->db->get('project_budgets as pb');
        $budgets = array('0'=>'No Budget');
        if($query->num_rows()>0)
        {
            foreach($query->result() as $row)
            {
                $budgets[$row->id] = $row->budget;
            }
        }
        //echo $this->db->last_query();
        return $budgets;
    }


  public function admin_get_mode_list($language_slug='en')
    {
        $this->db->select('pb.id, pbt.mode');
        $this->db->order_by('pbt.mode','asc');
        $this->db->join('project_work_mode_translations as pbt','pbt.project_work_mode_id = pb.id','right');
        $this->db->where('pbt.language_slug',$language_slug);
        $query = $this->db->get('project_work_modes as pb');
        $modes = array('0'=>'No Mode');
        if($query->num_rows()>0)
        {
            foreach($query->result() as $row)
            {
                $modes[$row->id] = $row->mode;
            }
        }
        //echo $this->db->last_query();
        return $modes;
    }



      public $rules = array(
        'insert' => array(
            'name' => array('field'=>'name','label'=>'Name','rules'=>'trim|required|callback__unique_value'),
            'published_at' => array('field'=>'published_at','label'=>'Published at','rules'=>'trim|datetime'),
            'language_slug' => array('field'=>'language_slug','label'=>'Language slug','rules'=>'trim|required')
        ),
        'update' => array(
            'name' => array('field'=>'name','label'=>'Name','rules'=>'trim|required|callback__unique_value'),
            'id' => array('field'=>'id', 'label'=>'ID', 'rules'=>'trim|is_natural_no_zero|required'),
            'published_at' => array('field'=>'published_at','label'=>'Published at','rules'=>'trim|datetime'),
            'language_slug' => array('field'=>'language_slug','label'=>'language_slug','rules'=>'trim|required')
        ),
        'insert_featured' => array(
            'file_name' => array('field'=>'file_name','label'=>'File name','rules'=>'trim'),
            // there where two typos in here 'Contend ID' and 'tirm' 
            'content_id' => array('field'=>'content_id','label'=>'Content ID','rules'=>'trim|is_natural_no_zero|required')
        )
    );



  public function search_get_category_list($parent_id = 0, $language_slug='en')
    {
        $this->db->select('C.id,C.featured_image, CT.name');
        $this->db->order_by('CT.name','asc');
        $this->db->join('category_translations as CT','CT.category_id = C.id','right');
        $this->db->where('C.parent_id',$parent_id);
        $query = $this->db->get('categories as C');
        
        if($query->num_rows()>0)
        {
           
                $category = $query->result_array();
               
            
        }else{
            $category ='';
        }
        //echo $this->db->last_query();
        return $category;
    }


    public function search_get_budget_list($language_slug='en')
    {
        $this->db->select('PB.id, PBT.budget');
        $this->db->order_by('PB.id','asc');
        $this->db->join('project_budget_translations as PBT','PBT.project_budget_id = PB.id','right');
        $this->db->where('PBT.language_slug',$language_slug);
        $query = $this->db->get('project_budgets as PB');
       
        if($query->num_rows()>0)
        {
            
                $budgets = $query->result_array();
           
        }else{
            $budgets ='';
        }
        //echo $this->db->last_query();
        return $budgets;
    }

}
