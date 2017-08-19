<?php
//-------------------------------------------------------
// Purpose: This will be included at the top of almost every file (the files in Interface folder and in ajax files). This file contains Global declaration of variables which are being used throught the application such as DB variables (to establish connection with database ), Http Paths, Physical Path, Image Path, Model path, Log file path, Standard Messages, paging controls, GET or POST in REQUEST_DATA array variable etc. In other words, this file controls the functionality of the application.
// Author : Pushpender Kumar Chauhan
// Created on : (09.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
set_time_limit(0);
require_once('dbConfig.inc.php');
/**
 * Document root path
 */

// define('MAXIMUM_FILE_SIZE',1048576);  // 1 mb    

// define('REG_NO','Registration No.');    //The Database column "regNo" in Student Table will be displayed in all the forms as second parameter(Registration no.)

define("LIB_PATH", $_SERVER['DOCUMENT_ROOT']);
define("SITE_NAME", "syenergy");
define("HTTP_PATH", 'http://'.$_SERVER['HTTP_HOST']);
define("BL_PATH", LIB_PATH ."/Library");
define("HTTP_LIB_PATH", HTTP_PATH ."/Library");
define("MODEL_PATH", LIB_PATH . "/Model");
define("UI_PATH", LIB_PATH ."/Interface");
define("UI_HTTP_PATH", HTTP_PATH ."/Interface");
define("JS_PATH", HTTP_PATH ."/Scripts");    // http path
define("SCRIPT_PATH", LIB_PATH ."/Scripts"); //real path
define("STORAGE_PATH", LIB_PATH ."/Storage"); //real path
define("STORAGE_HTTP_PATH", HTTP_PATH .'/Storage'); //http path
define("STUDENT_PHOTO_PATH", STORAGE_HTTP_PATH .'/Images/Student'); //http path
define("STUDENT_STORAGE_PATH", STORAGE_PATH .'/Images/Student'); //real path
define("UI_MANAGEMENT_PATH", LIB_PATH ."/Interface/Management"); //management real path
define("UI_HTTP_MANAGEMENT_PATH", HTTP_PATH ."/Interface/Management"); //management http path
define("UI_HTTP_TEACHER_PATH", HTTP_PATH ."/Interface/Teacher"); //Teacher http path
define("UI_HTTP_STUDENT_PATH", HTTP_PATH ."/Interface/Student"); //Student http path
define("UI_HTTP_PARENT_PATH", HTTP_PATH ."/Interface/Parent"); //Parent http path
define("UI_HTTP_MOODLE_PATH", HTTP_PATH ."/Interface/Moodle"); //Moodle http path

//WinJs is library for window js alerts and popups
define("WINJS_JS_PATH",JS_PATH."/winjs");
define("DA_PATH", BL_PATH . "/Database");
define("IMG_PATH", STORAGE_PATH ."/Images");
define("IMG_HTTP_PATH", STORAGE_HTTP_PATH ."/Images");
define("HELP_VIDEO_PATH", STORAGE_HTTP_PATH ."/Help/Video");
define("SERVER_URL", HTTP_PATH);
define("LANG_PATH", LIB_PATH . "/Languages");
define("TEMPLATES_PATH", LIB_PATH . "/Templates");
define("CSS_PATH", HTTP_PATH . "/CSS");
define("WINJS_CSS_PATH", HTTP_PATH . "/includes/winjs/themes");

define("TEMP_FOLDER_PATH", STORAGE_PATH ."/temp");
define("TEMP_UPLOAD_PATH", STORAGE_PATH ."/Uploads");
define("TEMP_UPLOAD_HTTP_PATH", HTTP_PATH ."/Storage/Uploads");
define("LOCAL_STORAGE_DIR", LIB_PATH . '/Storage/temp/');

//Broadcast Feature Module
define("BROADCAST_DB_HOST", "192.168.1.11");
define("BROADCAST_DB_USER", "trainee");
define("BROADCAST_DB_PASS", "trainee");
define("BROADCAST_DB_NAME", "trainee_broadcast_feature");


define('SET_MENU_HEADING',1);
define('MAKE_SINGLE_MENU',2);
define('MAKE_MENU',3);
define('MAKE_HEADING_MENU',4);

//ACTIVITIES FOR TIME TABLE LECTURES
define('NOTHING',0);
define('QUIZ',1);

//error_reporting(2047);
ini_set("display_errors", "on");
ini_set("error_reporting",E_ERROR | E_PARSE);
//ini_set(max_execution_time, 2000);
//ini_set(upload_max_filesize, '30M');
date_default_timezone_set('Asia/Calcutta');
//error_reporting(2047);


// to make log file writable
if(!is_writable(LOCAL_STORAGE_DIR.LOG_FILE_NAME)) {
   @chmod(LOCAL_STORAGE_DIR.LOG_FILE_NAME,777);
}

//FOR SENDING BULK SMSs AND EMAILs
define('NUMBER_OF_LOOPS_BEFORE_A_SLEEP',100);
define('AMOUNT_OF_SLEEP',10);

//Extension
$allowedExtensionsArray = array('gif','jpg','jpeg','png','bmp','doc','pdf','xls','csv','txt','rar','zip','gz','tar','docx', 'xlsx','pptx','ppt');

// Suggestion array
//$suggestionArr = array("1"=>"<img src='".IMG_HTTP_PATH."/technical.png' style='border:0px;height:20px;width:20px;margin-bottom:-4px;' />&nbsp;Technical","2"=>"<img src='".IMG_HTTP_PATH."/layout.png' style='border:0px;height:20px;width:20px;margin-bottom:-4px;' />&nbsp;Layout Problem", "10"=>"<img src='".IMG_HTTP_PATH."/others.png' style='border:0px;height:20px;width:20px;margin-bottom:-4px;' />&nbsp;Others");
$suggestionArr = array("1"=>"<img src='".IMG_HTTP_PATH."/technical.gif' class='imgLinkRemove' style='border:0px;height:29px;margin-bottom:-7px;' />",
					   "2"=>"<img src='".IMG_HTTP_PATH."/layout.gif' class='imgLinkRemove'  style='border:0px;height:29px;margin-bottom:-7px;' />",
					   "10"=>"<img src='".IMG_HTTP_PATH."/others.gif' class='imgLinkRemove'  style='border:0px;height:29px;margin-bottom:-7px;' />");

// seprator used for displaying class
define('CLASS_SEPRATOR','-');  //

// fee receipt number Prefix
define('FEE_RECEIPT_PREFIX','CIET/07-08/');  //

// fee receipt starting number
define('FEE_RECEIPT_START','10001');  //

define(NOT_APPLICABLE_STRING,'---'); //define variable to show value on blank fields in student information module
define(NO_DATA_FOUND,'No Data Found'); //define variable to show no data found in the data list if it will blank

// Define contents for ICard
define('ICARD_HEIGHT','190px');
define('ICARD_WIDTH','320px');

define("INCLUDE_INVENTORY_MANAGEMENT", true);

//inventory management common file
$inventoryCommonFileName = BL_PATH . '/inventoryCommon.inc.php';
if (file_exists($inventoryCommonFileName)) {
 require_once($inventoryCommonFileName);
}

//For external marks status
$marksScoredArray = array("RT" => -1,"RP" => -2, "RTI" => -3, "D" => -4, "A" => -5, "RL" => -6, "RLA" => -7, "N" => -8);
/*
RT: RE-APPEAR IN INTERNAL AND EXTERNAL
RP: RE-APPEAR IN EXTERNAL
RTI: RE-APPEAR IN INTERNAL
D: DETAINED
AB: ABSENT
RL: RESULT LATE
RLA: RESULT LATE
*/

$accountsCommonFileName = BL_PATH . '/accountsCommon.inc.php';
if (file_exists($accountsCommonFileName)) {
	require_once($accountsCommonFileName);
}

// fee instrument mode
$modeArr = array("1"=>"Cash","2"=>"Cheque", "3"=>"Draft","4"=>"Online");

// Program type in event calendar
$programTypeArr = array("1"=>"Events","2"=>"Holidays");

// fee receipt status
$receiptArr = array("1"=>"Open","2"=>"Closed", "3"=>"Cancel","4"=>"Delete");


// fee Head Wise Collection Details
$feeHeadCollectionArr = array("1"=>"Fee Head","2"=>"Fine", "3"=>"Advance");

// instrument payment status
$receiptPaymentArr = array("1"=>"With Clerk","2"=>"With Bank", "3"=>"Closed","4"=>"Bounced","5"=>"OSuceess","6"=>"ORollBack");

// title name array
$titleResults = array(1 => 'Mr', 'Mrs', 'Miss', 'Dr.','Ms.');

// month array
$monArr = array("01"=>"Jan","02"=>"Feb", "03"=>"Mar","04"=>"Apr","05"=>"May", "06"=>"Jun","07"=>"Jul","08"=>"Aug", "09"=>"Sep","10"=>"Oct","11"=>"Nov","12"=>"Dec");

// class status array
$classStatusArr = array("1"=>"Active","2"=>"Future", "3"=>"Past","4"=>"Unused");

// weeke days array
$daysArr = array("1"=>"Monday","2"=>"Tuesday", "3"=>"Wednesday","4"=>"Thursday","5"=>"Friday","6"=>"Saturday","7"=>"Sunday");

// weeke days array for master time table
$daysSingleArr = array("1"=>"M","2"=>"T", "3"=>"W","4"=>"Th","5"=>"F","6"=>"Sat","7"=>"Sun");

// fine type array
$fineTypeArr = array("2"=>"Daily","1"=>"Fixed");

// Repair Type array
$busRepairTypeArr = array("1"=>"Brake Oil","2"=>"Engine Oil", "3"=>"Gear Oil","4"=>"Greasing","5"=>"Oil Filter","6"=>"Air Filter");

// repairTypeArray
$repairTypeResults = array(1 => 'Labour', 'Item', 'Misc');


// BUS PASS array
$buspassArray = array("1"=>"Active", "2"=>"Cancelled");


//temporary employee status array
$statusArr = array("1"=>"On Job","2"=>"Left Job");

// Bus Service mode
$serviceArr = array("1"=>"Free","2"=>"Paid","3"=>"Workshop");

// Item Type array
$itemTypeArr = array("1"=>"Consumables","2"=>"NonConsumables");

// Issued Status array
$issuedStatusArr = array("2"=>"Issue","3"=>"Transfer","4"=>"Return");

// Inventory Department array
$inventoryDepartmentArr = array("1"=>"Issue/Transfer","2"=>"Issuing Authority", "3"=>"End User");

// hostel visitor relation array
$hostelVisitorRelArr = array("1"=>"Father","2"=>"Mother", "3"=>"Sister", "4"=>"Brother", "5"=>"Others");

// Transport Staff Type array
$transportStaffTypeArr = array("1"=>"DRIVER","2"=>"CONDUCTOR", "3"=>"OTHER");

// Blood Group Array
$bloodResults = array("1"=>"O","O+","O-","A","A+","A-","B","B+","B-","AB","AB+","AB-","A1+","A1-","A2+","A2-","A1B+","A1B-","A2B+","A2B-");

//Status Fine Category
$statusCategoryArr = array("1"=>"Approved","2"=>"Unapproved", "3"=>"Rejected");

//Status Fine Category2
$statusCategoryArr2 = array("1"=>"Approve","2"=>"Unapprove", "3"=>"Reject");

// adjustment type array
$adjustmentTypeArr = array("1"=>"Move","2"=>"Copy","3"=>"Swap/Substitution");

// fee receipt text
$feeFavourText = "For CHITKARA EDUCATIONAL TRUST";

// Time Table Format
$timeTableFormatArr = array("1"=>"Periods in Columns","2"=>"Periods in Rows");

// Student Reppear Status
$reppearStatusArr = array("1"=>"Approved","2"=>"Not Approved","3"=>"Pending Approval");

// experience array
$experienceResults = array(1 => 'Teaching', 'Research', 'Industry');

// experience certificate array
$experienceAvailableResults = array(1 => 'Yes', 'No');

// education array
$educationResults = array(1 => 'Self-financed', 'Educational Loan');

// Family Ailment array
$familyAilmentsResults = array(1 => 'Hypertension',2=>'Diabeties',3=>'Angina',4=>'Epilepsy');


//Cc= class centric, sc=subject(course) centric
define(CURRENT_PROCESS_FOR,"cc");

//Number of days for showing events prior to current date
define(EVENT_DAY_PRIOR,"10");

define(HELP_PERMISSION,0); //Used to make help facility On[1]/Off[other than 1]



//student syenergy block status
$blockStudentArr=array("1"=>"Blocked","0"=>"Active");

// employee tab publisher Details
$publisherScopeArr = array("1"=>"National Journals","2"=>"National Conferences","3"=>"International Conferences","4"=>"Books");

// employee tab seminar Details
$seminarParticipationArr = array("1"=>"Attended","2"=>"Conducted");

// Fee Type Details
$feeTypeArr = array("4"=>"All","1"=>"Only Academic","2"=>"Only Transport","3"=>"Only Hostel");


// Adv. FeedBack Relationships
$advFeedBackRelationship = array("1"=>"General","2"=>"Hostel","3"=>"Transport","4"=>"Subject");

// Source from where the visitor came to know about the college
$visitorSource = array("1"=>"Print Media","2"=>"Thru College Student","3"=>"Thru website","4"=>"Electronic Media");

// Source from where the visitor came to know about the college
$enquiryStatusArr = array("1"=>"Waiting","2"=>"Admission","3"=>"Offered","4"=>"Rejected","5"=>"Counseling");

// Coaching Centers
$coachingCenterArr = array("1"=>"CAT","2"=>"GMAT");


//FOR ATTENDANCE EVALUATION CRITERIAS
define('PERCENTAGES',5);
define('SLABS',6);

//THIS ARRAY IS USED FOR STUDENT DOCUMENTS
$globalStudentDocumentsArray=array(
                                    1=>'Date of Birth Certificate',
                                    2=>'Secondary Examination Certificate',
                                    3=>'Higher Secondary Examination Certificate',
                                    4=>'Graduation',
                                    5=>'Post Graduation',
                                    6=>'Diploma',
                                    7=>'Experience Certificates',
                                    8=>'Personal Profile',
                                    9=>'Photostats of payments like DD, gateway payment list',
                                    10=>'Others'
                                  );

//DEFINES FOR ADV. FEEDBACK MODULES
define(FEEDBACK_STUDENT_BLOCKED,0);
define(FEEDBACK_STUDENT_UNBLOCKED,1);
define(FEEDBACK_STUDENT_FORCED_UNBLOCKED,2);
define(FEEDBACK_STUDENT_COMPLETE,1);
define(FEEDBACK_STUDENT_INCOMPLETE,2);


//For AUDIT TRAIL
define('EXTERNAL_MARKS_ARE_UPLOADED',1);
define('GRACE_MARKS_ARE_ENTERED',2);
define('MARKS_ARE_TRANSFERRED',3);
define('STUDENT_IS_QUARANTINED',4);
define('STUDENT_IS_RESTORED',5);
define('GRADES_ARE_APPLIED',6);
define('CGPA_IS_CALCULATED',7);
define('TEST_TYPE_CATEGORY_IS_DELETED',8);
define('GROUPS_CHANGED',9);
define('ATTENDANCE_IS_ENTERED_BY_TEACHER_OR_ADMINISTRATOR',10);
define('FEES_IS_COLLECTED',11);
//define('FEES_HEADS_ARE_CREATED',12);
define('FEES_HEAD_VALUES_ARE_CREATED',13);
//define('NEW_FEES_CONCESSION_CATEGORY_IS_CREATED',14);
//define('FEES_CONCESSION_IS_GIVEN',15);
//define('FINE_IS_APPLIED_TO_STUDENT',16);
//define('FINE_IS_APPROVED_OR_REJECTED',17);
define('ATTENDANCE_IS_DELETED_BY_TEACHER_OR_ADMINISTRATOR',18);
//define('BANK_NAME_IS_CREATED',19);
//define('BANK_NAME_IS_EDITED',20);
//define('BANK_NAME_IS_DELETED',21);
define('FUND_ALLOCATION_ENTITY_ADDED',22);
define('FUND_ALLOCATION_ENTITY_EDITED',23);
define('FUND_ALLOCATION_ENTITY_DELETED',24);
define('FEE_CYCLE_ADDED',25);
define('FEE_CYCLE_EDITED',26);
define('FEE_CYCLE_DELETED',27);
define('FEE_HEAD_CREATED',28);
define('FEE_HEAD_DELETED',29);
define('FEE_HEAD_EDITED',30);
//define('FEE_CYCLE_FINE_ADDED',31);
//define('FEE_CYCLE_FINE_EDITED',32);
//define('FEE_CYCLE_FINE_DELETED',33);
define('FEE_CONCESSION_ADDED',34);
define('FEE_CONCESSION_EDITED',35);
define('FEE_CONCESSION_DELETED',36);
define('STUDENT_MISC_CHARGES_ADDED',37);
define('STUDENT_FEE_CONCESSION_MAPPING',38);
define('FEES_CONCESSION_VALUE_COPIED',39);
define('FEES_CONCESSION_CATAGORY_ADDED',40);
define('FEES_HEAD_VALUE_COPIED',41);
define('STUDENT_ADHOC_CONCESSION_ADDED',42);
define('FEES_FACILITY',43);
define('PROMOTE_STUDENT',44);
define('MARKS_ARE_CHANGED',45);
$auditTrailArray = array(
							EXTERNAL_MARKS_ARE_UPLOADED => 'External Marks have been uploaded',
							GRACE_MARKS_ARE_ENTERED => 'Grace Marks have been given',
							MARKS_ARE_TRANSFERRED => 'Internal Marks have been Transferred',
							STUDENT_IS_QUARANTINED => 'Student(s) Quarantined',
							STUDENT_IS_RESTORED => 'Student(s) Restored',
							GRADES_ARE_APPLIED => 'Grades Applied',
							CGPA_IS_CALCULATED => 'CGPA Calculated',
							TEST_TYPE_CATEGORY_IS_DELETED => 'Test Type Category has been Deleted',
							GROUPS_CHANGED => 'Groups have been Changed',
							ATTENDANCE_IS_ENTERED_BY_TEACHER_OR_ADMINISTRATOR => 'Attendance has been entered by Teacher or Administrator',
							ATTENDANCE_IS_DELETED_BY_TEACHER_OR_ADMINISTRATOR => 'Attendance has been deleted by Teacher or Administrator',
							FUND_ALLOCATION_ENTITY_ADDED =>'Fund Allocation Entity has been Added',
							FUND_ALLOCATION_ENTITY_EDITED =>'Fund Allocation Entity has been Edited',
							FUND_ALLOCATION_ENTITY_DELETED =>'Fund Allocation Entity has been Deleted',
							FEES_IS_COLLECTED => 'Fees Collected',
							FEE_HEAD_CREATED => 'Fees Head has been Created',
							FEE_HEAD_DELETED =>'Fees Head has been Deleted',
							FEE_HEAD_EDITED =>'Fees Head has been Edited',
							FEES_HEAD_VALUES_ARE_CREATED => 'Fees Head Values have been Created',
							FEES_HEAD_VALUE_COPIED =>'Fees Head Value has been Copied',
							FEE_CYCLE_ADDED =>'Fees Cycle has been Added',
							FEE_CYCLE_EDITED =>'Fees Cycle has been Edited',
							FEE_CYCLE_DELETED =>'Fees Cycle has been Deleted',
							FEE_CONCESSION_ADDED =>'Fees Consession has been Added',
							FEE_CONCESSION_EDITED =>'Fees Consession has been Edited',
							FEE_CONCESSION_DELETED =>'Fees Consession has been Deleted',
							FEES_CONCESSION_VALUE_COPIED =>'Fees Concession Value has been Copied',
							FEES_CONCESSION_CATAGORY_ADDED =>'Fees Consession Category has been Added',
							FEES_FACILITY =>'Fees Facility has been Added',
							STUDENT_MISC_CHARGES_ADDED =>'Student Miscellaneus Charges have been Added',
							STUDENT_FEE_CONCESSION_MAPPING =>'Student fees Concession mapping',
							PROMOTE_STUDENT =>'Student Promoted',

							STUDENT_ADHOC_CONCESSION_ADDED =>'Student ADHOC Concession has been added',
							MARKS_ARE_CHANGED =>'Marks Changed'
							
						);


//FOR TIME TABLES: WEEKLY FOR DAYS SUCH AS MONDAY, TUESDAY. DAILY FOR DATE SUCH AS 14-APR-2010, 15-APR-2010
define('WEEKLY_TIMETABLE',1);
define('DAILY_TIMETABLE',2);

//THESE DEFINES ARE USED FOR BOOKS MANAGEMENT
define('BOOK_ISSUED',1);
define('BOOK_PACKED',2);
define('BOOK_DISPACHED',3);

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    foreach ($_POST as $key => $value) {
        if (!isset($_GET[$key])) {
            logError("URL parameter '$key=$value' found in POST but not in GET request", WARNING_SEVERITY);
            $_GET[$key] = $value;
        }
    }
    $REQUEST_DATA = &$_GET;
} else {
    $REQUEST_DATA = &$_POST;
}
//Array, which includes roles for which all security has to be bypassed.
$checkAccessArray = Array('Administrator'=>1,'Management'=>5);

// include centralized messages file path
require_once(BL_PATH . '/messages.inc.php');
$accountsMessagesFileName = BL_PATH . '/Accounts/accountsMessages.inc.php';
if (file_exists($accountsMessagesFileName)) {
	require_once($accountsMessagesFileName);
}

//uncomment the following line to store session data into db
//require_once(BL_PATH.'/SessionHandler.php');
//$sessionHandler = new SessionHandler();
//comment the following lines to store data in files on server
//print BL_PATH.'/SessionManager.inc.php';
require_once(BL_PATH . '/SessionManager.inc.php');
$sessionHandler = SessionManager::getInstance();

//for paging
define('RECORDS_PER_PAGE',$sessionHandler->getSessionVariable('RECORDS_PER_PAGE')!=''?$sessionHandler->getSessionVariable('RECORDS_PER_PAGE'):25); // records per page to be shown
define('LINKS_PER_PAGE',$sessionHandler->getSessionVariable('LINKS_PER_PAGE')!=''?$sessionHandler->getSessionVariable('LINKS_PER_PAGE'):5); // links per page to be shown
define('RECORDS_PER_PAGE_TEACHER',$sessionHandler->getSessionVariable('RECORDS_PER_PAGE_TEACHER')!=''?$sessionHandler->getSessionVariable('RECORDS_PER_PAGE_TEACHER'):100); // records per page to be shown IN TEACHER MODULE
define('RECORDS_PER_PAGE_ADMIN_MESSAGE',$sessionHandler->getSessionVariable('RECORDS_PER_PAGE_ADMIN_MESSAGE')!=''?$sessionHandler->getSessionVariable('RECORDS_PER_PAGE_ADMIN_MESSAGE'):100); // records per page to be shown IN MESSAGE MODULES IN ADMIN SECTION
define('RECORDS_PER_PAGE_ADMIN_MESSAGE_EMPLOYEE',$sessionHandler->getSessionVariable('RECORDS_PER_PAGE_ADMIN_MESSAGE_EMPLOYEE')!=''?$sessionHandler->getSessionVariable('RECORDS_PER_PAGE_ADMIN_MESSAGE_EMPLOYEE'):5); // records per page to be shown IN MESSAGE MODULES IN EMPLOYEE SECTION
//for paging

/**************multiple institutes ******defines**********/
$sessionInstituteId = $sessionHandler->getSessionVariable('InstituteId');
if(MULTI_INSTITUTE == 0) {
  $sessionInstituteId = '';
}

$isDBName="syenergy_cietpb";
if(DB_NAME==$isDBName) {
   $ttSessionId = $sessionHandler->getSessionVariable('SessionId');
   if($ttSessionId>=7) {
     define('TIME_TABLE_TABLE',    'time_table_12'); 
     define('MEDICAL_LEAVE_TABLE', 'medical_leave'.$sessionInstituteId.'_12'); 
     define('DUTY_LEAVE_TABLE',    'duty_leave'.$sessionInstituteId.'_12');
     define('ATTENDANCE_TABLE',    'attendance'. $sessionInstituteId.'_12');
   }
   else {
     define('TIME_TABLE_TABLE',    'time_table'); 
     define('MEDICAL_LEAVE_TABLE', 'medical_leave'); 
     define('DUTY_LEAVE_TABLE',    'duty_leave');  
     define('ATTENDANCE_TABLE',    'attendance'    . $sessionInstituteId);
   }
}
else {
   define('TIME_TABLE_TABLE',    'time_table'); 
   define('MEDICAL_LEAVE_TABLE', 'medical_leave'); 
   define('DUTY_LEAVE_TABLE',    'duty_leave');  
   define('ATTENDANCE_TABLE',    'attendance'    . $sessionInstituteId); 
}

define('TEST_TRANSFERRED_MARKS_TABLE', 'test_transferred_marks' . $sessionInstituteId);
define('TEST_TABLE',     'test'      . $sessionInstituteId);
define('TEST_MARKS_TABLE',    'test_marks'    . $sessionInstituteId);
define('TEST_TRANSFERRED_MARKS_TABLE', 'test_transferred_marks' . $sessionInstituteId);
define('TOTAL_TRANSFERRED_MARKS_TABLE', 'total_transferred_marks' . $sessionInstituteId);
define('QUARANTINE_ATTENDANCE_TABLE', 'quarantine_attendance'  . $sessionInstituteId);
define('QUARANTINE_TEST_TABLE',   'quarantine_test'   . $sessionInstituteId);
define('QUARANTINE_TEST_MARKS_TABLE', 'quarantine_test_marks'  . $sessionInstituteId);
define('TEST_GRACE_MARKS_TABLE', 'test_grace_marks'  . $sessionInstituteId);
define('TOTAL_UPDATED_MARKS_TABLE', 'total_updated_marks'  . $sessionInstituteId);
/*************************************************/


// Counseling Mail Server Id
define('COUNSELING_MAIL',1);        // 0 = Local, 1 = Live

//FOR BUDGET HEAD TYPE
define('GUEST_HOUSE',1);

//FOR BUDGET HEAD TYPE ARRAY
$globalBudgetHeadTypeArray=array(GUEST_HOUSE=>"Guest House");

//FOR Duty Leave Status Array
define('DUTY_LEAVE_REJECT',0);
define('DUTY_LEAVE_APPROVE',1);
define('DUTY_LEAVE_MARK_ABSENT',2);
define('DUTY_LEAVE_UNRESOLVED',3);
$globalDutyLeaveStatusArray=array(DUTY_LEAVE_APPROVE=>'Approve',DUTY_LEAVE_REJECT=>'Reject',DUTY_LEAVE_MARK_ABSENT=>'Mark Absent',DUTY_LEAVE_UNRESOLVED=>'Unresolved');

//FOR Medical Leave Status Array
define('MEDICAL_LEAVE_REJECT',0);
define('MEDICAL_LEAVE_APPROVE',1);
define('MEDICAL_LEAVE_MARK_ABSENT',2);
define('MEDICAL_LEAVE_UNRESOLVED',3);
$globalMedicalLeaveStatusArray=array(MEDICAL_LEAVE_APPROVE=>'Approve',MEDICAL_LEAVE_REJECT=>'Reject',MEDICAL_LEAVE_MARK_ABSENT=>'Mark Absent',MEDICAL_LEAVE_UNRESOLVED=>'Unresolved');

//For rounding while transferring marks
define('CEIL_TOTAL',1);
define('CEIL_TEST_TYPE',2);
define('ROUND_TOTAL',3);
define('ROUND_TEST_TYPE',4);
define('NO_ROUND',5);

$roundingArray = array('ceilTotal' => CEIL_TOTAL, 'ceilTestType' => CEIL_TEST_TYPE, 'roundTotal' => ROUND_TOTAL, 'roundTestType' => ROUND_TEST_TYPE, 'noRound' => NO_ROUND);


//FOR GUEST HOUSE BOOKING
define('BOOKING_NO_PREFIX','BOOKING');
define('BOOKING_NO_LENGTH',15);


define(EXPAND_COLLAPSE_CONFIG_PERMISSION,'1'); //Used to make grouping facility On[1]/Off[other than 1]


// For Leave Status
$leaveStatusArray = array("0"=>"Applied","1"=>"First Approval", "2"=>"Second Approval","3"=>"Rejected","4"=>"Cancelled");


define('SHOW_EMPLOYEE_APPRAISAL_FORM',1);

define('SHOW_PLACEMENT_MODULES',1);

//defines for fleet
define('EXPIRY_DATE_VALIDATION','Expiry date cannot be less than Issue date');
define('BIRTH_DATE_VALIDATION','Joining date cannot be less than date of birth');

define('ENTER_ROLL_NO_REG_NO_UNI_NO','Enter Reg. No./ Uni. No./ Roll No.');



// FOR CONDUCTING AUTHORITY
define('PRECOMPRE',1);
define('COMPRE',2);
define('ATTENDANCE',3);
define('PRECOMPRE_REAPPEAR',4);
define('COMPRE_REAPPEAR',5);
define('ATTENDANCE_REAPPEAR',6);
define('PRECOMPRE_COMPRE_REAPPEAR',7);
define('PRECOMPRE_ATTENDANCE_REAPPEAR',8);
define('COMPRE_ATTENDANCE_REAPPEAR',9);
define('PRECOMPRE_ATTENDANCE_COMPRE_REAPPEAR',10);


function redirectBrowser($pathToRedirect){
    ob_start();
    header("Location: $pathToRedirect");
    ob_flush();
    exit;
}
/*
 * trigger errors in log.txt file

 * @param $message error message

 * @param $severityLevel level of error message

 */

function logError($message, $severityLevel = DEBUG_SEVERITY) {

    require_once(BL_PATH . '/Logger.inc.php');

    $logger = Logger::getInstance();

    $logger->write($message, $severityLevel);
}
function queryLog($message) {

    if(DB_QUERY_LOG==1 && strpos($message,"user_log")== false && strpos($message,"usage_log")== false) {
        require_once(BL_PATH . '/Logger.inc.php');
        $logger = Logger::getInstance();
        $logger->queryLog($message);
    }
}
/**
* change to add slash according to the magic_quote property of php.ini
*/

function add_slashes($string) {
    if (!get_magic_quotes_gpc()) {
        $string = addslashes($string);
    }
    return $string;
}
function strip_slashes($string) {
    if (!get_magic_quotes_gpc()) {
        $string = stripslashes($string);
    }
    return $string;
}


//checking for CLIENT_INSTITUTES count
require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
$instituteCountArray=CommonQueryManager::getInstance()->countInstitutes();
$databaseTamperedFlag=0;
if($instituteCountArray[0]['instituteCount']>CLIENT_INSTITUTES){
    //$databaseTamperedFlag=1;
    $indexPageName=LIB_PATH.'/Interface/index.php';
    $fileName=$_SERVER['SCRIPT_FILENAME'];
    $homePage=HTTP_PATH.'/Interface/index.php';
    require_once(BL_PATH . "/UtilityManager.inc.php");
    //send mail to admin
    $msgBody="Client ".CLIENT_NAME." has created ".$instituteCountArray[0]['instituteCount']." no. of  institutes, whereas the product 'syenergy' was bought for ".CLIENT_INSTITUTES." no. of institutes only";
    $msgSubject="LIVE DATABASE(".DB_HOST.'---'.DB_NAME.") for ".CLIENT_NAME." TAMPERED";
    $headers = 'From: '.ADMIN_MSG_EMAIL.' '. "\r\n" ;
    $headers .= 'Content-type: text/html;';
    UtilityManager::sendMail('kabir@chalkpad.in', $msgSubject, $msgBody, $headers);
    if($fileName!=$indexPageName){
        //echo DATABASE_TAMPERED;
        //$sessionHandler->destroySession();
        //redirectBrowser($homePage);
        //die;
    }
}

//To show search on all modules
$showSearch = true;

//Cache File
$cacheFile = TEMPLATES_PATH . '/Xml/menuContents'.trim($sessionHandler->getSessionVariable('RoleId')).'.html';
//cache : 5 minutes [from milliseconds point of view]
$rewriteTime = 1;//1000 * 60 * 5;

function copyHODSendSMS($msg='') {
   if(trim($msg)!='') {
     global $sessionHandler;
     $smsInstituteId = $sessionHandler->getSessionVariable('InstituteId');
     if($smsInstituteId=='17') {
       $ret=sendSMS("9501119649",strip_tags(trim($msg))); 
       $ret=sendSMS("9501119650",strip_tags(trim($msg)));
     }
   }
   return true;
}

?>
