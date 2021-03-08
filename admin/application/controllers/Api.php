<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Api extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        //$this->load->library('session');
    }

    public function onGetLeadDetails()
    {
        $jsonData = $this->input->post('oAuth_json');
        $json_Param = $this->input->post('jsonParam');
        //print_obj($this->input->post());

        if (!empty($jsonData)) {
            $decoded = json_decode($jsonData);
            $decodedParam = json_decode($json_Param);
            //print_obj($decodedParam);

            if ($decoded != '' || $decoded != NULL) {
                $sKey = $decoded->sKey;
                $aKey = $decoded->aKey;
                $param = array();

                if ($sKey == secretKey && $aKey == accesskey) {

                    if (!empty($decodedParam)) {
                        if ($decodedParam->lead_email != '') {
                            $par = array(
                                'leads_cstm.lead_email_c' => xss_clean($decodedParam->lead_email)
                            );

                            $param = array_merge($param, $par);
                        }

                        if ($decodedParam->phone_no != '') {
                            $phone_no = substr($decodedParam->phone_no, -10);
                            $par = array(
                                'leads.phone_work' => xss_clean($phone_no)
                            );

                            $param = array_merge($param, $par);
                        }

                        $par = array(
                            'leads.deleted' => 0
                        );
                        $param = array_merge($param, $par);

                        $getData = $this->smm->getLCstmData($select = 'leads_cstm.*, leads.phone_work', $param, $order_by = 'leads_cstm.owner_c', $order = 'ASC', $many = FALSE);
                    } else {
                        // $getData = $this->smm->getLCstmData();
                    }

                    //print_obj($getData);die;

                    if (!empty($getData)) {
                        // foreach ($getData as $key => $value) {
                        $return['leadData'] = array(
                            'lead_id'  => $getData->id_c,
                            'lead_email'  => $getData->lead_email_c,
                            'phone_no'  => $getData->phone_work
                        );
                        // }
                        $return['success'] = '0';
                        $return['message'] = 'Duplicate found in email and phone combination!';
                    } else {
                        $return['leadData'] = '';
                        $return['success'] = '1';
                        $return['message'] = 'No duplicate found in email and phone combination!';
                    }
                } else {
                    $return['success'] = '0';
                    $return['error'] = 'Credentials mismatch!';
                }
            } else {
                $return['success'] = '0';
                $return['error'] = 'JSON data error';
            }
        } else {
            $return['success'] = '0';
            $return['error'] = 'JSON data is empty';
        }

        header('Content-Type: application/json');
        echo json_encode($return);
    }

    public function onCreateLeadFreeReg()
    {
        $jsonData = $this->input->post('oAuth_json');
        $json_Param = $this->input->post('jsonParam');
        //print_obj($this->input->post());

        if (!empty($jsonData) && !empty($json_Param)) {
            $decoded = json_decode($jsonData);
            $decodedParam = json_decode($json_Param);
            //print_obj($decodedParam);die;

            // if ($decoded) {
            $sKey = $decoded->sKey;
            $aKey = $decoded->aKey;

            if ($sKey == secretKey && $aKey == accesskey) {

                // if ($decodedParam != '' || $decodedParam != NULL) {

                //$this->load->database('server_rds');


                if (xss_clean($decodedParam->name) != '' && xss_clean($decodedParam->phone) != '' && xss_clean($decodedParam->email) != '' && xss_clean($decodedParam->lob) != '') {

                    $name = xss_clean($decodedParam->name);
                    $phone = xss_clean($decodedParam->phone);
                    $email = xss_clean($decodedParam->email);
                    $lob = xss_clean($decodedParam->lob);
                    $lead_source_c = 'free_regis_sth_std_app';
                    $ip = $this->input->ip_address();
                    $param = array();

                    if ($ip != '::1' && $ip != '') {
                        $iplocation = getiplocation($ip);
                        if (!empty($iplocation)) {
                            $country = $iplocation->country;
                            $state = $iplocation->regionName;
                            $city = $iplocation->city;
                            //$city = $contact_city;
                        } else {
                            $country = 'N/A';
                            $state = 'N/A';
                            $city = 'N/A';
                        }
                    } else {
                        $country = 'N/A';
                        $state = 'N/A';
                        $city = 'N/A';
                        //$city = $contact_city;
                    }

                    if (strpos($name, " ") !== false) {

                        $splitstr = explode(" ", $name);
                        $fname = $splitstr[0];
                        $lname = trim($name, $splitstr[0]);
                    } else {

                        $fname = $name;
                        $lname = '';
                    }

                    // $addArray = array(
                    //     'name' => $name,
                    //     'phone' => $phone,
                    //     'email' => $email,
                    //     'lead_source' => $lead_source_c,
                    //     //'lob' => $lob,
                    //     'dtime' => dtime,
                    //     'ip' => $this->input->ip_address(),
                    // );

                    // $added = $this->mm->addFreeReg($addArray);

                    //duplicate checking starts
                    if ($email != '') {
                        $par = array(
                            'leads_cstm.lead_email_c' => xss_clean($email)
                        );

                        $param = array_merge($param, $par);
                    }

                    if ($phone != '') {
                        $phone_no = substr($phone, -10);
                        $par = array(
                            'leads.phone_work' => xss_clean($phone_no)
                        );

                        $param = array_merge($param, $par);
                    }

                    $par = array(
                        'leads.deleted' => 0
                    );
                    $param = array_merge($param, $par);

                    $getData = $this->smm->getLCstmData($select = 'leads_cstm.*, leads.phone_work', $param, $order_by = 'leads_cstm.owner_c', $order = 'ASC', $many = FALSE);


                    //duplicate checking ends

                    if (empty($getData)) {
                        $url = SUITE_API_URL;
                        $username = 'admin';
                        $password = 'admin@suite_crm';

                        function call($method, $parameters, $url)
                        {
                            ob_start();
                            $curl_request = curl_init();

                            curl_setopt($curl_request, CURLOPT_URL, $url);
                            curl_setopt($curl_request, CURLOPT_POST, 1);
                            curl_setopt($curl_request, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
                            curl_setopt($curl_request, CURLOPT_HEADER, 1);
                            curl_setopt($curl_request, CURLOPT_SSL_VERIFYPEER, 0);
                            curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, 1);
                            curl_setopt($curl_request, CURLOPT_FOLLOWLOCATION, 0);

                            $jsonEncodedData = json_encode($parameters);

                            $post = array(
                                "method" => $method,
                                "input_type" => "JSON",
                                "response_type" => "JSON",
                                "rest_data" => $jsonEncodedData
                            );

                            curl_setopt($curl_request, CURLOPT_POSTFIELDS, $post);
                            $result = curl_exec($curl_request);
                            curl_close($curl_request);

                            $result = explode("\r\n\r\n", $result, 2);
                            $response = json_decode($result[1]);
                            ob_end_flush();

                            return $response;
                        }

                        //login ---------------------------------------- 
                        $login_parameters = array(
                            "user_auth" => array(
                                "user_name" => $username,
                                "password" => md5($password),
                                "version" => "1"
                            ),
                            "application_name" => "RestTest",
                            "name_value_list" => array(),
                        );

                        $login_result = call("login", $login_parameters, $url);
                        //print_obj($login_result);

                        if (!isset($login_result->name)) {
                            $name_val = $login_result->name_value_list;
                            $user_ar = $name_val->user_id;
                            $user_hash = $user_ar->value;
                            //echo 'id= '.$user_hash.'<br>';

                            //get session id
                            $session_id = $login_result->id;

                            //retrieve fields -------------------------------- 
                            $set_entries_parameters = array(
                                //Session id
                                "session" => $session_id,

                                //The name of the module from which to retrieve records.
                                "module_name" => "Leads",

                                //Record attributes
                                "name_value_lists" => array(
                                    array(
                                        //to update a record
                                        /*
                                                            array(
                                                                "name" => "id",
                                                                "value" => "da0b107d-cfbc-cb08-4f90-50b7b9cb9ad7"
                                                            ),
                                                            */

                                        array(
                                            "name" => "last_name",
                                            "value" => $fname . ' ' . $lname
                                        ),
                                        array(
                                            "name" => "phone_mobile",
                                            "value" => $phone
                                        ),
                                        array(
                                            "name" => "phone_work",
                                            "value" => $phone
                                        ),
                                        array(
                                            "name" => "email1",
                                            "value" => $email
                                        ),
                                        array(
                                            "name" => "lead_email_c",
                                            "value" => $email
                                        ),
                                        array(
                                            "name" => "primary_address_city",
                                            "value" => $city
                                        ),
                                        array(
                                            "name" => "primary_address_state",
                                            "value" => $state
                                        ),
                                        array(
                                            "name" => "primary_address_country",
                                            "value" => $country
                                        ),
                                        array(
                                            "name" => "description",
                                            "value" => ''
                                        ),
                                        array(
                                            "name" => "orion_lead_source_c",
                                            "value" => $lead_source_c
                                        ),
                                        array(
                                            "name" => "assigned_user_id",
                                            "value" => $user_hash
                                        ),
                                        array(
                                            "name" => "lead_stage_date_c",
                                            "value" => date("Y-m-d")
                                        ),
                                        array(
                                            "name" => "lob_c",
                                            "value" => $lob
                                        ),
                                    ),
                                ),
                            );


                            $get_module_fields_result = call("set_entries", $set_entries_parameters, $url);


                            if (!empty($get_module_fields_result)) {
                                //echo '&#10004; Successfully Synced.'; 
                                //print_obj($get_module_fields_result); 
                                $return['api_response'] = $get_module_fields_result;
                                $return['success'] = '1';
                                $return['message'] = 'Lead created!';
                            } else {
                                //echo 'Problem in syncing!'; 
                                //print_obj($get_module_fields_result); 
                                $return['api_response'] = $get_module_fields_result;
                                $return['success'] = '0';
                                $return['message'] = 'Lead is not created!';
                            }
                        } else {
                            //echo (isset($login_result->description))?$login_result->description:'Invalid Login!';
                            $return['api_response'] = '';
                            $return['success'] = '0';
                            $return['message'] = 'Invalid Login!';
                        }
                    } else {
                        $return['api_response'] = '';
                        $return['success'] = '0';
                        $return['message'] = 'Duplicate found in email and phone combination!';
                    }
                } else {
                    $return['api_response'] = '';
                    $return['success'] = '0';
                    $return['message'] = 'misssing required fields!';
                }
                // } else {
                //     $return['api_response'] = '';
                //     $return['success'] = '0';
                //     $return['message'] = 'Empty Param Json!';
                // }
            } else {
                $return['success'] = '0';
                $return['message'] = 'Credentials mismatch!';
            }
            // } else {
            //     $return['success'] = '0';
            //     $return['message'] = 'JSON data error';
            // }
        } else {
            $return['success'] = '0';
            $return['message'] = 'JSON data is empty';
        }

        header('Content-Type: application/json');
        echo json_encode($return);
    }

    public function onGetLeads()
    {
        //$jsonData = $this->input->post('oAuth_json');
        //$json_Param = $this->input->post('jsonParam');
        //print_obj($this->input->post());

        // if (!empty($jsonData)) {
           // $decoded = json_decode($jsonData);
           // $decodedParam = json_decode($json_Param);
            //print_obj($decodedParam);

            // if ($decoded != '' || $decoded != NULL) {
               // $sKey = $decoded->sKey;
               // $aKey = $decoded->aKey;
              //  $param = array();

                // if ($sKey == secretKey && $aKey == accesskey) {

                    // if (!empty($decodedParam)) {
                        // if ($decodedParam->lead_email != '') {
                        //     $par = array(
                        //         'leads_cstm.lead_email_c' => xss_clean($decodedParam->lead_email)
                        //     );

                        //     $param = array_merge($param, $par);
                        // }

                        $url = "http://example.com/suitecrm/service/v4_1/rest.php";

                        function restRequest($method, $arguments)
                        {
                            global $url;
                            $curl = curl_init($url);
                            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                            $post = array(
                                "method" => $method,
                                "input_type" => "JSON",
                                "response_type" => "JSON",
                                "rest_data" => json_encode($arguments),
                            );

                            curl_setopt($curl, CURLOPT_POSTFIELDS, $post);

                            $result = curl_exec($curl);
                            curl_close($curl);
                            return json_decode($result, 1);
                        }


                        $userAuth = array(
                            'user_name' => 'admin',
                            'password' => md5('admin@suite_crm'),
                        );
                        $appName = 'MyApi';
                        $nameValueList = array();

                        $args = array(
                            'user_auth' => $userAuth,
                            'application_name' => $appName,
                            'name_value_list' => $nameValueList
                        );

                        $result = restRequest('login', $args);
                        $sessId = $result['id'];

                        $entryArgs = array(
                            //Session id - retrieved from login call
                            'session' => $sessId,
                            //Module to get_entry_list for
                            'module_name' => 'Leads',
                            //Filter query - Added to the SQL where clause,
                            //'query' => "leads.deleted = 0",
                            //Order by - unused
                            'order_by' => '',
                            //Start with the first record
                            'offset' => 0,
                            //Return the id and name fields
                            'select_fields' => array('id', 'name',),
                            //Link to the "contacts" relationship and retrieve the
                            //First and last names.
                            'link_name_to_fields_array' => array(
                                array(
                                    'name' => 'leads',
                                    'value' => array(
                                        'first_name',
                                        'last_name',
                                    ),
                                ),
                            ),
                            //Show 10 max results
                            'max_results' => 10,
                            //Do not show deleted
                            'deleted' => 0,
                        );
                        $result = restRequest('get_entry_list', $entryArgs);

                        print_obj($result);die;
                    // } else {
                    //     // $getData = $this->smm->getLCstmData();
                    // }

                    //print_obj($getData);die;

                    if (!empty($getData)) {
                        // foreach ($getData as $key => $value) {
                        $return['leadData'] = array(
                            'lead_id'  => $getData->id_c,
                            'lead_email'  => $getData->lead_email_c,
                            'phone_no'  => $getData->phone_work
                        );
                        // }
                        $return['success'] = '1';
                    } else {
                        $return['leadData'] = '';
                        $return['success'] = '0';
                    }
                // } else {
                //     $return['success'] = '0';
                //     $return['error'] = 'Credentials mismatch!';
                // }
            // } else {
            //     $return['success'] = '0';
            //     $return['error'] = 'JSON data error';
            // }
        // } else {
        //     $return['success'] = '0';
        //     $return['error'] = 'JSON data is empty';
        // }

        header('Content-Type: application/json');
        echo json_encode($return);
    }
}
