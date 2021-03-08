<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

	public function __construct() {
		parent:: __construct();
	}

	public function index()
	{
		if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in')==1 && $this->session->userdata('usergroup')==1)
		{

			$userdata = $this->am->getUserData($p=array('user_id !='=>1),$many=TRUE);
			if($userdata){
				foreach ($userdata as $key => $value) {
					$this->data['user_data'][] = array(
						'dtime'  => $value->dtime,
						'userid'  => $value->user_id,
						'usergroup'  => $value->user_group,
						'username'  => $value->user_name,
						'password'  => decrypt_it($value->pass),
						'fullname'  => $value->full_name,
						'lastlogin'  => $value->last_login,
						'lastloginip'  => $value->last_login_ip,
						'lastupdated'  => $value->last_updated
					);
				}
				
			//print_obj($this->data['user_data']);die;

		}else{
			$this->data['user_data']= '';
		}
		$this->load->view('users/vw_userlist', $this->data, false);
	}
	else{
			redirect(base_url());
		}
}

	public function onCheckDuplicateUser()
	{
		if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in')==1)
		{
			if($this->input->is_ajax_request() && $this->input->server('REQUEST_METHOD')=='POST'){

				$username = xss_clean($this->input->post('user_name'));

				$user_exists = $this->am->getUserData($p=array('user_name'=>$username),$many=FALSE);
				if($user_exists){
					if($username==$this->session->userdata('username')){
						$return['user_exists'] = 3;
					}else{
						$return['user_exists'] = 1;
					}
				}
				else{
					$return['user_exists'] = 0;
				}
					
				header('Content-Type: application/json');

				echo json_encode($return);	
			}
			else{
				//exit('No direct script access allowed');
				redirect(base_url());
			}
		}else{
			redirect(base_url());
		}
	}


	public function onGetUserProfile()
	{
		if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in')==1) {

			$user_id = xss_clean($this->uri->segment(2));
			$chkdata = array(
				'user_id'  => (isset($user_id) && $user_id!='')?$user_id:$this->session->userdata('userid')
			);
			$userdata = $this->am->getUserData($chkdata,$many=FALSE);
			if($userdata){
				$this->data['user_data'] = array(
						'userid'  => $userdata->user_id,
						'user_group'  => $userdata->user_group,
						'username'  => $userdata->user_name,
						'password'  => decrypt_it($userdata->pass),
						'fullname'  => $userdata->full_name,
						'lastlogin'  => $userdata->last_login,
						'lastloginip'  => $userdata->last_login_ip,
						'lastupdated'  => $userdata->last_updated
					);
				//print_obj($this->data['user_data']);die;
				$this->load->view('users/vw_profile', $this->data, false);
			}
			else{
	  			redirect(base_url());
	  		}
			
		}
		else{
	  		redirect(base_url());
	  	}	
	}

	public function onChangeUserProfile()
	{
		if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in')==1) {

			$user_id = xss_clean($this->input->post('user_id'));
			$chkdata = array('user_id'  => $user_id);

			$fullname = xss_clean($this->input->post('full_name'));
			if($this->session->userdata('usergroup')==1){
				$user_group = xss_clean($this->input->post('user_group'));
			}else{
				$user_group = $this->session->userdata('usergroup');
			}
			
			$username = xss_clean($this->input->post('user_name'));
			$password = xss_clean($this->input->post('password'));

			$upd_userdata = array(
						'full_name'  => $fullname,
						'user_group'  => $user_group,
						'user_name'  => $username,
						'pass'  => encrypt_it($password),
						'last_updated'  => dtime,
						'updated_by'  => $this->session->userdata('userid')
					);
			// print_obj($upd_userdata);die;

			$userdata = $this->am->getUserData($chkdata,$many=FALSE);
			if($userdata && $username!=''){
				//update

				$upduser = $this->am->updateUser($upd_userdata,$chkdata);
				if ($upduser) {
					$this->data['update_success'] = 'Successfully updated.';
					//list
					
					$usrdata = $this->am->getUserData($chkdata,$many=FALSE);
					$this->data['user_data'] = array(
						'userid'  => $usrdata->user_id,
						'user_group'  => $usrdata->user_group,
						'username'  => $usrdata->user_name,
						'password'  => decrypt_it($usrdata->pass),
						'fullname'  => $usrdata->full_name,
						'lastlogin'  => $usrdata->last_login,
						'lastloginip'  => $usrdata->last_login_ip,
						'lastupdated'  => $usrdata->last_updated
					);
					$setdata = array('username'  => $usrdata->user_name);
					$this->session->set_userdata($setdata);

				}
				else{
					$this->data['update_failure'] = 'Not updated!';
				}

				$this->load->view('users/vw_profile', $this->data, false);
			}
			else{
	  			redirect(base_url());
	  		}	
			
		}
		else{
	  		redirect(base_url());
	  	}	
	}

   public function onCreateUserView()
	{
		if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in')==1 && $this->session->userdata('usergroup')==1) {

				$this->load->view('users/vw_createuser');
		
		}
		else{
	  		redirect(base_url());
	  	}	
	}

	public function onCreateUser()
	{
		if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in')==1 && $this->session->userdata('usergroup')==1)
		{
			if($this->input->is_ajax_request() && $this->input->server('REQUEST_METHOD')=='POST'){

				$fullname = xss_clean($this->input->post('full_name'));
				$user_group = xss_clean($this->input->post('user_group'));
				$username = xss_clean($this->input->post('user_name'));
				$password = xss_clean($this->input->post('password'));

				$chkdata = array('user_name'  => $username);
				$userdata = $this->am->getUserData($chkdata,$many=FALSE);
			
			if(!$userdata){
				//update

				$ins_userdata = array(
							'full_name'  => $fullname,
							'user_group'  => $user_group,
							'user_name'  => $username,
							'pass'  => encrypt_it($password),
							'dtime'  => dtime,
							'created_by'  => $this->session->userdata('userid')
						);
				// print_obj($ins_userdata);die;
				$adduser = $this->am->addUser($ins_userdata);

				if($adduser){
					$return['user_added'] = 'success';
				}
				else{
					$return['user_added'] = 'failure';
				}
					
			}
			else{
				$return['user_added'] = 'already_exists';
			}

		header('Content-Type: application/json');
		echo json_encode($return);	

		}else{
			redirect(base_url());
		}
	}
	else{
			redirect(base_url());
		}
 }

public function onDeleteUser()
	{
		if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in')==1 && $this->session->userdata('usergroup')==1)
		{
		   if($this->input->is_ajax_request() && $this->input->server('REQUEST_METHOD')=='POST'){

			$user_id = xss_clean($this->input->post('userid'));
			$userdata = $this->am->getUserData(array('user_id'  => $user_id),$many=FALSE);

			if($userdata){
				//del
				//$invoiceData = $this->bm->getInvData(array('created_user'  => $user_id),$many=FALSE);
				//$mrData = $this->mm->getMRData(array('created_user'  => $user_id),$many=FALSE);

				//if(empty($invoiceData) && empty($mrData)){
					
					$deluser = $this->am->delUser(array('user_id' => $user_id));

					if($deluser){
						$return['user_deleted'] = 'success';
					}
					else{
						$return['user_deleted'] = 'failure';
					}
				// }
				// else{
				// 	$return['user_deleted'] = 'billed';
				// }

				
					
			}
			else{
				$return['user_deleted'] = 'not_exists';
			}

		header('Content-Type: application/json');
		echo json_encode($return);	

		}else{
			redirect(base_url());
		}
	}
 }


}