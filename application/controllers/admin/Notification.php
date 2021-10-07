<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notification extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$CI =& get_instance();
		$CI->load->library('session');
		frontendcheck();
		$this->settings = get_setting();	
	}
	public function index(){
		$data['settinglist'] = $this->settings;
		$this->load->view("admin/notification/notification",$data);
	}

	public function save(){
		$setting= $this->settings;
		
		foreach($setting as $set)
		{
			$setn[$set->key]=$set->value;
		}
		
		$ONESIGNAL_APP_ID=$setn['onesignal_apid'];
		$ONESIGNAL_REST_KEY=$setn['onesignal_rest_key'];
		
		
		$content = array(
			"en" => $_POST['message']                                                 
		);

		$bigPicture = $this->Common_model->imageupload($_FILES['image'],'image', FCPATH . 'assets/images/notification');

		if(isset($_FILES['image']['name']))
		{
			$filePath = base_url().'/assets/images/notification/'.$bigPicture;
			$fields = array(
				'app_id' =>  $ONESIGNAL_APP_ID,
				'included_segments' => array('All'),                                            
				'data' => array("foo" => "bar"),
				'headings'=> array("en" => $_POST['title']),
				'contents' => $content,
				'big_picture' =>$filePath                    
			);
		}else
		{
			$filePath = '';  
			$fields = array(
				'app_id' => $ONESIGNAL_APP_ID,
				'included_segments' => array('All'),   
				'data' => array("foo" => "bar"),
				'headings'=> array("en" => $_POST['title']),
				'contents' => $content,
			);
		}


		$fields = json_encode($fields);    

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
			'Authorization: Basic '.$ONESIGNAL_REST_KEY));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

		$response = curl_exec($ch);
		
		curl_close($ch);
		print_r($response);
		
	}
}
