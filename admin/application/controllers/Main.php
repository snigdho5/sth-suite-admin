<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Main extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		//
	}


	//leads
	public function onGetLeadsList()
	{
		if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in') == 1) {
			$this->data['page_title'] = 'Leads';

			$param = array(
				'date(leads.date_entered) <=' =>  date("Y-m-d"),
				'leads.deleted' => 0
			);

			$leadsData = $this->mm->getLeadsList($p = $param, $many = TRUE, null, $limit = 100);
			if ($leadsData) {
				foreach ($leadsData as $key => $value) {
					$this->data['leads_data'][] = array(
						'id'  => $value->id,
						'date_entered'  => $value->date_entered,
						'first_name'  => $value->first_name,
						'last_name'  => $value->last_name,
						'name'  => ($value->first_name . ' ' . $value->last_name),
						'lead_email_c'  => $value->lead_email_c,
						'phone'  => ($value->phone_mobile != '') ? $value->phone_mobile : $value->phone_work,
						'orion_lead_source_c'  => $value->orion_lead_source_c,
						'source_campaign_c'  => $value->source_campaign_c,
						'owner_c'  => $value->owner_c,
						'business_model_c'  => $value->business_model_c
					);
				}
			} else {
				$this->data['leads_data'] = '';
			}
			$this->load->view('main/vw_leads', $this->data, false);
		} else {
			redirect(base_url());
		}
	}

	//bm report
	public function onGetBMReport()
	{
		if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in') == 1) {
			$this->data['page_title'] = 'Report | Business Model Wise';

			$this->load->view('main/vw_bmreport', $this->data, false);
		} else {
			redirect(base_url());
		}
	}


	public function onGetBMReportOpen()
	{
		// if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in')==1)
		// {
		$this->data['page_title'] = 'Report | Leads Summary';

		if (!empty(xss_clean($this->uri->segment(3)))) {
			$this->data['owner'] = xss_clean($this->uri->segment(3));
		} else {
			$this->data['owner'] = 'ALL';
		}

		$this->load->view('main/vw_bmreport_open', $this->data, false);


		// }
		// else{
		// 	redirect(base_url());
		// }
	}

	public function onSendTestMail()
	{
		if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in') == 1) {
			$this->load->library('email');
			// $config = array(
			// 	'mailtype' => 'html',
			// 	'charset'  => 'utf-8',
			// 	'wordwrap' => true
			// );
			$config = array(
				'protocol' => 'smtp',
				'smtp_host' => 'email-smtp.us-east-1.amazonaws.com',
				'smtp_port' => 465,
				'smtp_user' => 'AKIAYWIER7I53IKQ3XRY',
				'smtp_pass' => 'BLFYHgmR1N1X0vgWo6wT+cD0eHehaU0sqQT2hQC+u5Aq',
				'mailtype' => 'html',
				'smtp_crypto' => 'ssl',
				'smtp_timeout' => '4',
				'charset' => 'utf-8',
				'wordwrap' => TRUE
			);
			$this->email->initialize($config);

			$added = 1;

			if ($added) {
				//mail
				$name = 'Snigdho';
				$email = 'snigdho@orionedutech.com';
				$subject = 'Orion Softech - Contact Us | From: ' . $name;

				$msg = "<p>You've got a new a message.</p>
				   <table border=\"1\">

				   <tr>
				   <th>Name</th>
				   </tr>

				   <tr>
				   <td>test</td>
				   </tr>
				   </table>
				   <p>Dated: " . dtime . "</p>
				   <p>*This is a system generated e-mail please do not reply to this mail.*</p>
				   ";

				$this->email->set_newline("\r\n");
				$toList = array($email);
				//$toList = array('tamali@orionedutech.com');
				//$cc = 'snigdho@orionedutech.com';

				$this->email->from(SOURCE_EMAIL, 'Orion Softech');
				//$this->email->reply_to('snigdho@orionedutech.com', 'STH');
				$this->email->to($toList);
				//$this->email->cc($cc);
				$this->email->subject($subject);
				$this->email->message($msg);

				if ($this->email->send()) {
					$this->data['success'] = '1';
					$this->data['status'] = 'Mail sent successfully!';
					$this->data['name'] = $name;
					$this->data['recipient'] = $email;
					$this->data['response'] = '';
				} else {
					$this->data['success'] = '0';
					$this->data['status'] = 'Mail sending is unsuccessful!';
					//$this->data['error'] = $this->email->print_debugger();
					//show_error($this->email->print_debugger());
				}
			}

			$this->data['page_title'] = 'Mail | Test';

			$this->load->view('main/vw_testmail', $this->data, false);
		} else {
			redirect(base_url());
		}
	}


	public function onSendTestSMS()
	{
		if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in') == 1) {
			$this->data['page_title'] = 'SMS | Test';

			//sms api
			$username = urlencode('ORION');
			$password = urlencode('ORION');
			$senderid = urlencode('OSCGPL');
			$phone = urlencode('9831252408');
			$name = 'Indranil';
			$message = urlencode('Hi ' . $name . ', here is your otp: ' . random_numbs(6));

			$url = "http://egromsg.egro.in/http-api.php?username=$username&password=$password&senderid=$senderid&route=2&number=$phone&message=$message";


			$curl = curl_init();

			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_POST, 1);                //0 for a get request
			curl_setopt($curl, CURLOPT_POSTFIELDS, $url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 3);
			curl_setopt($curl, CURLOPT_TIMEOUT, 20);

			$response = curl_exec($curl);
			//print_obj($response);die;                                                                 
			curl_close($curl);
			//end sms api

			if ($response != 'No Numbers Found') {
				$this->data['success'] = '1';
				$this->data['status'] = 'SMS sent successfullly!';
				$this->data['name'] = $name;
				$this->data['recipient'] = $phone;
				$this->data['response'] = $response;
			} else {
				$this->data['success'] = '0';
				$this->data['status'] = 'SMS sending is unsuccessful!';
				$this->data['error'] = 'API Error!!';
			}

			$this->load->view('main/vw_testmail', $this->data, false);
		} else {
			redirect(base_url());
		}
	}

	public function onGetEmailUpdate()
	{
		if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in') == 1) {
			$this->data['page_title'] = 'Email Update';

			$i = 0;

			$leadsData = $this->smm->getLeadsCstmData($param = array('lead_email_c !=' => ''), $many = TRUE);
			//print_obj($leadsData);die;
			if ($leadsData) {
				foreach ($leadsData as $key => $value) {
					// $this->data['leads_data'][] = array(
					// 	'id_c'  => $value->id_c,
					// 	'lead_email_c'  => $value->lead_email_c
					// );

					$randstr = random_strings(30) . substr($value->id_c, -5); //35 char
					$randstr2 = random_strings(30) . substr($value->id_c, -5); //35 char

					$emailRelData = $this->mm->get_email_addr_bean_rel($param = array('bean_id' => $value->id_c));
					if (!empty($emailRelData)) {
						//bean id found

						//check if deleted
						$emailRelData2 = $this->mm->get_email_addr_bean_rel($param = array('bean_id' => $value->id_c, 'deleted'=>0));
						if (!empty($emailRelData2)) {
							$status = 0;
						} else {
							$status = 1;
						}
					} else {
						//bean id not found
						$status = 1;
					}
					//echo $status;die;

					if ($status == 1) {
						$ins_email_addresses = array(
							'id' => $randstr,
							'email_address' => $value->lead_email_c,
							'email_address_caps' => strtoupper($value->lead_email_c),
							'invalid_email' => 0,
							'opt_out' => 0,
							'confirm_opt_in' => 'confirmed-opt-in',
							'date_created' => dtime,
							'date_modified' => dtime
						);


						$added_email_add = $this->mm->addEmailAddresses($ins_email_addresses);


						$ins_addr_bean_rel = array(
							'id' => $randstr2,
							'email_address_id' => $randstr,
							'bean_id' => $value->id_c,
							'bean_module' => 'Leads',
							'primary_address' => 1,
							'reply_to_address' => 0,
							'date_created' => dtime,
							'date_modified' => dtime,
							'deleted' => 0
						);

						$added_email_bean = $this->mm->add_addr_bean_rel($ins_addr_bean_rel);

						echo '<br>Process starts: ' . $i;
						print_obj($ins_email_addresses);
						echo '<br>Added in email addr: ' . $added_email_add;

						print_obj($ins_addr_bean_rel);
						echo '<br>Added in addr_bean_rel: ' . $added_email_bean;
						echo '<br>Process ends' . $i;
					}else{
						echo '<br>Already exists: '. $value->lead_email_c . '('. $value->id_c . ')';
					}


					$i++;
				}
			} else {
				$this->data['leads_data'] = '';
				echo 'not found';
			}
			echo '<br>Process ended.';
			die;
			$this->load->view('main/vw_emailupdate', $this->data, false);
		} else {
			redirect(base_url());
		}
	}
}
