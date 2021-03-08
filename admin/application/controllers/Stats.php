<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Stats extends CI_Controller {

	public function __construct() {
		parent:: __construct();
	}

	public function index()
	{
		if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in')==1)
		{

			$visitdata = $this->sm->getVisitsData($p=null,$many=TRUE);
			//print_obj($visitdata);die;
			if($visitdata){
				foreach ($visitdata as $key => $value) {
					$this->data['visit_data'][] = array(
						'hits_id'  => $value->hits_id,
						'ip'  => $value->ip,
						'country'  => $value->country,
						'region'  => $value->regionName,
						'city'  => $value->city,
						'hit_source'  => $value->hit_source,
						'hit_dtime'  => $value->hit_dtime
					);
				}
			}
			else{
				$this->data['visit_data'] = '';
			}

			//count visits
			$countVisits = $this->sm->getWebCount();
			if($countVisits){
				$this->data['tot_visits'] = $countVisits->tot_count;
			}
			else{
				$this->data['tot_visits'] = '';
			}

			$this->load->view('stats/vw_stats', $this->data, false);
		

		}
		else{
			redirect(base_url());
		}
	}

	//countvisits
	public function onGetContactUs()
	{
		if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in')==1)
		{

			$countVisits = $this->sm->getWebCount();
			if($countVisits){
				$this->data['visit_data'] = $countVisits->tot_count;
			}
			else{
				$this->data['visit_data'] = '';
			}
			$this->load->view('stats/vw_stats', $this->data, false);
		

		}
		else{
			redirect(base_url());
		}	
		
	}
}