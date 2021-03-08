<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
 
class Export_model extends CI_Model {
    // get employee list
    public function employeeList() {
        $this->db->select('*');
        $this->db->from('import as e');
        $query = $this->db->get();
        return $query->result_array();
    }
}
?>