<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *
 */
class Main_model extends MY_Model {


	function __construct(){
		//
	}


	//leads

	public function getLeadsData($param=null,$many=FALSE,$order_by='date_entered',$order='DESC'){
		$this->table='leads';
		if($param!=null && $many==FALSE){
			return $this->get_one($param);
		}
		elseif($param!=null && $many==TRUE){
			return $this->get_many($param,$order_by,$order,FALSE);
		}
		elseif($param==null && $many==TRUE){
			return $this->get_many($param=null,$order_by,$order,FALSE);
		}
		else{
			return $this->get_many();
		}
	}

		public function getLeadsList($param=null,$many=TRUE,$group_by=null,$limit=null, $start=null){

			$this->db->select('leads.*, leads_cstm.*');
	
			$this->db->join('leads_cstm','leads.id=leads_cstm.id_c','inner');
	
			// $this->db->join('mt_category','mt_category.category_id=cat_products_bridge.category_id','left');
	
			if($param!=null){
				$this->db->where($param);
			}
	
			if($group_by!=null){
				$this->db->group_by("leads.".$group_by);
			}
	
			$this->db->order_by("leads.date_entered", "DESC");

			if ($limit != null) {
				$this->db->limit($limit);
			 }

			// if ($limit != '' && $start != '') {
			// 	$this->db->limit($limit, $start);
			//  } else{
			// 	$this->db->limit(100);  
			//  }
	
			$query = $this->db->get('leads');
			//echo $this->db->last_query();die;
			
			if($many != TRUE){
				return $query->row();
			}else{
				return $query->result();
			}
			
	
		}

		public function getLeadsReport($param=null,$many=TRUE,$limit=null, $start=null){

			$this->db->select('`leads_cstm`.Business_model_c,
				`leads_cstm`.owner_c,
				date(ADDTIME(leads.date_entered, TIME("05:30:00"))) AS date_entered,
				count(`leads_cstm`.id_c) AS total_leads,
				count(`leads_cstm`.orion_not_called_c) AS total_not_called_leads,
				count(`leads_cstm`.orion_called_c) AS total_called_leads,
				count(`leads_cstm`.orion_converted_c) AS total_converted_leads');
	
			$this->db->join('leads_cstm','leads.id=leads_cstm.id_c','inner');

	
			if($param!=null){
				$this->db->where($param);
			}
	
			$this->db->group_by("`leads_cstm`.Business_model_c,
				`leads_cstm`.owner_c,
				date(ADDTIME(leads.date_entered, TIME('05:30:00')))");
		
			//$this->db->order_by("leads_cstm.Business_model_c", "DESC");

			if ($limit != null) {
				$this->db->limit($limit);
			 }

			// if ($limit != '' && $start != '') {
			// 	$this->db->limit($limit, $start);
			//  } else{
			// 	$this->db->limit(100);  
			//  }
	
			$query = $this->db->get('leads');
			//echo $this->db->last_query();die;
			
			if($many != TRUE){
				return $query->row();
			}else{
				return $query->result();
			}
			
	
		}

		public function getLeadsReportOpen($param=null,$many=TRUE,$limit=null, $start=null){

			$this->db->select('`leads_cstm`.Business_model_c,
				`leads_cstm`.owner_c,
				count(`leads_cstm`.id_c) AS total_leads,
				count(`leads_cstm`.orion_not_called_c) AS total_not_called_leads,
				count(`leads_cstm`.orion_called_c) AS total_called_leads,
				count(`leads_cstm`.orion_converted_c) AS total_converted_leads');
	
			$this->db->join('leads_cstm','leads.id=leads_cstm.id_c','inner');

	
			if($param!=null){
				$this->db->where($param);
			}
	
			$this->db->group_by("`leads_cstm`.Business_model_c,
				`leads_cstm`.owner_c");
		
			$this->db->order_by("leads_cstm.owner_c", "ASC");

			if ($limit != null) {
				$this->db->limit($limit);
			 }

			// if ($limit != '' && $start != '') {
			// 	$this->db->limit($limit, $start);
			//  } else{
			// 	$this->db->limit(100);  
			//  }
	
			$query = $this->db->get('leads');
			//echo $this->db->last_query();die;
			
			if($many != TRUE){
				return $query->row();
			}else{
				return $query->result();
			}
			
	
		}
	
	
	// public function delLeads($param){
	// 	$this->table='leads';
	// 	return $this->remove($param);
	// }

	//lead source
	public function addLeadSource($data){
		$this->table='orion_lead_source_master';
		return $this->store($data);
	}

	public function getLeadSourceData($param=null,$many=FALSE,$order_by='olsm_id',$order='DESC'){
		$this->table='orion_lead_source_master';
		if($param!=null && $many==FALSE){
			return $this->get_one($param);
		}
		elseif($param!=null && $many==TRUE){
			return $this->get_many($param,$order_by,$order,FALSE);
		}
		elseif($param==null && $many==TRUE){
			return $this->get_many($param,$order_by,$order,FALSE);
		}
		else{
			return $this->get_many();
		}
	}

	public function updateLeadSource($data,$param){
		$this->table='orion_lead_source_master';
		return $this->modify($data,$param);
	}

	public function delLeadSource($param){
		$this->table='orion_lead_source_master';
		return $this->remove($param);
	}

		//users

		public function getUsersData($param = null, $many = FALSE, $order_by = 'user_name ', $order = 'ASC')
		{
			$this->table = 'users';
			if ($param != null && $many == FALSE) {
				return $this->get_one($param);
			} elseif ($param != null && $many == TRUE) {
				return $this->get_many($param, $order_by, $order, FALSE);
			} elseif ($param == null && $many == TRUE) {
				return $this->get_many($param = null, $order_by, $order, FALSE);
			} else {
				return $this->get_many();
			}
		}
//others
		public function get_email_addr_bean_rel($param = null, $many = FALSE, $order_by = 'id ', $order = 'ASC')
		{
			$this->table = 'email_addr_bean_rel';
			if ($param != null && $many == FALSE) {
				return $this->get_one($param);
			} elseif ($param != null && $many == TRUE) {
				return $this->get_many($param, $order_by, $order, FALSE);
			} elseif ($param == null && $many == TRUE) {
				return $this->get_many($param = null, $order_by, $order, FALSE);
			} else {
				return $this->get_many();
			}
		}

		public function get_email_addresses($param = null, $many = FALSE, $order_by = 'id ', $order = 'ASC')
		{
			$this->table = 'email_addresses';
			if ($param != null && $many == FALSE) {
				return $this->get_one($param);
			} elseif ($param != null && $many == TRUE) {
				return $this->get_many($param, $order_by, $order, FALSE);
			} elseif ($param == null && $many == TRUE) {
				return $this->get_many($param = null, $order_by, $order, FALSE);
			} else {
				return $this->get_many();
			}
		}
		
		public function addEmailAddresses($data){
			$this->table='email_addresses';
			return $this->store($data);
		}

		public function add_addr_bean_rel($data){
			$this->table='email_addr_bean_rel';
			return $this->store($data);
		}




}
