<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	public function __construct() {
		parent:: __construct();
	}

	public function get404(){
		$this->load->view('404');
	}

	public function index()
	{
		$sess_id = $this->session->userdata('userid');

		if(!empty($sess_id) && $this->session->userdata('usr_logged_in')==1)
		{
			redirect(base_url().'dashboard');

		}else{
			redirect(base_url().'login');
		}
	}


	public function onGetDashboard()
	{
		if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in')==1) {
			$this->data['page_title'] = 'Dashboard';

			$this->load->view('vw_dashboard', $this->data, false);
		}
		else{
	  		redirect(base_url().'login');
	  	}	
	}

	public function onSetLogin()
	{
		if (empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in')==0) {
			$this->load->view('vw_login');
		}
		else{
			redirect(base_url().'dashboard');
		}
	}

	//login
	public function onCheckLogin()
	{
		if (empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in')==0) {

			if($this->input->is_ajax_request() && $this->input->server('REQUEST_METHOD')=='POST'){

				$this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean|htmlentities');
				$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean|htmlentities');

				if ($this->form_validation->run() == FALSE)
				{
					$this->form_validation->set_error_delimiters('', '');
					$return['errors'] = validation_errors();
					$return['checkedlogin'] = 'rule_error';
				}
				else {
					$username = $this->input->post('username');
					$password = encrypt_it($this->input->post('password'));

					$chkdata = array(
						'user_name'  => $username,
						'pass'     => $password,
						'user_blocked'     => 0
					);
					$userdata = $this->am->getUserData($chkdata,$many=FALSE);
					//print_obj($userdata);die;

					if($userdata){
						$editarr = array('last_login_ip' =>$this->input->ip_address(),'last_login' => dtime);
						$upduser = $this->am->updateUser($editarr,array('user_id'=>$userdata->user_id));
						if($upduser){
							$setdata = array(
								'userid'  => $userdata->user_id,
								'usergroup'  => $userdata->user_group,
								'username'  => $userdata->user_name,
								'fullname'  => $userdata->full_name,
								'usr_logged_in' => 1
							);
							//print_obj($setdata);die;
							$this->session->set_userdata($setdata);
							
							$return['checkedlogin'] = 'success';
							//redirect(base_url().'dashboard');
						}
						else{
							$return['checkedlogin'] = 'upd_error';
						}
					}else{
						$return['checkedlogin'] = 'mismatch_error';
					}
				}
			 header('Content-Type: application/json');
			 echo json_encode($return);	
	  		}
	  		else{
				redirect(base_url());
			}
		}
	  else{
	  	redirect(base_url().'dashboard');
	  }

	}

	// Logout
	public function onSetLogout() {

		if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in')==1) {
			// $sess_array = array(
			// 	'userid'  => '',
			// 	'username'  => '',
			// 	'usr_logged_in' => 0
			// );
	    	//$this->session->unset_userdata($sess_array);
	    	$set_logout_time = array('last_logout' => dtime);
			$upduser = $this->am->updateUser($set_logout_time,array('user_id'=>$this->session->userdata('userid')));
			$this->session->sess_destroy();

			$data['logout_success'] = 'success';
			$this->load->view('vw_login', $data);
		}
		else{
	  		redirect(base_url().'login');
	  	}	
	}



}
