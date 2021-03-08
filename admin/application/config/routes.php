<?php
defined('BASEPATH') OR exit('No direct script access allowed');



$route['default_controller'] = 'Auth/index';
$route['404_override'] = 'Auth/get404';
$route['translate_uri_dashes'] = FALSE;


//login
$route['login'] = 'Auth/onSetLogin';
$route['chk_login'] = 'Auth/onCheckLogin';
$route['logout'] = 'Auth/onSetLogout';
$route['dashboard'] = 'Auth/onGetDashboard';

//user management
$route['users'] = 'Users/index';
$route['duplicate_check_un'] = 'Users/onCheckDuplicateUser';
$route['adduser'] = 'Users/onCreateUserView';
$route['createuser'] = 'Users/onCreateUser';
$route['profile'] = 'Users/onGetUserProfile/';
$route['profile/(:num)'] = 'Users/onGetUserProfile/$1';
$route['changeprofile'] = 'Users/onChangeUserProfile';
$route['deluser'] = 'Users/onDeleteUser';


//reports
$route['bmreport'] = 'Main/onGetBMReport';
$route['getbmreport'] = 'Export/onGetBMXLSX';

$route['addons/bmreport'] = 'Main/onGetBMReportOpen';
$route['addons/bmreport/(:any)'] = 'Main/onGetBMReportOpen/$1';
$route['addons/getbmreport'] = 'Export/onGetBMXLSXOpen';

//sms
$route['addons/sendsms'] = 'Sms/index';
$route['addons/sendsms/(:any)'] = 'Sms/index/$1';
$route['addons/sendsms/db209113fed6e673ffb7a19d1a5206b7'] = 'Sms/index/db209113fed6e673ffb7a19d1a5206b7';//pradipta
$route['addons/sendsms/e4c75152da2adb01d664c1d5a236431f'] = 'Sms/index/e4c75152da2adb01d664c1d5a236431f';//pratibha
$route['addons/getleads'] = 'Sms/onGetFilteredLeads';
$route['addons/sendbulksms'] = 'Sms/onSendBulkSMS';


//lead source
$route['leadsources'] = 'LeadSource/index';
$route['addleadsource'] = 'LeadSource/onCreateLeadSourceView';
$route['duplicate_check_leadsource'] = 'LeadSource/onCheckDuplicateLeadSource';
$route['createleadsource'] = 'LeadSource/onCreateLeadSource';
$route['editleadsource/(:num)'] = 'LeadSource/onCreateLeadSourceView/$1';
$route['deleteleadsource'] = 'LeadSource/onDeleteLeadSource';


//leads
$route['leads'] = 'Main/onGetLeadsList';


//mail & sms test
$route['testmail'] = 'Main/onSendTestMail';
$route['testmsg'] = 'Main/onSendTestSMS';

$route['emailupdate'] = 'Main/onGetEmailUpdate';


//api

$route['api/getleaddetails'] = 'Api/onGetLeadDetails';

$route['api/createleadforfreereg'] = 'Api/onCreateLeadFreeReg';

$route['api/getleads'] = 'Api/onGetLeads';

//export
//$route['exportConnect'] = 'Export/connectXLSX';
//$route['exportPartner'] = 'Export/partnerXLSX';