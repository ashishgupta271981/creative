<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Project extends Public_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model(array('project_model','skill_model','country_model','state_model','city_model'));
    }

    public function index()
    {
        $this->render('public/homepage_view');
    }

     public function launch()
    {
        $categories = $this->project_model->search_get_category_list();
        $budgets = $this->project_model->search_get_budget_list();
        $skills = $this->skill_model->get_skill_list();
        $countries = $this->country_model->get_country_list();

        //echo "<pre>";print_r($countries);exit;
        $this->data['skills'] = $skills;
        $this->data['budgets'] = $budgets;
        $this->data['categories'] = $categories;
        $this->data['countries'] = $countries;

        $this->render('public/searchlaunch_view');
    }


    public function searchdata()
    {
        $cat_id=$this->input->get_post('cat_id');
        $maincategories = $this->project_model->search_get_category_list();
        $subcategories = $this->project_model->search_get_category_list($cat_id);
        $skills = $this->skill_model->get_skill_list();
        $result =array();
        //echo "<pre>";print_r($skills);exit;
        $result['skills'] = $skills;
        $result['maincategories'] = $maincategories;
        $result['subcategories'] = $subcategories;
        $result['cat_id'] = $cat_id;
        echo json_encode($result);
        
    }


    public function countrystatebydata()
    {
        $country_id=$this->input->get_post('country');
        $mainstate = $this->state_model->get_ajaxstate_list($country_id);
        $result ='';
        if(is_array($mainstate) && !empty($mainstate)){
        foreach($mainstate as $key=>$state){ 
            $result .='<option value="'.$key.'" data-rel-name="'.$state['name'].'">'.$state['name'].'</option>';
        }
        }
        //print_r($result);exit;
        echo $result;
        return $result;
        
    }



    public function statecitybydata()
    {
        $state=$this->input->get_post('state');
        $maincity = $this->city_model->get_ajaxcity_list($state);
        $result ='';
        if(is_array($maincity) && !empty($maincity)){
       foreach($maincity as $key=>$city){ 
            $result .='<option value="'.$key.'" data-rel-name="'.$city['name'].'">'.$city['name'].'</option>';
        }
    }
        //print_r($result);exit;
        echo $result;
        return $result;
        
    }


   public function subcategorybydata()
    {
        $category_dropdown=$this->input->get_post('category_dropdown');
        $subcategories = $this->project_model->search_get_category_list($category_dropdown);
         $result ='<option value="0">Please Select Sub category</option>';
        if(is_array($subcategories) && !empty($subcategories)){
            foreach($subcategories as $key=>$subcat){ 
            $result .='<option value="'.$subcat['id'].'" data-rel-name="'.$subcat['name'].'">'.$subcat['name'].'</option>';
        }
        }
        
        //print_r($result);exit;
        echo $result;
        return $result;
        
    }



  public function subcategorybydiv()
    {
        $category_dropdown=$this->input->get_post('category_id');
        $subcategories = $this->project_model->search_get_category_list($category_dropdown);

         $result ='';

        if(is_array($subcategories) && !empty($subcategories)){
            foreach($subcategories as $key=>$subcat){ 

               $result .=' <div class="col-sm-4 col-sm-6"> <a href="#" id="subcategorydiv_'.$subcat['id'].'" onclick="subcategory_click('.$subcat['id'].');" class="subcategorydiv" data-rel="'.$subcat['id'].'" data-rel-name="'.$subcat['name'].'">
                    <div class="thumbnail "> <img src="'.site_url('assets/img/icon/'.$subcat['featured_image']).'" alt="...">
                      <div class="caption">
                        <p>'.$subcat['name'].'</p>
                      </div>
                    </div>
                    </a> </div>';


          
        }
        }
        
        //print_r($result);exit;
        echo $result;
        return $result;
        
    }



    public function skillsbydata()
    {
        $category_id=$this->input->get_post('category_id');
        $skills = $this->skill_model->search_get_skill_list($category_id);
        $result ='';
        if(is_array($skills) && !empty($skills)){
            foreach($skills as $key=>$skillval){ 
            $result .='<input onclick="skill_checker();" type="checkbox" name="skills" id="skill_'.$key.'" value="'.$key.'" data-rel-name="'.$skillval['skill_name'].'"/> '.$skillval['skill_name'].' <br>';
        }
        }else{
            $result ='No Skill here for This category/subcategory ';
        }
        
        //print_r($result);exit;
        echo $result;
        return $result;
        
    }

}
