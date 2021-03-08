<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Sms extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library('excel');
	}

	public function index()
	{
		// if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in') == 1) {
		$this->data['page_title'] = 'Send Bulk SMS';

		if (!empty(xss_clean($this->uri->segment(3)))) {
			$read_inp = xss_clean($this->uri->segment(3));

			$count_str = strlen($read_inp);
			if ($count_str == 36) {
				//assigned user
				$userData = $this->mm->getUsersData($param = array('id' => $read_inp));

				if (!empty($userData)) {
					$leadSourceData = $this->smm->getLCstmData($select = 'DISTINCT(orion_lead_source_c)', $param = array('leads.assigned_user_id' => $read_inp, 'orion_lead_source_c !=' => '', 'leads.deleted' => '0'), $order_by = 'orion_lead_source_c', $order = 'ASC');
					//print_obj($ownerData);die;
					if (!empty($leadSourceData)) {
						foreach ($leadSourceData as $key => $value) {
							$this->data['leadsource_data'][] = array(
								'orion_lead_source'  => $value->orion_lead_source_c
							);
						}
					} else {
						$this->data['leadsource_data'] = '';
					}
					$this->data['assigned'] = $read_inp;
				} else {
					$this->data['leadsource_data'] = '';
					$this->data['assigned'] = '';
				}
				$this->data['owner'] = 'assigned_user';
				$this->data['owner_data'] = '';
			} else if ($read_inp == 'db209113fed6e673ffb7a19d1a5206b7' || $read_inp == 'e4c75152da2adb01d664c1d5a236431f') {

				//pradipta and pratibha
				$param = 'owner_c in("Ankita_Mukherjee", "Nabanita_Banerjee", "Narendranath_Paul", "Somnath_Acharya", "Debprasad_Saha", "Rajeswari_Banerjee", "Subhojit_Chatterjee") AND leads.deleted = 0';
				$ownerFind = $this->smm->getLCstmData($select = 'DISTINCT(owner_c)', $param);


				//print_obj($ownerFind);die;
				if (!empty($ownerFind)) {
					foreach ($ownerFind as $key => $value) {
						$this->data['owner_data'][] = array(
							'owner'  => $value->owner_c
						);
					}

					$param2 = 'owner_c in("Ankita_Mukherjee", "Nabanita_Banerjee", "Narendranath_Paul", "Somnath_Acharya", "Debprasad_Saha", "Rajeswari_Banerjee", "Subhojit_Chatterjee") AND orion_lead_source_c !="" AND leads.deleted = 0';
					$leadSourceData = $this->smm->getLCstmData($select = 'DISTINCT(orion_lead_source_c)', $param2, $order_by = 'orion_lead_source_c', $order = 'ASC');

					if (!empty($leadSourceData)) {
						foreach ($leadSourceData as $key => $value) {
							$this->data['leadsource_data'][] = array(
								'orion_lead_source'  => $value->orion_lead_source_c
							);
						}
					} else {
						$this->data['leadsource_data'] = '';
					}
				} else {
					$this->data['owner_data'] = '';
					$this->data['leadsource_data'] = '';
				}

				$this->data['owner'] = 'special_user';
				$this->data['assigned'] = '';
			} else {
				//owner
				$ownerFind = $this->smm->getLCstmData($select = 'DISTINCT(owner_c)', $param = array('owner_c' => $read_inp, 'leads.deleted' => '0'));
				//print_obj($ownerData);die;
				if (!empty($ownerFind)) {
					$leadSourceData = $this->smm->getLCstmData($select = 'DISTINCT(orion_lead_source_c)', $param = array('owner_c' => $read_inp, 'orion_lead_source_c !=' => '', 'leads.deleted' => '0'), $order_by = 'orion_lead_source_c', $order = 'ASC');

					if (!empty($leadSourceData)) {
						foreach ($leadSourceData as $key => $value) {
							$this->data['leadsource_data'][] = array(
								'orion_lead_source'  => $value->orion_lead_source_c
							);
						}
					} else {
						$this->data['leadsource_data'] = '';
					}
				} else {
					$this->data['leadsource_data'] = '';
				}

				$this->data['owner'] = $read_inp;
				$this->data['assigned'] = '';
				$this->data['owner_data'] = '';
			}


			//$this->data['owner_data'] = '';
		} else {
			$this->data['owner'] = 'ALL';
			$ownerData = $this->smm->getLCstmData($select = 'DISTINCT(owner_c)', $param = array('owner_c !=' => '', 'leads.deleted' => '0'));
			//print_obj($ownerData);die;
			if (!empty($ownerData)) {
				foreach ($ownerData as $key => $value) {
					$this->data['owner_data'][] = array(
						'owner'  => $value->owner_c
					);
				}
			} else {
				$this->data['owner_data'] = '';
			}

			$leadSourceData = $this->smm->getLCstmData($select = 'DISTINCT(orion_lead_source_c)', $param = array('orion_lead_source_c !=' => '', 'leads.deleted' => '0'), $order_by = 'orion_lead_source_c', $order = 'ASC');
			//print_obj($ownerData);die;
			if (!empty($leadSourceData)) {
				foreach ($leadSourceData as $key => $value) {
					$this->data['leadsource_data'][] = array(
						'orion_lead_source'  => $value->orion_lead_source_c
					);
				}
			} else {
				$this->data['leadsource_data'] = '';
			}
			$this->data['assigned'] = '';
		}

		$bmData = $this->smm->getLCstmData($select = 'DISTINCT(business_model_c)', $param = array('business_model_c !=' => '', 'leads.deleted' => '0'), $order_by = 'business_model_c', $order = 'ASC');
		//print_obj($ownerData);die;
		if (!empty($bmData)) {
			foreach ($bmData as $key => $value) {
				$this->data['bm_data'][] = array(
					'business_model'  => $value->business_model_c
				);
			}
		} else {
			$this->data['bm_data'] = '';
		}

		//lead stage starts
		$leadStageData = $this->smm->getLCstmData($select = 'DISTINCT(lead_stage_c)', $param = array('lead_stage_c !=' => '', 'leads.deleted' => '0'), $order_by = 'lead_stage_c', $order = 'ASC');
			//print_obj($ownerData);die;
			if (!empty($leadStageData)) {
				foreach ($leadStageData as $key => $value) {
					$this->data['leadstage_data'][] = array(
						'lead_stage_c'  => $value->lead_stage_c
					);
				}
			} else {
				$this->data['leadstage_data'] = '';
			}
		//lead stage ends

		//Delivery Report Status API starts
		//if ($balance) {

		$username = urlencode('ORION');
		$password = urlencode('ORION');

		$url = "http://egromsg.egro.in/http-credit.php?username=$username&password=$password&route_id=2";

		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_POST, 1);                //0 for a get request
		curl_setopt($curl, CURLOPT_POSTFIELDS, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 3);
		curl_setopt($curl, CURLOPT_TIMEOUT, 20);

		$this->data['sms_balance'] = curl_exec($curl);
		//$response = 'test';
		//print_obj($delivery_response);die;                                                                 
		curl_close($curl);

		//}
		//Delivery Report Status API starts

		$this->load->view('main/vw_sendsms', $this->data, false);
		// } else {
		// 	redirect(base_url());
		// }
	}

	public function onGetFilteredLeads()
	{
		// if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in')==1)
		// {
		if ($this->input->is_ajax_request() && $this->input->server('REQUEST_METHOD') == 'POST') {

			$date_from = xss_clean($this->input->post('date_from'));
			$date_to = xss_clean($this->input->post('date_to'));
			$owner = xss_clean($this->input->post('owner'));
			$assigned = xss_clean($this->input->post('assigned'));
			$business_model = xss_clean($this->input->post('business_model'));
			$orion_lead_source = xss_clean($this->input->post('orion_lead_source'));
			$owner_get = xss_clean($this->input->post('owner_get'));
			$sms_status = xss_clean($this->input->post('sms_status'));
			$lead_stage = xss_clean($this->input->post('lead_stage'));
			$param = array();

			if ($owner_get == 'ALL' || $owner_get == 'special_user') {
				$cond = $date_from != '' ||  $date_to != '' || $owner != '' || $business_model != '' || $orion_lead_source != '' || $lead_stage != '';
			} else {
				$cond = $date_from != '' ||  $date_to != '' || $business_model != '' || $orion_lead_source != '' || $sms_status != '' || $lead_stage != '';
			}

			if ($cond) {

				if ($date_from != '' && $date_to != '') {
					$date_from = date_create($date_from);
					$date_from = date_format($date_from, "Y-m-d");

					$date_to = date_create($date_to);
					$date_to = date_format($date_to, "Y-m-d");

					$par = array(
						'date(ADDTIME(leads.date_entered, TIME("05:30:00"))) >=' => $date_from,
						'date(ADDTIME(leads.date_entered, TIME("05:30:00"))) <=' => $date_to
					); //to match IST
					$param = array_merge($param, $par);
				}

				if ($owner != '') {
					$par = array(
						'leads_cstm.owner_c' => $owner
					);

					$param = array_merge($param, $par);
				}

				if ($business_model != '') {
					$par = array(
						'leads_cstm.business_model_c' => $business_model
					);
					$param = array_merge($param, $par);
				}

				if ($orion_lead_source != '') {
					$par = array(
						'leads_cstm.orion_lead_source_c' => $orion_lead_source
					);
					$param = array_merge($param, $par);
				}

				if ($assigned != '') {
					$par = array(
						'leads.assigned_user_id' => $assigned
					);
					$param = array_merge($param, $par);
				}

				if ($lead_stage != '') {
					$par = array(
						'leads_cstm.lead_stage_c' => $lead_stage
					);
					$param = array_merge($param, $par);
				}



				$par = array(
					'leads.deleted' => 0
				);
				$param = array_merge($param, $par);
				//print_obj($param);

				if ($sms_status != '' && $sms_status == '1') {
					//sent
					$leadsDataTab = $this->smm->getFilteredLeadsData($param, 'inner');
					$param_send = $param;
					$par2 = array(
						'sms_status' => 'Sent'
					);
					$param_send = array_merge($param_send, $par2);
				} else if ($sms_status != '' && $sms_status == '2') {
					//not sent
					$add_where = "(`bulksms_tmp`.`msg_body` = '' or `bulksms_tmp`.`msg_body` is null)";

					$leadsDataTab = $this->smm->getFilteredLeadsData($param, 'left', $add_where);

					$param_send = $param;
					$par2 = array(
						'sms_status' => 'Not Sent'
					);
					$param_send = array_merge($param_send, $par2);
				} else {
					$leadsDataTab = $this->smm->getFilteredLeadsData($param, 'left');
					$param_send = $param;
				}
				//print_obj($leadsDataTab);die;

				if (!empty($leadsDataTab)) {
					foreach ($leadsDataTab as $key => $value) {
						$return['tableData'][] = array(
							'leadid'  => $value->id,
							'dateentered'  => $value->date_entered,
							'firstname'  => ($value->first_name != '') ? $value->first_name : '',
							'lastname'  => ($value->last_name != '') ? $value->last_name : '',
							'phonework'  => ($value->phone_work != '') ? $value->phone_work : '',
							'phonemobile'  => ($value->phone_mobile != '') ? $value->phone_mobile : '',
							'leadorigin'  => ($value->lead_origin_c != '') ? $value->lead_origin_c : '',
							'orionleadsource'  => ($value->orion_lead_source_c != '') ? $value->orion_lead_source_c : '',
							'leademail'  => ($value->lead_email_c != '') ? $value->lead_email_c : '',
							'leadstagedate'  => ($value->lead_stage_date_c != '') ? $value->lead_stage_date_c : '',
							'businessmodel'  => ($value->business_model_c != '') ? $value->business_model_c : '',
							'owner'  => ($value->owner_c != '') ? $value->owner_c : '',
							'leadstage'  => ($value->lead_stage_c != '') ? $value->lead_stage_c : '',
							'last_sent_sms_dt'  => ($value->last_sentsms_dt != '') ? $value->last_sentsms_dt : '',
							'last_sent_sms_body'  => ($value->last_sentsms_body != '') ? $value->last_sentsms_body : '',
							'sms_count'  => ($value->sms_count != '') ? $value->sms_count : ''
						);
					}

					//export

					// create file name
					$fileName = 'BulkSMSReport-' . dtime2 . '.xlsx';
					$filepath = ABS_PATH . 'ExportedData/' . $fileName;
					$downfpath = base_url() . 'common/ExportedData/' . $fileName;
					//echo $filepath ;die;

					$objPHPExcel = new PHPExcel();
					$objPHPExcel->setActiveSheetIndex(0);
					// set Header
					$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'DateCreated');
					$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Name');
					$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Email');
					$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Phone');
					$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'BusinessModel');
					$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Owner');
					$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'LeadOrigin');
					$objPHPExcel->getActiveSheet()->SetCellValue('H1', 'LeadOrigin');
					$objPHPExcel->getActiveSheet()->SetCellValue('I1', 'OrionLeadSource');
					$objPHPExcel->getActiveSheet()->SetCellValue('J1', 'LastSentDate');
					$objPHPExcel->getActiveSheet()->SetCellValue('K1', 'LastSentSMS');
					$objPHPExcel->getActiveSheet()->SetCellValue('L1', 'SMS Count');

					// set Row
					$rowCount = 2;
					foreach ($leadsDataTab as $key => $value) {
						$name = (($value->first_name != '') ? $value->first_name : '') . ' '. (($value->last_name != '') ? $value->last_name : '');
						$phone = ($value->phone_work != '' && $value->phone_work != '-') ? $value->phone_work : $value->phone_work;

						$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $value->date_entered);
						$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $name);
						$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $value->lead_email_c);
						$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $phone);
						$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, ($value->business_model_c != '') ? $value->business_model_c : '');
						$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, ($value->owner_c != '') ? $value->owner_c : '');
						$objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, ($value->lead_origin_c != '') ? $value->lead_origin_c : '');
						$objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, ($value->lead_stage_c != '') ? $value->lead_stage_c : '');
						$objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, ($value->orion_lead_source_c != '') ? $value->orion_lead_source_c : '');
						$objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, ($value->last_sentsms_dt != '') ? $value->last_sentsms_dt : '');
						$objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, ($value->last_sentsms_body != '') ? $value->last_sentsms_body : '');
						$objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, ($value->sms_count != '') ? $value->sms_count : '');
						$rowCount++;
					}
					$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
					$objWriter->save($filepath);
					// download file
					$return['downpath'] = $downfpath;
				} else {
					$return['tableData'] = '';
					$return['downpath'] = '';
				}

				//filtered by
				//$param_send = $param;
				unset($param_send['leads.assigned_user_id']);
				unset($param_send['leads.deleted']);
				$return['filterData'] = implode(', ', $param_send);
				$return['success'] = '1';
				$return['error_msg'] = '';
			} else {
				$return['success'] = '0';
				$return['error_msg'] = 'Please select at least one filter!';
			}


			header('Content-Type: application/json');
			echo json_encode($return);
		} else {
			redirect(base_url());
		}
		// }
		// else{
		// 	redirect(base_url());
		// }  
	}

	public function onSendBulkSMS()
	{
		if ($this->input->is_ajax_request() && $this->input->server('REQUEST_METHOD') == 'POST') {
			$this->form_validation->set_rules('phone_nos', 'Phone No', 'trim|required|xss_clean|htmlentities');
			//$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
			$this->form_validation->set_rules('sms_body', 'SMS Body', 'trim|required|xss_clean|htmlentities');

			if ($this->form_validation->run() == FALSE) {
				$this->form_validation->set_error_delimiters('', '');
				$return['msg'] = validation_errors();
				$return['success'] = '0';
			} else {
				$name 	=	xss_clean($this->input->post('phone_nos'));
				//$email =	xss_clean($this->input->post('email'));
				$phone =	xss_clean($this->input->post('phone_nos'));
				$email = '';
				$sms_body =	xss_clean($this->input->post('sms_body'));
				$owner_get = xss_clean($this->input->post('owner_get'));
				$assigned = xss_clean($this->input->post('assigned'));
				$msg = '';
				//$rand_otp = random_numbs(6);
				$sms_arr = json_decode($this->input->post('sms_arr'));
				$i = 0;
				$limit = 1000; //set limit fro max selection
				$count_arr = count((array)$sms_arr);

				//print_obj($sms_arr);

				//email starts
				if ($email != '') {
					//mail

					$config = array(
						'mailtype' => 'html',
						'charset' => 'utf-8',
						'wordwrap' => TRUE
					);
					$this->email->initialize($config);

					$subject = 'Mail - OrionCRM';
					$msg .= '<p>Hi ' . $name . ',</p>';
					$msg .= '<p>Here is your OTP for Email Verification: <b> </b> </p>
		   		  			<p>Dated: ' . dtime . '</p>
							<p>*This is a system generated e-mail please do not reply to this mail.*</p>';



					$this->email->set_newline("\r\n");
					$toList = array($email);
					//$toList = array('snigdho@orionedutech.com');
					//$cc = 'snigdho@orionedutech.com';

					$this->email->from(SOURCE_EMAIL, 'OrionCRM');
					//$this->email->reply_to('snigdho@orionedutech.com', 'STH');
					$this->email->to($toList);
					//$this->email->cc($cc);
					$this->email->subject($subject);
					$this->email->message($msg);

					if ($this->email->send()) {
						$return['mailsent'] = 'success';
						$return['success'] = '1';
						//$return['otp_val'] = $rand_otp;
					} else {
						$return['mailsent'] = 'failure_mail';
						$return['success'] = '0';
						$return['error'] = 'Email id is incorrect!';
						//show_error($this->email->print_debugger());
					}
				}
				//email ends

				//sms starts
				if (!empty($sms_arr)) {
					if ($count_arr < $limit) {

						foreach ($sms_arr as $key => $value) {
							//echo 'id: '.$value->rec_id;
							//sms api
							$username = urlencode('ORION');
							$password = urlencode('ORION');
							$senderid = urlencode('OSCGPL');
							$phone_no = substr($value->sphone, -10);
							$phone 	  = urlencode($phone_no);
							$message  = urlencode($sms_body);

							$url = "http://egromsg.egro.in/http-api.php?username=$username&password=$password&senderid=$senderid&route=2&number=$phone&message=$message";

							$curl = curl_init();

							curl_setopt($curl, CURLOPT_URL, $url);
							curl_setopt($curl, CURLOPT_POST, 1);                //0 for a get request
							curl_setopt($curl, CURLOPT_POSTFIELDS, $url);
							curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
							curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 3);
							curl_setopt($curl, CURLOPT_TIMEOUT, 20);

							$response = curl_exec($curl);
							//$response = 'test';
							//print_obj($response);                                                                 
							curl_close($curl);
							//end sms api

							//Delivery Report Status API starts
							if ($response != 'No Numbers Found') {

								$msg_id = str_replace("msg-id : ", "", $response);
								$url = "http://egromsg.egro.in/http-dlr.php?username=$username&password=$password&msg_id=$msg_id";

								$curl = curl_init();

								curl_setopt($curl, CURLOPT_URL, $url);
								curl_setopt($curl, CURLOPT_POST, 1);                //0 for a get request
								curl_setopt($curl, CURLOPT_POSTFIELDS, $url);
								curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
								curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 3);
								curl_setopt($curl, CURLOPT_TIMEOUT, 20);

								$delivery_response = curl_exec($curl);
								//print_obj($delivery_response);die;                                                                 
								curl_close($curl);
							} else {
								$delivery_response = $response;
							}
							//Delivery Report Status API starts

							if ($owner_get == 'ALL') {
								$user = 'admin';
							} elseif ($owner_get == 'assigned_user') {
								$user = $assigned;
							} else {
								$user = $owner_get;
							}

							$statsArray[$i] = array(
								'leads_id' => $value->rec_id,
								'actual_phone' => $value->sphone,
								'sent_phone' => $phone_no,
								'name' => $value->sname,
								'email' => $value->smail,
								'msg_body' => $sms_body,
								'sent_by' => $user,
								'api_response' => $response,
								'delivery_response' => $delivery_response,
								'dtime' => dtime,
								'ip' => $this->input->ip_address(),
							);
							$statAdded = $this->smm->addSmsStats($statsArray[$i]);
							$i++;
						} //foreach ends

						if (!empty($statsArray)) {
							$return['success'] = '1';
							$return['response'] = $statsArray;
						} else {
							$return['success'] = '0';
							$return['msg'] = 'Please try again!!';
						}
					} else {
						$return['success'] = '0';
						$return['msg'] = 'You have exceeded the selection limit: ' . $limit . '!!';
					}
				} else {
					$return['success'] = '0';
					$return['msg'] = 'No data received. Please try again!!';
				}
				//sms ends

			}
		} else {
			$return['redirect_url'] = base_url();
		}

		header('Content-Type: application/json');

		echo json_encode($return);
	}
}
