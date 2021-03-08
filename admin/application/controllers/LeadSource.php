<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LeadSource extends CI_Controller {

	public function __construct() {
		parent:: __construct();
	}


	public function index()
	{
		if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in')==1 && ($this->session->userdata('usergroup')==1  || $this->session->userdata('usergroup')==2))
		{
            $this->data['page_title'] = 'Orion Lead Source Tagger';
			$leadsourceData = $this->mm->getLeadSourceData($p=array('is_active'=>1),$many=TRUE);
			if($leadsourceData){
				foreach ($leadsourceData as $key => $value) {
					
					$this->data['leadsource_data'][] = array(
						'olsm_id'  => $value->olsm_id,
						'orion_lead_source_c'  => $value->orion_lead_source_c,
                        'business_model_c'  => $value->business_model_c,
                        'added_dtime'  => ($value->added_dtime != '')?$value->added_dtime:'By System'
					);
				}
			}
			else{
				$this->data['leadsource_data'] = '';
			}
			$this->load->view('main/vw_leadsource', $this->data, false);
		

		}
		else{
			redirect(base_url());
		}
	}

	public function onCreateLeadSourceView()
	{
		if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in')==1 && ($this->session->userdata('usergroup')==1  || $this->session->userdata('usergroup')==2)){

			$this->data['page_title'] = 'Add OrionLeadSource and BM Tag';
			
			$bmData = $this->smm->getLCstmData($select='DISTINCT(business_model_c)', $param=array('business_model_c !='=>'', 'leads.deleted' => '0'));
			//print_obj($ownerData);die;
			if ($bmData) {
				foreach ($bmData as $key => $value) {
					$this->data['bm_data'][] = array(
						'business_model'  => $value->business_model_c
					);
				}
			} else {
				$this->data['bm_data'] = '';
			}

			if(!empty(xss_clean($this->uri->segment(2)))){

				$olsm_id = xss_clean($this->uri->segment(2));
				$leadsourceData = $this->mm->getLeadSourceData($p=array('olsm_id'=>$olsm_id),$many=FALSE);
				if($leadsourceData){
						$this->data['leadS_data'] = array(
							'olsm_id'  => $leadsourceData->olsm_id,
							'orion_lead_source_c'  => $leadsourceData->orion_lead_source_c,
                            'business_model_c'  => $leadsourceData->business_model_c,
                            'added_dtime'  => $leadsourceData->added_dtime
						);
				}
				else{
					$this->data['leadS_data'] = '';
				}

				$this->load->view('main/vw_create_leadsource', $this->data, false);
			}
			else{
				$this->load->view('main/vw_create_leadsource', $this->data, false);
			}
			
		}else{
			redirect(base_url());
		}

	}

	public function onCheckDuplicateLeadSource()
	{
		if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in')==1 && ($this->session->userdata('usergroup')==1  || $this->session->userdata('usergroup')==2))
		{
			if($this->input->is_ajax_request() && $this->input->server('REQUEST_METHOD')=='POST'){

				$orion_lead_source_c = xss_clean($this->input->post('orion_lead_source'));

				$is_exists = $this->mm->getLeadSourceData($p=array('orion_lead_source_c'=>$orion_lead_source_c),$many=FALSE);
				if($is_exists){
						$return['is_exists'] = 1;
				}
				else{
					$return['is_exists'] = 0;
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

	public function onCreateLeadSource()
	{
		if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in')==1 && ($this->session->userdata('usergroup')==1  || $this->session->userdata('usergroup')==2))
		{
			if($this->input->is_ajax_request() && $this->input->server('REQUEST_METHOD')=='POST'){

                    $orion_lead_source_c = xss_clean($this->input->post('orion_lead_source'));
                    $business_model_c = xss_clean($this->input->post('business_model'));
					$olsm_id = xss_clean($this->input->post('lead_source_id'));

					if($orion_lead_source_c != '' && $business_model_c != '0'){
						$chkdata = array('orion_lead_source_c'  => $orion_lead_source_c);
						$leadSourceData = $this->mm->getLeadSourceData($chkdata,$many=FALSE);
	
					
						if(empty($leadSourceData) && $olsm_id==0){
							
							$insdata = array(
                                        'orion_lead_source_c'  => $orion_lead_source_c,
                                        'business_model_c'  => $business_model_c,
										'added_dtime'  => dtime,
										'added_by'  => $this->session->userdata('userid')
									);
		
							$added = $this->mm->addLeadSource($insdata);
		
							if($added){
								$return['added'] = 'success';
							}
							else{
								$return['added'] = 'failure';
							}
								
						}
						else{
		
							$updata = array(
										'orion_lead_source_c'  => $orion_lead_source_c,
                                        'business_model_c'  => $business_model_c,
										'edited_dtime'  => dtime,
										'edited_by'  => $this->session->userdata('userid')
									);
							$updated = $this->mm->updateLeadSource($updata,array('olsm_id'  => $olsm_id));
							if($updated){
								$return['updated'] = 'success';
							}
							else{
								$return['updated'] = 'failure';
							}
							
						}
					}else{
                        $return['is_blank'] = 1;
                        $return['bm_val'] = $business_model_c;
                        $return['ls_val'] = $orion_lead_source_c;
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

	public function onDeleteLeadSource()
	{
		if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in')==1 && ($this->session->userdata('usergroup')==1  || $this->session->userdata('usergroup')==2))
			{
			   if($this->input->is_ajax_request() && $this->input->server('REQUEST_METHOD')=='POST'){

				$olsm_id = xss_clean($this->input->post('ldid'));
				$leadsourceData = $this->mm->getLeadSourceData(array('olsm_id'  => $olsm_id),$many=FALSE);

				if($leadsourceData){

                    $updata = array(
                        'is_active'  => 0,
                        'edited_dtime'  => dtime
                    );
                    $del = $this->mm->updateLeadSource($updata,array('olsm_id'  => $olsm_id));
					//del
					//$del = $this->mm->delLeadSource(array('olsm_id' => $olsm_id));

					if($del){
						$return['deleted'] = 'success';
					}
					else{
						$return['deleted'] = 'failure';
					}
						
				}
				else{
					$return['deleted'] = 'not_exists';
				}

			header('Content-Type: application/json');
			echo json_encode($return);	

			}else{
				redirect(base_url());
			}
		}else{
            redirect(base_url());
        }
 	}



}