<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Banners extends Admin_Controller
{

    function __construct()
    {
        parent::__construct();
        if(!$this->ion_auth->in_group('admin'))
        {
            $this->postal->add('You are not allowed to visit the Users page','error');
            redirect('admin');
        }
        $this->load->model(array('banners_model'));
        $this->load->helper(array('text','form'));
        $this->load->library('form_validation');
              
    }

    public function index()
    {
       
        $this->data['page_title'] = 'banners Advertising ';
        $this->data['users'] = $this->banners_model->get_banners_list();
        $this->render('admin/banners/index_view');
	}



    public function create()
    {
         
    
        $this->data['page_title'] = 'Create banner';
       
        $this->form_validation->set_rules('image_type','Image Type','trim');
        $this->form_validation->set_rules('upload_image','Upload Image','trim');
        $this->form_validation->set_rules('image_url','Image Url','trim');
        $this->form_validation->set_rules('landing_url','Landing Url','trim|required');
        $this->form_validation->set_rules('starting_date','Starting Date','trim|required');
        $this->form_validation->set_rules('ending_date','Ending Date','trim|required');
        

        if($this->form_validation->run()===FALSE)
        {
        
            $this->render('admin/banners/create_view');
        }
        else
        {
           if($this->input->post('image_type')==1){
                     $config = array(
                                'upload_path' => './uploads/banners/',
                                'allowed_types' => 'jpg|gif|png',
                                'max_size' => '2048',
                                'multi' => 'all'
                        );
                        $this->load->library('upload',$config);
                        $this->upload->do_upload('upload_image');    
                        $upload_image =$this->upload->data();
                        $file_name = $upload_image['file_name'];
                        
           }else{
                         $file_name = '';
           }
         
            $insert_content = array(
                'image_type' => $this->input->post('image_type'),
                'upload_image' =>$file_name,
                'image_url'  => $this->input->post('image_url'),
                'landing_url'    => $this->input->post('landing_url'),
                'starting_date'      => $this->input->post('starting_date'),
                'ending_date'      => $this->input->post('ending_date'),
                'status' => 1
            );

            $content_id = $this->banners_model->insert($insert_content);

           $this->postal->add('The Banner was Insert successfully.','success');

            redirect('admin/banners');
           
        }
    }

    public function edit($banner_id = NULL)
    {
        $banner_id = $this->input->post('banner_id') ? $this->input->post('banner_id') : $banner_id;
       
        $this->data['page_title'] = 'Edit Banner';
    
        $this->form_validation->set_rules('image_type','Image Type','trim');
        $this->form_validation->set_rules('upload_image','Upload Image','trim');
        $this->form_validation->set_rules('image_url','Image Url','trim');
        $this->form_validation->set_rules('landing_url','Landing Url','trim|required');
        $this->form_validation->set_rules('starting_date','Starting Date','trim|required');
        $this->form_validation->set_rules('ending_date','Ending Date','trim|required');
        
        if($this->form_validation->run() === FALSE)
        {
            if($banner = $this->banners_model->get_banners_details($banner_id))
            {
                $this->data['banner'] = $banner;
                
              //print_r($banner);exit;

            }
            else
            {
                $this->postal->add('The banner doesn\'t exist.','error');
                redirect('admin/banners');
            }
            
                    
            $this->render('admin/banners/edit_view');
        }
        else
        {
            $banner_id = $this->input->post('banner_id');

            if($this->input->post('image_type')==1){
                     $config = array(
                                'upload_path' => './uploads/banners/',
                                'allowed_types' => 'jpg|gif|png',
                                'max_size' => '2048',
                                'multi' => 'all'
                        );
                        $this->load->library('upload',$config);
                        $this->upload->do_upload('upload_image');    
                        $upload_image =$this->upload->data();
                        $file_name = $upload_image['file_name'];
                        $image_url ='';
                        
           }else{
                         $file_name = '';
                         $image_url =$this->input->post('image_url');
           }
         

    
            $update_data = array(
                'image_type' => $this->input->post('image_type'),
                'upload_image' =>$file_name,
                'image_url'  => $image_url,
                'landing_url'    => $this->input->post('landing_url'),
                'starting_date'      => $this->input->post('starting_date'),
                'ending_date'      => $this->input->post('ending_date')
            );

           $this->banners_model->update($update_data , $banner_id );

           $this->postal->add('The Banner was updated successfully.','success');
           
            redirect('admin/banners');
        }
    }

 
    public function delete($banner_id = NULL)
    {
        if($content = $this->banners_model->get($banner_id))
        {
            
            $deleted_pages = $this->banners_model->delete($banner_id);
            $this->postal->add('Banner was deleted','success');
                
        }
        else
        {
            $this->postal->add('There is no translation to delete.','error');
        }
       
        redirect('admin/banners','refresh');

    }


}