<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Withdrawal extends CI_Controller {

	public function __construct() {
		parent::__construct();
		frontendcheck();
		$this->settings = get_setting();
		$this->load->library('email');
	}

	public function index(){
		$data['settinglist'] = $this->settings;
		$data['withdrawal_request'] = $this->Common_model->get_witdrawal_requrst();
		$this->load->view("admin/withdrawal_request/list",$data);
	}

	public function edit(){
		
		$id = $this->uri->segment(4);
	
		$where='id="'.$id.'"';
		$data['withdrawal_request'] = $this->Common_model->getById($where , 'withdrawal_request');
		$userinfowhere =  'id="'.$data['withdrawal_request']['user_id'].'"';
		$data['userinfo'] = $this->Common_model->getById($userinfowhere , 'user');

		$data['settinglist'] = $this->settings;

		$this->load->view("admin/withdrawal_request/edit",$data);
	}

	public function update(){
		$id = $_POST['id'];
		$data['status'] = $_POST['status'];
	
		$res_id=$this->Common_model->update($id,'id',$data,'withdrawal_request');

		$where = 'id="' . $_POST['id'] . '"';
		$withdrawal_request = $this->Common_model->getById($where, 'withdrawal_request');

		if($withdrawal_request['id']){

			$where = 'id="' . $withdrawal_request['user_id'] . '"';
			$userData = $this->Common_model->getById($where, 'users');

			if(isset($userData['id'])){						
				$settinglist = get_setting();
				foreach($settinglist as $set){
					$setn[$set->key]=$set->value;
				}

				$mail['user'] = $userData;
				$mail['withdrawal'] = $withdrawal_request;
				$mail['setn'] = $setn;
				$user_data = $this->load->view("admin/email/withdrawal_request_status",$mail,true);
				$subject = "You have withdrawal request";
				$email =$userData['email'];
				$this->send_mail($user_data,$email,$subject);
			}
		}	

		if($res_id){
			$res=array('status'=>'200','msg'=>'Update status Sucessfully','id'=>$res_id);
			echo json_encode($res);exit;
		}else{
			$res=array('status'=>'400','msg'=>'Please try again');
			echo json_encode($res);exit;
		}
	}

	public function send_mail($message,$email,$subject)
	{
		$smtpWhere='id="1"';
		$smtp_detail = $this->Common_model->getById($smtpWhere,'smtp_setting');

		$emailconfig = get_smtp_setting();		
		$this->email->initialize($emailconfig);
		$this->email->from($smtp_detail['from_email'], $smtp_detail['from_name']);
		$this->email->to($email);
		$this->email->set_mailtype('html');
		$this->email->subject($subject);
		$this->email->message($message);  
		$this->email->send();
	}	
}