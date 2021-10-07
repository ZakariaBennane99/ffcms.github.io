<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$CI =& get_instance();
		$CI->load->library('session');
		$this->load->helper('delpha_helper');		
		frontendcheck();
		$this->settings = get_setting();		
	}

	public function index(){

		/*Category*/
		$result = $this->Common_model->getCount('','category');
		$data['category']=sizeof($result);
		
		$users = $this->Common_model->getCount('','users');
		$data['users']=sizeof($users);

		$level = $this->Common_model->getCount('','level');
		$data['level']=sizeof($level);

		$question = $this->Common_model->getCount('','question');
		$data['question']=sizeof($question);

		$data['settinglist'] = $this->settings;

		$this->load->view("admin/dashboard", $data);
	}
	
}
