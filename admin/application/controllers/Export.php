<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Export extends CI_Controller {

	public function __construct() {
		parent:: __construct();
 		$this->load->library('excel');
	}

	// export xlsx|xls file
    public function index() {
    	if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in')==1)
		{
	       //
	    }
		else{
			redirect(base_url());
		}
    }


    	// create xlsx
    public function onGetBMXLSX() {
    	if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in')==1)
		{
			if($this->input->is_ajax_request() && $this->input->server('REQUEST_METHOD')=='POST'){
				// create file name
				$fileName = 'BMReport-'.dtime2.'.xlsx'; 
				$filepath = ABS_PATH.'ExportedData/'.$fileName; 
				$downfpath = base_url().'common/ExportedData/'.$fileName; 
				//echo $filepath ;die;

				$date_from = xss_clean($this->input->post('date_from'));
				$date_to = xss_clean($this->input->post('date_to'));
				// $business_model = xss_clean($this->input->post('business_model'));
				
				$date_from=date_create($date_from);
				$date_from = date_format($date_from,"Y-m-d");

				$date_to=date_create($date_to);
				$date_to = date_format($date_to,"Y-m-d");
				
				$param = array(
					'date(ADDTIME(leads.date_entered, TIME("05:30:00"))) >=' => $date_from,
					'date(ADDTIME(leads.date_entered, TIME("05:30:00"))) <=' => $date_to,
					'leads.deleted' => 0
				);//to match IST

				$leadsData = $this->mm->getLeadsReport($p=$param,$many=TRUE);
				//print_obj($leadsData);die;

				if(!empty($leadsData)){
					$objPHPExcel = new PHPExcel();
					$objPHPExcel->setActiveSheetIndex(0);
					
					$rowCount = 0;
					$sum_total_leads = 0;
					$sum_total_not_called_leads = 0;
					$sum_total_called_leads = 0;
					$sum_total_converted_leads = 0;
					$sum_converted_leads_per = 0;

					$owner_name = '';
					$owner_name_sum = '';
					$header_title = '';

					foreach ($leadsData as $value) {
						

							if($header_title != $value->Business_model_c){
								if($value->Business_model_c == 'b2b'){
									$bm_name = 'B2B';
								}elseif($value->Business_model_c == 'b2c'){
									$bm_name = 'B2C';
								}else{
									$bm_name = 'Others';
								}
								$rowCount++;
								$objPHPExcel->getActiveSheet()->SetCellValue('A' . ++$rowCount, $bm_name.' LEAD SUMMARY');
								$header_title = $value->Business_model_c;
								//$rowCount++;
							}

									// set Header
									if($value->owner_c != $owner_name){
										$rowCount++;
										$objPHPExcel->getActiveSheet()->SetCellValue('A' . ++$rowCount, $value->owner_c);
										$objPHPExcel->getActiveSheet()->SetCellValue('A' . ++$rowCount, 'Date');
										$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, 'Total Leads');
										$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, 'Not called leads');
										$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, 'Called leads');
										$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, 'Converted leads');
										$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, 'Converted leads %');
										
										$rowCount++;
										$owner_name = $value->owner_c;
									}

									// set Row
									$converted_leads_per= (round(($value->total_converted_leads/$value->total_leads)*100, 2));
									$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $value->date_entered);
									$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $value->total_leads);
									$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $value->total_not_called_leads);
									$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $value->total_called_leads);
									$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $value->total_converted_leads);
									$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $converted_leads_per);
									$rowCount++;

									// set total
									if($value->owner_c != $owner_name_sum){
										$sum_total_leads = 0;
										$sum_total_not_called_leads = 0;
										$sum_total_called_leads = 0;
										$sum_total_converted_leads = 0;
										$sum_converted_leads_per = 0;
									}
										$sum_total_leads += $value->total_leads;
										$sum_total_not_called_leads += $value->total_not_called_leads;
										$sum_total_called_leads += $value->total_called_leads;
										$sum_total_converted_leads += $value->total_converted_leads;
										$sum_converted_leads_per = round(($sum_total_converted_leads/$sum_total_leads)*100, 2);

										$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, 'Total');
										$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $sum_total_leads);
										$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $sum_total_not_called_leads);
										$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $sum_total_called_leads);
										$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $sum_total_converted_leads);
										$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $sum_converted_leads_per);

										$owner_name_sum = $value->owner_c;
										//$rowCount++;
									
						
					}
					$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
					$objWriter->save($filepath);
					// download file
					//header("Content-Type: application/vnd.ms-excel");
					//redirect($downfpath);
					$return['export'] = 'success';
					$return['downpath'] = $downfpath;
					$return['tableData'] = $leadsData;
				}else{
					$return['export'] = 'failure';
					$return['downpath'] = 0;
				}

			 header('Content-Type: application/json');
			 echo json_encode($return);	
	  		}
	  		else{
				redirect(base_url());
			}      
	    }
		else{
			redirect(base_url());
		}  
	}

	public function onGetBMXLSXOpen() {
    	// if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in')==1)
		// {
			if($this->input->is_ajax_request() && $this->input->server('REQUEST_METHOD')=='POST'){
				// create file name
				$fileName = 'BMReport-'.dtime2.'.xlsx'; 
				$filepath = ABS_PATH.'ExportedData/'.$fileName; 
				$downfpath = base_url().'common/ExportedData/'.$fileName; 
				//echo $filepath ;die;

				$date_from = xss_clean($this->input->post('date_from'));
				$date_to = xss_clean($this->input->post('date_to'));
				$owner = xss_clean($this->input->post('owner'));
				// $business_model = xss_clean($this->input->post('business_model'));
				
				$date_from=date_create($date_from);
				$date_from = date_format($date_from,"Y-m-d");

				$date_to=date_create($date_to);
				$date_to = date_format($date_to,"Y-m-d");
				
				if($owner == 'ALL'){
					$param = array(
						'date(ADDTIME(leads.date_entered, TIME("05:30:00"))) >=' => $date_from,
						'date(ADDTIME(leads.date_entered, TIME("05:30:00"))) <=' => $date_to,
						'leads.deleted' => 0
					);//to match IST
				}else{
					$param = array(
						'date(ADDTIME(leads.date_entered, TIME("05:30:00"))) >=' => $date_from,
						'date(ADDTIME(leads.date_entered, TIME("05:30:00"))) <=' => $date_to,
						'leads.deleted' => 0,
						'leads_cstm.owner_c' => $owner
					);//to match IST
				}

				$leadsDataTab = $this->mm->getLeadsReportOpen($p=$param,$many=TRUE);
				$leadsData = $this->mm->getLeadsReport($p=$param,$many=TRUE);
				//print_obj($leadsData);die;

				if(!empty($leadsData)){
					$objPHPExcel = new PHPExcel();
					$objPHPExcel->setActiveSheetIndex(0);
					
					$rowCount = 0;
					$sum_total_leads = 0;
					$sum_total_not_called_leads = 0;
					$sum_total_called_leads = 0;
					$sum_total_converted_leads = 0;
					$sum_converted_leads_per = 0;

					$owner_name = '';
					$header_title = '';

					if($owner == 'ALL'){
						//admin
						$owner_name_sum = '';
						foreach ($leadsData as $value) {
							

								if($header_title != $value->Business_model_c){
									
									$bm_name = strtoupper($value->Business_model_c);
									$rowCount++;
									$this->cellColor($objPHPExcel, 'A'.++$rowCount, $bm_name.' LEAD SUMMARY', '');
									$header_title = $value->Business_model_c;
									//$rowCount++;

								}

										// set Header
										if($value->owner_c != $owner_name){
											$rowCount++;
											$this->cellColor($objPHPExcel, 'A'.++$rowCount, $value->owner_c, '404040');
											$this->cellColor($objPHPExcel, 'A'.++$rowCount, 'Date', 'e86c1e');
											$this->cellColor($objPHPExcel, 'B'.$rowCount, 'Total Leads' , 'e86c1e');
											$this->cellColor($objPHPExcel, 'C'.$rowCount, 'Not called leads', 'e86c1e');
											$this->cellColor($objPHPExcel, 'D'.$rowCount, 'Called leads', 'e86c1e');
											$this->cellColor($objPHPExcel, 'E'.$rowCount, 'Converted leads', 'e86c1e');
											$this->cellColor($objPHPExcel, 'F'.$rowCount, 'Converted leads %', 'e86c1e');
											
											$rowCount++;
											$owner_name = $value->owner_c;
										}

										// set Row
										$converted_leads_per= (round(($value->total_converted_leads/$value->total_leads)*100, 2));
										$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $value->date_entered);
										$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $value->total_leads);
										$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $value->total_not_called_leads);
										$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $value->total_called_leads);
										$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $value->total_converted_leads);
										$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $converted_leads_per);
										$rowCount++;

										// set total
										if($value->owner_c != $owner_name_sum){
											$sum_total_leads = 0;
											$sum_total_not_called_leads = 0;
											$sum_total_called_leads = 0;
											$sum_total_converted_leads = 0;
											$sum_converted_leads_per = 0;
										}
											$sum_total_leads += $value->total_leads;
											$sum_total_not_called_leads += $value->total_not_called_leads;
											$sum_total_called_leads += $value->total_called_leads;
											$sum_total_converted_leads += $value->total_converted_leads;
											$sum_converted_leads_per = round(($sum_total_converted_leads/$sum_total_leads)*100, 2);

											$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, 'Total');
											$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $sum_total_leads);
											$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $sum_total_not_called_leads);
											$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $sum_total_called_leads);
											$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $sum_total_converted_leads);
											$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $sum_converted_leads_per);

											$owner_name_sum = $value->owner_c;
											//$rowCount++;
										
							
						}
					}else{
						//owner wise
						$header_title_sum = '';
						foreach ($leadsData as $value) {
								
										// set Header
										if($value->owner_c != $owner_name){
											$this->cellColor($objPHPExcel, 'A'.++$rowCount, $value->owner_c, '404040');
											$this->cellColor($objPHPExcel, 'A'.++$rowCount, 'Date', 'e86c1e');
											$this->cellColor($objPHPExcel, 'B'.$rowCount, 'Total Leads' , 'e86c1e');
											$this->cellColor($objPHPExcel, 'C'.$rowCount, 'Not called leads', 'e86c1e');
											$this->cellColor($objPHPExcel, 'D'.$rowCount, 'Called leads', 'e86c1e');
											$this->cellColor($objPHPExcel, 'E'.$rowCount, 'Converted leads', 'e86c1e');
											$this->cellColor($objPHPExcel, 'F'.$rowCount, 'Converted leads %', 'e86c1e');
											
											$rowCount++;
											$owner_name = $value->owner_c;
										}
										

										if($header_title != $value->Business_model_c){

											$bm_name = strtoupper($value->Business_model_c);
											$rowCount++;
											$this->cellColor($objPHPExcel, 'A'.++$rowCount, $bm_name.' LEAD SUMMARY', '');
											$header_title = $value->Business_model_c;
											$rowCount++;
											
										}

										// set Row
										$converted_leads_per= (round(($value->total_converted_leads/$value->total_leads)*100, 2));
										$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $value->date_entered);
										$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $value->total_leads);
										$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $value->total_not_called_leads);
										$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $value->total_called_leads);
										$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $value->total_converted_leads);
										$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $converted_leads_per);
										$rowCount++;

										// set total
										if($value->Business_model_c != $header_title_sum){
											$sum_total_leads = 0;
											$sum_total_not_called_leads = 0;
											$sum_total_called_leads = 0;
											$sum_total_converted_leads = 0;
											$sum_converted_leads_per = 0;
										}
											$sum_total_leads += $value->total_leads;
											$sum_total_not_called_leads += $value->total_not_called_leads;
											$sum_total_called_leads += $value->total_called_leads;
											$sum_total_converted_leads += $value->total_converted_leads;
											$sum_converted_leads_per = round(($sum_total_converted_leads/$sum_total_leads)*100, 2);

											$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, 'Total');
											$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $sum_total_leads);
											$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $sum_total_not_called_leads);
											$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $sum_total_called_leads);
											$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $sum_total_converted_leads);
											$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $sum_converted_leads_per);

											$header_title_sum = $value->Business_model_c;
											//$rowCount++;
										
							
						}
					}

					$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
					$objWriter->save($filepath);
					// download file
					//header("Content-Type: application/vnd.ms-excel");
					//redirect($downfpath);
					$return['export'] = 'success';
					$return['downpath'] = $downfpath;
					$return['tableData'] = $leadsDataTab;
				}else{
					$return['export'] = 'failure';
					$return['downpath'] = 0;
				}

			 header('Content-Type: application/json');
			 echo json_encode($return);	
	  		}
	  		else{
				redirect(base_url());
			}      
	    // }
		// else{
		// 	redirect(base_url());
		// }  
	}

	public function cellColor($objPHPExcel, $cell, $text, $color){
	
		$styleArray = array(
			'font'  => array(
				'bold'  => true,
				'color' => array('rgb' => $color),
				'size'  => 10,
				'name'  => 'Verdana'
			));
		
		$objPHPExcel->getActiveSheet()->getCell($cell)->setValue($text);
		$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleArray);
		
	}
	


    public function onUploadFbLeads(){
    	if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in')==1)
		{
 
	  	if ($this->input->post('submit')) {
	            
	            $path = ABS_PATH.'uploads/';
	            //echo  $path;die;

	            $config['upload_path'] = $path;
	            $config['allowed_types'] = 'xlsx|xls|csv';
	            $config['remove_spaces'] = TRUE;

	            $this->load->library('upload', $config);
	            $this->upload->initialize($config);   

	            if (!$this->upload->do_upload('uploadFile')) {
	                $error = array('error' => $this->upload->display_errors());
	            } else {
	                $data = array('upload_data' => $this->upload->data());
	            }

	            if(empty($error)){
	              if (!empty($data['upload_data']['file_name'])) {
	                $import_xls_file = $data['upload_data']['file_name'];
	            } else {
	                $import_xls_file = 0;
	            }

	            $inputFileName = $path . $import_xls_file;
	            
	            try {
	                $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
	                $objReader = PHPExcel_IOFactory::createReader($inputFileType);
	                $objPHPExcel = $objReader->load($inputFileName);
	                $allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
	                $flag = true;
	                
	                $xl_length = count($allDataInSheet[1]);
	                //echo $xl_length;die;

	                $my_header = array(
						'Created',
						'Name',
						'Email address',
						'Phone number',
						'Stage',
						'Owner',
						'Source',
						'Labels'
						);
	                 $ar_length = count($my_header);

	               if($xl_length==$ar_length){

	                $excel_header = array(
						$allDataInSheet[1]['A'],
						$allDataInSheet[1]['B'],
						$allDataInSheet[1]['C'],
						$allDataInSheet[1]['D'],
						$allDataInSheet[1]['E'],
						$allDataInSheet[1]['F'],
						$allDataInSheet[1]['G'],
						$allDataInSheet[1]['H']
						);

	                $diff = array_diff($my_header, $excel_header);
	                //print_obj($diff);

	                //print_obj($allDataInSheet);die;
	                if(empty($diff)){
	                	foreach ($allDataInSheet as $value) {
	                  if($flag){
	                    $flag =false;
	                    continue;
	                  }
	                  $data = array(
						'lead_created' => $value['A'],
						'name' => $value['B'],
						'email' => $value['C'],
						'phone' => $value['D'],
						'stage' => $value['E'],
						'owner' => $value['F'],
						'source' => $value['G'],
						'lebels' => $value['H'],
						'added_user' => $this->session->userdata('userid'),
						'created_dtime' => dtime
						);
	                  	//print_obj($data);die;

	                  $chkdata = array(
						'name' => $value['B'],
						'email' => $value['C'],
						'phone' => $value['D']
						);

	                  $leaddata = $this->mm->getFbleadsData($chkdata,$many=FALSE);
	                  if($leaddata){
	                  	$result = $this->mm->updateFBLeads($data,$chkdata);
	                  }else{
	                  	$result = $this->mm->addfbLeads($data);   
	                  }
						
	                }       
    
	                
		                if($result){
		                 $this->data['import_status'] = "success";
		                }else{
		                  $this->data['import_status'] = "error";
		                }             
		 
		                }else{
		                	$this->data['import_status'] = "Mismatch in Excel Column Names!";
		                }
	               }else{
	               		$this->data['import_status'] = "Mismatch in Excel Column Header!";
	               }

	                
	          } catch (Exception $e) {
	               $this->data['import_status'] = 'Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME)
	                        . '": ' .$e->getMessage();
	            }
	          }else{
	              $this->data['import_status'] = 'Error2';
	            }
	            
	            
		    }
		    $this->load->view('main/vw_fbleads', $this->data, false);
	    }
		else{
			redirect(base_url());
		}
	  }
	  


}
