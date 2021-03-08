<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 *
 */
class Sms_model extends MY_Model
{


	function __construct()
	{
		//
	}


	//leads

	public function getLeadsCstmData($param = null, $many = FALSE, $order_by = 'owner_c ', $order = 'ASC')
	{
		$this->table = 'leads_cstm';
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

	public function getLCstmData($select = '*', $param = null, $order_by = 'owner_c', $order = 'ASC', $many = TRUE)
	{

		$this->db->select($select);

		$this->db->join('leads','leads.id=leads_cstm.id_c','inner');

		if ($param != null) {
			$this->db->where($param);
		}

		$this->db->order_by($order_by, $order);

		$query = $this->db->get('leads_cstm');
		//echo $this->db->last_query();die;

		if ($many != TRUE) {
			return $query->row();
		} else {
			return $query->result();
		}
	}

	public function getFilteredLeadsData($param = null, $join = 'left', $add_where = null, $order_by = 'date(ADDTIME(leads.date_entered, TIME("05:30:00")))', $order = 'DESC', $many = TRUE)
	{
		$this->db->select('
				leads.id,
				date(ADDTIME(leads.date_entered, TIME("05:30:00"))) AS date_entered,
				leads.first_name,
				leads.last_name,
				leads.phone_work,
				leads.phone_mobile,
				leads_cstm.*,
				bulksms_tmp.msg_body AS last_sentsms_body,
				bulksms_tmp.dtime AS last_sentsms_dt,
				(select count(*) from bulksms_stats bs1 where bs1.leads_id = bulksms_tmp.leads_id) as sms_count
			');
	
		$this->db->join('leads_cstm','leads.id=leads_cstm.id_c','inner');

		$this->db->join('(SELECT bulksms_stats.leads_id, bulksms_stats.dtime dtime, bulksms_stats.msg_body FROM bulksms_stats where bulksms_stats.dtime = (select max(bs.dtime) from bulksms_stats bs where bulksms_stats.leads_id = bs.leads_id group by bs.leads_id)) AS bulksms_tmp','bulksms_tmp.leads_id=leads.id', $join);

		// $this->db->join('mt_category','mt_category.category_id=cat_products_bridge.category_id','left');

		if($param != null){
			$this->db->where($param);
		}

		if($add_where != null){
			$this->db->where($add_where);
		}
		
		// if($group_by!=null){
			//$this->db->group_by("bulksms_stats.dtime");
		// }

		$this->db->order_by($order_by. ' ' . $order);

		$query = $this->db->get('leads');
		//echo $this->db->last_query();die;
		
		if($many != TRUE){
			return $query->row();
		}else{
			return $query->result();
		}
		
	}

	public function addSmsStats($data){
		$this->table='bulksms_stats';
		return $this->store($data);
	}
}
