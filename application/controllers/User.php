<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->library('postal');
    }

    public function index()
    {
        $this->login();
    }

    public function login()
    {
        if($this->ion_auth->logged_in())
        {
            if($this->ion_auth->in_group('freelancer'))
            {
                redirect('freelancer/#/');
            }
            if($this->ion_auth->in_group('projectowner'))
            {
                redirect('project-owner/#/');
            }
        }
        $redirect_to = $this->session->flashdata('redirect_to');
        if(!isset($redirect_to) && isset($_SERVER['HTTP_REFERER']))
        {
            $redirect_to = $_SERVER['HTTP_REFERER'];
            if(strpos($redirect_to, site_url(), 0)=== FALSE) $redirect_to = site_url();
        }
        elseif(!isset($redirect_to))
        {
            $redirect_to = site_url('user');
        }
        $this->data['redirect_to'] = $redirect_to;
        $this->data['page_title'] = 'Login';
        $this->load->library('form_validation');
        $this->form_validation->set_rules('identity', 'Identity', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');
      //  $this->form_validation->set_rules('remember','Remember me','integer');
       // $this->form_validation->set_rules('redirect_to','Redirect to','valid_url');
        if($this->form_validation->run()===TRUE)
        {
            $remember = (bool) $this->input->post('remember');
            if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember))
            {

                if($this->ion_auth->in_group('freelancer'))
                {
                    redirect('freelancer/#/');
                }
                if($this->ion_auth->in_group('projectowner'))
                {
                    redirect('project-owner/#/');
                }

            }
            else
            {
                $this->session->set_flashdata('redirect_to',$this->input->post('redirect_to'));
                $this->postal->add($this->ion_auth->errors(),'error');
                redirect('user/login');
            }
        }

        $this->render('public/login_view','public_master');
        //$this->load->helper('form');
        //$this->render('admin/login_view','admin_master');
    }


    public function register()
    {
        if($this->ion_auth->logged_in())
        {
            if($this->ion_auth->in_group('freelancer'))
            {
                redirect('freelancer/#/');
            }
            if($this->ion_auth->in_group('projectowner'))
            {
                redirect('project-owner/#/');
            }
        }
/*echo "<pre>";
print_r($_POST);
exit;*/
        $this->data['page_title'] = 'Register';
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        $this->form_validation->set_rules('identity', 'User Name', 'required|min_length[5]|max_length[12]|is_unique[users.username]');
        $this->form_validation->set_rules('email', 'Email Address', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]|max_length[12]');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');

        $this->form_validation->set_rules('user_type','User Type','trim|required');
        $this->form_validation->set_rules('tnc', 'Agree to the Terms and Conditions', 'required');
       // $this->form_validation->set_rules('redirect_to','Redirect to','valid_url');
        if($this->form_validation->run()===TRUE)
        {

            $username = $this->input->post('identity');
            $email = $this->input->post('email');
            $password = $this->input->post('password');
            $group_ids[0] = $this->input->post('user_type');
/*
            $additional_data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name'  => $this->input->post('last_name'),
                'company'    => $this->input->post('company'),
                'phone'      => $this->input->post('phone')
            );*/
            $additional_data ='';
            $this->ion_auth->register($username, $password, $email, $additional_data, $group_ids);
            $this->postal->add($this->ion_auth->messages(),'success');


            $this->load->library('email');

            $this->email->from('no-reply@cfi.com', 'Creative Freelancers Inc');
            $this->email->to($email);

            $this->email->subject('Thank you for registration CFI');
            //$body = $this->load->view('emails/anillabs.php',$data,TRUE);
            //$this->email->message($body);

            $this->email->message('Thank you for registration CFI.');

            if($this->email->send())
            {
                $this->render('public/register_success','public_master');
            }



        }
        else
        {
            $this->render('public/register_view','public_master');
        }

        //$this->load->helper('form');
        //$this->render('admin/login_view','admin_master');
    }


    public function logout()
    {
        $this->ion_auth->logout();
        $this->postal->add($this->ion_auth->messages(),'error');
        redirect('user/login');
    }
}
