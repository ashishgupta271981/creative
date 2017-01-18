<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Members extends Admin_Controller
{

    function __construct()
    {
        parent::__construct();
        if(!$this->ion_auth->in_group('admin'))
        {
            $this->postal->add('You are not allowed to visit the Users page','error');
            redirect('admin');
        }
        $this->load->model(array('members_model'));
        $this->load->helper(array('text','form'));
        $this->load->library('form_validation');
              
    }

    public function index()
    {
        $group_id = $this->uri->segment(4);
        $this->data['page_title'] = 'Members';
        $this->data['group_id'] = $group_id;
        $this->data['users'] = $this->members_model->get_members_list($group_id);
        $this->render('admin/members/index_view');
	}


    public function view($id)
    {
        $content = $this->members_model->get_members_details($id);
        $group_name = $this->members_model->groups($content->group_id);
        $this->data['group_name'] = $group_name;
        $this->data['translation'] = $content;
        $this->render('admin/members/details_view');

    }


    public function create()
    {
         $group_id = $this->uri->segment(4);
        $this->data['page_title'] = 'Create user';
       
        $this->form_validation->set_rules('first_name','First name','trim');
        $this->form_validation->set_rules('last_name','Last name','trim');
        $this->form_validation->set_rules('company','Company','trim');
        $this->form_validation->set_rules('phone','Phone','trim');
        $this->form_validation->set_rules('username','Username','trim|required');
        $this->form_validation->set_rules('email','Email','trim|required|valid_email');
        $this->form_validation->set_rules('password','Password','required|min_length[6]');
        $this->form_validation->set_rules('password_confirm','Password confirmation','required|matches[password]');
        $this->form_validation->set_rules('groups','Groups','required|integer');

        if($this->form_validation->run()===FALSE)
        {
            $this->data['groups'] = $this->members_model->groups();
            $this->data['group_id'] = $group_id;
            $this->render('admin/members/create_view');
        }
        else
        {
            $username = $this->input->post('username');
            $email = $this->input->post('email');
            $password = $this->input->post('password');
            $group = $this->input->post('groups');
            $group_ids =array('0'=>$group);

            $additional_data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name'  => $this->input->post('last_name'),
                'company'    => $this->input->post('company'),
                'phone'      => $this->input->post('phone')
            );
            $this->ion_auth->register($username, $password, $email, $additional_data, $group_ids);

            $this->postal->add($this->ion_auth->messages(),'success');
            if($group_id!=''){
                redirect('admin/members/index/'.$group_id);
            }else{
                redirect('admin/members');
            }
        }
    }

    public function edit($user_id = NULL)
    {
        $user_id = $this->input->post('user_id') ? $this->input->post('user_id') : $user_id;
        if($this->data['current_user']->id == $user_id)
        {
            $this->postal->add('Use the profile page to change your own credentials.','error');
            redirect('admin/members/index/2');
        }
        $this->data['page_title'] = 'Edit user';
    
        $this->form_validation->set_rules('first_name','First name','trim');
        $this->form_validation->set_rules('last_name','Last name','trim');
        $this->form_validation->set_rules('company','Company','trim');
        $this->form_validation->set_rules('phone','Phone','trim');
        $this->form_validation->set_rules('username','Username','trim|required');
        $this->form_validation->set_rules('email','Email','trim|required|valid_email');
        $this->form_validation->set_rules('password','Password','min_length[6]');
        $this->form_validation->set_rules('password_confirm','Password confirmation','matches[password]');
        $this->form_validation->set_rules('groups','Groups','required|integer');
        $this->form_validation->set_rules('user_id','User ID','trim|integer|required');

        if($this->form_validation->run() === FALSE)
        {
            if($user = $this->members_model->get_members_details($user_id))
            {
                $this->data['user'] = $user;
                $this->data['group_id'] = $user->group_id;
              //print_r($user);exit;

            }
            else
            {
                $this->postal->add('The user doesn\'t exist.','error');
                redirect('admin/members/index/2');
            }
            $this->data['groups'] = $this->members_model->groups();
                    
            $this->render('admin/members/edit_view');
        }
        else
        {
            $user_id = $this->input->post('user_id');
            $new_data = array(
                'username' => $this->input->post('username'),
                'email' => $this->input->post('email'),
                'first_name' => $this->input->post('first_name'),
                'last_name'  => $this->input->post('last_name'),
                'company'    => $this->input->post('company'),
                'phone'      => $this->input->post('phone')
            );
            if(strlen($this->input->post('password'))>=6) $new_data['password'] = $this->input->post('password');

            $this->ion_auth->update($user_id, $new_data);

            //Update the groups user belongs to
            $group = $this->input->post('groups');
            $group_ids =array('0'=>$group);
            if (isset($groups) && !empty($groups))
            {
                $this->ion_auth->remove_from_group('', $user_id);
                foreach ($groups as $group)
                {
                    $this->ion_auth->add_to_group($group, $user_id);
                }
            }
            $this->postal->add($this->ion_auth->messages(),'success');
            redirect('admin/members/index/'.$group);
        }
    }

    public function delete($user_id = NULL)
    {
        if(is_null($user_id))
        {
            $this->postal->add('There\'s no user to delete','error');
        }
        else
        {
             if($user = $this->members_model->get_members_details($user_id))
            {
                $this->data['user'] = $user;
                $group_id = $user->group_id;
              //print_r($user);exit;

            }
            $this->ion_auth->delete_user($user_id);
            $this->postal->add($this->ion_auth->messages(),'success');
        }
        redirect('admin/members/index/'.$group_id);
    }
}