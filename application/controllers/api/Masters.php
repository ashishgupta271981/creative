<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Masters extends Api_Controller
{
	private $_error = array();

	function __construct()
	{
		parent::__construct();

        $this->load->model('slug_model');
        $this->load->model('language_model');
        $this->load->helper('text');

	}


	public function country_list_get()
	{
		$this->load->model('country_model');

		$countries = $this->country_model->get_all();
		array_walk($countries, array($this, 'utility_format_ids_integer'),$arrayName = array('country_id'));
		$this->response([
						'status' 	=> TRUE,
						'code' 		=> 100,
						'data' 	=> $countries
						], API_Controller::HTTP_OK);

	}
	public function state_list_get($country_id=null)
	{
		$this->load->model('state_model');

		$states = $this->state_model->get_by_country($country_id);
		array_walk($states, array($this, 'utility_format_ids_integer'),$arrayName = array('country_id'));
		//array_walk_recursive(array('utility_format_ids_integer' => , $states);)
		$this->response([
						'status' 	=> TRUE,
						'code' 		=> 100,
						'data' 	=> $states
						], API_Controller::HTTP_OK);

	}
	public function city_list_get($state_id=null)
	{
		$this->load->model('city_model');

		$cities = $this->city_model->get_by_state($state_id);
		array_walk($cities, array($this, 'utility_format_ids_integer'),$arrayName = array('state_id'));
		$this->response([
						'status' 	=> TRUE,
						'code' 		=> 100,
						'data' 	=> $cities
						], API_Controller::HTTP_OK);

	}
	public function skill_list_get()
	{
		$this->load->model('skill_translation_model');

		$skills = $this->skill_translation_model->get_list();
		array_walk($skills, array($this, 'utility_format_ids_integer'));

		$this->response([
						'status' 	=> TRUE,
						'code' 		=> 100,
						'data' 	=> $skills
						], API_Controller::HTTP_OK);

	}


}
