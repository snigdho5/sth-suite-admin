<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *
 */
class Stats_model extends MY_Model {


	function __construct(){
		//
	}

	//stats
	public function addVisits($data){
		$this->table='website_hits';
		return $this->store($data);
	}
	public function getVisitsData($param=null,$many=FALSE){
		$this->table='website_hits';
		if($param!=null && $many==FALSE){
			return $this->get_one($param);
		}
		elseif($param!=null && $many==TRUE){
			return $this->get_many($param,$order_by='hits_id',$order='DESC',FALSE);
		}
		elseif($param==null && $many==TRUE){
			return $this->get_many($param,$order_by='hits_id',$order='DESC',FALSE);
		}
		else{
			return $this->get_many();
		}
	}

	public function updateVisits($data,$param){
		$this->table='website_hits';
		return $this->modify($data,$param);
	}

	public function delVisits($param){
		$this->table='website_hits';
		return $this->remove($param);
	}

	public function getWebCountP($param=null){

		if($param!=null){
			$this->db->select('COUNT(*) as tot_count');
			return $this->db->get_where('website_hits',$param)->first_row();
		}

	}

	public function getCountAll($table=null){

		if($table!=null){
			$this->db->select('COUNT(*) as tot_count');
			$this->db->from($table);
			//$this->db->order_by('hits_id', 'DESC');
			//$this->db->limit('1');

			$query = $this->db->get();
			return $query->row();
		}

	}

	public function getWebCount($param=null){

		$this->db->select('COUNT(*) as tot_count');
		$this->db->from('website_hits');
		//$this->db->order_by('hits_id', 'DESC');
		//$this->db->limit('1');

		$query = $this->db->get();
		return $query->row();
	}


}
