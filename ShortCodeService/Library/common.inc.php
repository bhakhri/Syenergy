<?php
//-------------------------------------------------------
// Purpose: This will be included at the top of almost every file (the files in Interface folder and in ajax files). This file contains Global declaration of variables which are being used throught the application such as DB variables (to establish connection with database ), Http Paths, Physical Path, Image Path, Model path, Log file path, Standard Messages, paging controls, GET or POST in REQUEST_DATA array variable etc. In other words, this file controls the functionality of the application.
//
// Author : Pushpender Kumar Chauhan
// Created on : (09.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------


 require_once('dbConfig.inc.php');  

/**
 * Document root path
 *
 */
//define('ACCOUNT_NAME','testsms'); 
//define("LIB_PATH", '/var/www/html/ciet' . '/' . ACCOUNT_NAME );
define("LIB_PATH", 'D:/wamp/www' . '/' . ACCOUNT_NAME );
define("SITE_NAME", "College Management System");
define("HTTP_PATH", 'http://'.$_SERVER['HTTP_HOST'] . '/' . ACCOUNT_NAME );
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

define('SET_MENU_HEADING',1);
define('MAKE_SINGLE_MENU',2);
define('MAKE_MENU',3);
define('MAKE_HEADING_MENU',4);

//ACTIVITIES FOR TIME TABLE LECTURES
define('NOTHING',0);
define('QUIZ',1);

//error_reporting(2047);
ini_set("display_errors", "on");
ini_set("error_reporting",~E_NOTICE);
//ini_set(max_execution_time, 2000);
//ini_set(upload_max_filesize, '30M');
date_default_timezone_set('Asia/Calcutta');
//error_reporting(2047);

// Misc. Log-related constants

define("LOG_FILE_NAME", 'log.txt');        // The name of the log file
// query log
define("DB_QUERY_LOG", 1); // 0 = off, 1 = on
define("DB_QUERY_LEVEL", 3); //  1 = All, 2= read (SELECT only), 3= write (INSERT, UPDATE, DELETE)
define("DB_QUERY_LOG_DESTINATION", 1); //  1 = Database, 2= File

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
$suggestionArr = array("1"=>"<img src='".IMG_HTTP_PATH."/technical.gif' style='border:0px;height:29px;margin-bottom:-7px;' />","2"=>"<img src='".IMG_HTTP_PATH."/layout.gif' style='border:0px;height:29px;margin-bottom:-7px;' />", "10"=>"<img src='".IMG_HTTP_PATH."/others.gif' style='border:0px;height:29px;margin-bottom:-7px;' />");

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

//For external marks status
$marksScoredArray = array("RT" => -1,"RP" => -2, "RTI" => -3, "D" => -4, "A" => -5, "RL" => -6, "RLA" => -7);
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
$modeArr = array("1"=>"Cash","2"=>"Cheque", "3"=>"Draft");

// Program type in event calendar
$programTypeArr = array("1"=>"Events","2"=>"Holidays");

// fee receipt status
$receiptArr = array("1"=>"Open","2"=>"Closed", "3"=>"Cancel");

// instrument payment status
$receiptPaymentArr = array("1"=>"With Clerk","2"=>"With Bank", "3"=>"Closed","4"=>"Bounced");

// fee receipt print outs
$feeReceiptPrintArr = array("1"=>"Bank Copy","2"=>"College Copy", "3"=>"Student Copy");



// title name array
$titleResults = array(1 => 'Mr', 'Mrs', 'Miss', 'Dr.');

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
$busRepairTypeArr = array("1"=>"Engine Oil Change","2"=>"Gear Box Oil Change", "3"=>"Transfer Case Oil Change","4"=>"Tyre Rotation","5"=>"Air Filter Cleaning","6"=>"Other Reason");


// BUS PASS array
$buspassArray = array("1"=>"Active", "2"=>"Cancelled");


//temporary employee status array
$statusArr = array("1"=>"On Job","2"=>"Left Job");



// hostel visitor relation array
$hostelVisitorRelArr = array("1"=>"Father","2"=>"Mother", "3"=>"Sister", "4"=>"Brother", "5"=>"Others");

// Transport Staff Type array
$transportStuffTypeArr = array("1"=>"DRIVER","2"=>"CONDUCTOR", "3"=>"OTHER");

// Blood Group Array
$bloodResults = array("1"=>"O","O+","O-","A","A+","A-","B","B+","B-","AB","AB+","AB-","A1+","A1-","A2+","A2-","A1B+","A1B-","A2B+","A2B-");

// Class array for admit student
$classResults= array(1 => 'Matric', '10+2', 'Graduation', 'PG (if any)', 'Any Diploma');

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


//Cc= class centric, sc=subject(course) centric
define(CURRENT_PROCESS_FOR,"cc");

//Number of days for showing events prior to current date
define(EVENT_DAY_PRIOR,"10");

define(HELP_PERMISSION,0); //Used to make help facility On[1]/Off[other than 1]


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


//FOR ATTENDANCE EVALUATION CRITERIAS
define('PERCENTAGES',5);
define('SLABS',6);

//DEFINES FOR ADV. FEEDBACK MODULES
define(FEEDBACK_STUDENT_BLOCKED,0);
define(FEEDBACK_STUDENT_UNBLOCKED,1);
define(FEEDBACK_STUDENT_FORCED_UNBLOCKED,2);
define(FEEDBACK_STUDENT_COMPLETE,1);
define(FEEDBACK_STUDENT_INCOMPLETE,2);

//FOR TIME TABLES: WEEKLY FOR DAYS SUCH AS MONDAY, TUESDAY. DAILY FOR DATE SUCH AS 14-APR-2010, 15-APR-2010
define('WEEKLY_TIMETABLE',1);
define('DAILY_TIMETABLE',2);

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
//$sessionInstituteId = $sessionHandler->getSessionVariable('InstituteId');
//if (MULTI_INSTITUTE == 0) {
//    $sessionInstituteId = '';        
//}

//define('ATTENDANCE_TABLE',    'attendance'    . $sessionInstituteId); commented for shortcode inside replicated getdata
//define('TEST_TABLE',     'test'      . $sessionInstituteId);//commented for shortcode inside replicated getmarks
//define('TEST_MARKS_TABLE',    'test_marks'    . $sessionInstituteId);//commented for shortcode inside replicated getmarks
define('TEST_TRANSFERRED_MARKS_TABLE', 'test_transferred_marks' . $sessionInstituteId);
define('TOTAL_TRANSFERRED_MARKS_TABLE', 'total_transferred_marks' . $sessionInstituteId);
define('QUARANTINE_ATTENDANCE_TABLE', 'quarantine_attendance'  . $sessionInstituteId);
define('QUARANTINE_TEST_TABLE',   'quarantine_test'   . $sessionInstituteId);
define('QUARANTINE_TEST_MARKS_TABLE', 'quarantine_test_marks'  . $sessionInstituteId);
define('TEST_GRACE_MARKS_TABLE', 'test_grace_marks'  . $sessionInstituteId);
/*******************************************************/


// Counseling Mail Server Id 
define('COUNSELING_MAIL',1);        // 0 = Local, 1 = Live  


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
  
    if(DB_QUERY_LOG==1) {
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
    $msgSubject="LIVE DATABASE for ".CLIENT_NAME." TAMPERED";
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


// $History: common.inc.php $
//
//*****************  Version 59  *****************
//User: Parveen      Date: 4/22/10    Time: 11:31a
//Updated in $/LeapCC/Library
//entrance exam, hostel type array move in dbConfig
//
//*****************  Version 58  *****************
//User: Dipanjan     Date: 20/04/10   Time: 18:53
//Updated in $/LeapCC/Library
//Modified coding to stoping clients is they creates more institutes than
//sold.
//
//*****************  Version 57  *****************
//User: Dipanjan     Date: 20/04/10   Time: 18:30
//Updated in $/LeapCC/Library
//Added check for CLIENT_INSTITUTES
//
//*****************  Version 56  *****************
//User: Ajinder      Date: 4/16/10    Time: 10:36a
//Updated in $/LeapCC/Library
//added defines for TIME-TABLE type.
//
//*****************  Version 55  *****************
//User: Parveen      Date: 4/13/10    Time: 5:29p
//Updated in $/LeapCC/Library
//COUNSELING_MAIL define added
//
//*****************  Version 54  *****************
//User: Dipanjan     Date: 30/03/10   Time: 17:41
//Updated in $/LeapCC/Library
//Modified UI of "Suggest A Feature" pop-up div
//
//*****************  Version 53  *****************
//User: Jaineesh     Date: 3/29/10    Time: 5:18p
//Updated in $/LeapCC/Library
//modification in files according to add new fileds, show exp. &
//qualification
//
//*****************  Version 52  *****************
//User: Rajeev       Date: 10-03-26   Time: 1:26p
//Updated in $/LeapCC/Library
//Updated withs fees define
//
//*****************  Version 51  *****************
//User: Jaineesh     Date: 3/26/10    Time: 1:17p
//Updated in $/LeapCC/Library
//define new arrays for employee
//
//*****************  Version 50  *****************
//User: Jaineesh     Date: 3/18/10    Time: 5:16p
//Updated in $/LeapCC/Library
//changes done according to show status Absent, Detained
//
//*****************  Version 49  *****************
//User: Parveen      Date: 3/18/10    Time: 10:43a
//Updated in $/LeapCC/Library
//$enquiryStatusArr updated
//
//*****************  Version 48  *****************
//User: Parveen      Date: 3/15/10    Time: 11:52a
//Updated in $/LeapCC/Library
//$enquiryStatusArr array added
//
//*****************  Version 47  *****************
//User: Parveen      Date: 3/02/10    Time: 4:25p
//Updated in $/LeapCC/Library
//$visitorSource array define
//
//*****************  Version 46  *****************
//User: Parveen      Date: 2/25/10    Time: 4:23p
//Updated in $/LeapCC/Library
//$feeTypeArr added
//
//*****************  Version 45  *****************
//User: Vimal        Date: 2/22/10    Time: 2:36p
//Updated in $/LeapCC/Library
//Moved SMS related details (Variables and Details) from common.inc.php
//to dbConfig.inc.php.
//Set the default value of MULTI_INSTITUTE to 1 in dbConfig.inc.php. 
//
//*****************  Version 44  *****************
//User: Dipanjan     Date: 5/02/10    Time: 17:25
//Updated in $/LeapCC/Library
//Added 5 new defines for adv. feedback modules.
//
//*****************  Version 43  *****************
//User: Gurkeerat    Date: 1/23/10    Time: 3:10p
//Updated in $/LeapCC/Library
//Added defines "UI_HTTP_TEACHER_PATH", "UI_HTTP_STUDENT_PATH",
//"UI_HTTP_PARENT_PATH"
//
//*****************  Version 42  *****************
//User: Gurkeerat    Date: 1/22/10    Time: 1:04p
//Updated in $/LeapCC/Library
//added array $statusCategoryArr2
//
//*****************  Version 41  *****************
//User: Parveen      Date: 1/09/10    Time: 3:20p
//Updated in $/LeapCC/Library
//added $reppearStatusArr for Student internal re-appear subject status
//
//*****************  Version 40  *****************
//User: Dipanjan     Date: 9/01/10    Time: 15:15
//Updated in $/LeapCC/Library
//Added $advFeedBackRelationship array for advanced feedback modules
//
//*****************  Version 39  *****************
//User: Vimal        Date: 19-12-09   Time: 10:27a
//Updated in $/LeapCC/Library
//Added two constants for attendance evaluation criteria
//
////FOR ATTENDANCE EVALUATION CRITERIAS
//define('PERCENTAGES',5);
//define('SLABS',6);
//
//*****************  Version 38  *****************
//User: Vimal        Date: 18-12-09   Time: 3:40p
//Updated in $/LeapCC/Library
//Move some variables like CLIENT _NAME,  ADMIN_MSG_EMAIL,
//ERROR_MAIL_TO,  MAXIMUM_FILE_SIZE, INCLUDE_ACCOUNTS from common.inc.php
//to dbConfig.inc.php which contains different data values when setting
//up a new account for an institute. 
//Added MULTI_INSTITUTE  for Multi Institute Setup in dbConfig.inc.php
//and added
// if (file_exists($accountsMessagesFileName)) {
//	require_once($accountsMessagesFileName);
//}
// in common.inc.php .  
//
//
//*****************  Version 37  *****************
//User: Gurkeerat    Date: 12/07/09   Time: 6:17p
//Updated in $/LeapCC/Library
//defined 'HELP_VIDEO_PATH'
//
//*****************  Version 36  *****************
//User: Rajeev       Date: 09-11-21   Time: 3:27p
//Updated in $/LeapCC/Library
//Updated with fee type array
//
//*****************  Version 35  *****************
//User: Vimal        Date: 11/12/09   Time: 3:10p
//Updated in $/LeapCC/Library
//Removed all information related to database variables and included a
//dbConfig.inc.php file which contains db configuration details. Also
//added  SERVER for Server Name on which the application is uploaded and
//ACCOUNT_NAME for name of the directory where all the files copied on
//server OR Account/Project Name Like LeapCC/CIET.
//
//*****************  Version 34  *****************
//User: Dipanjan     Date: 5/11/09    Time: 10:49
//Updated in $/LeapCC/Library
//Modified $adjustmentTypeArr array by adding swap/substitution value in
//it
//
//*****************  Version 33  *****************
//User: Jaineesh     Date: 11/02/09   Time: 5:34p
//Updated in $/LeapCC/Library
//put adjustment type array
//
//*****************  Version 32  *****************
//User: Parveen      Date: 10/30/09   Time: 5:17p
//Updated in $/LeapCC/Library
//HELP_PERMISSION added (Used to make help facility On[1]/Off[other than
//1] )
//
//*****************  Version 31  *****************
//User: Dipanjan     Date: 8/10/09    Time: 12:51
//Updated in $/LeapCC/Library
//Modified how paging parameters get their values.Now their values are
//coming from "config" table.
//
//*****************  Version 30  *****************
//User: Pushpender   Date: 9/10/09    Time: 6:42p
//Updated in $/LeapCC/Library
//added CLIENT_NAME variable to identify the Client and which SMS Gateway
//to be used while sending SMS
//
//*****************  Version 29  *****************
//User: Pushpender   Date: 8/25/09    Time: 11:21a
//Updated in $/LeapCC/Library
//Added defines to handle multiple institutes
//
//*****************  Version 28  *****************
//User: Pushpender   Date: 8/19/09    Time: 1:00p
//Updated in $/LeapCC/Library
//defined ERROR_MAIL_TO variable to send mail on sql query failure
//
//*****************  Version 27  *****************
//User: Ajinder      Date: 8/10/09    Time: 6:50p
//Updated in $/LeapCC/Library
//included common messaging file.
//
//*****************  Version 26  *****************
//User: Ajinder      Date: 8/10/09    Time: 1:25p
//Updated in $/LeapCC/Library
//added define for 'accounts' module
//
//*****************  Version 25  *****************
//User: Parveen      Date: 7/15/09    Time: 3:30p
//Updated in $/LeapCC/Library
//$publisherScopeArr, $seminarParticipationArr Array Define
//
//*****************  Version 24  *****************
//User: Pushpender   Date: 7/10/09    Time: 3:41p
//Updated in $/LeapCC/Library
//changed the IP that sends SMS
//
//*****************  Version 23  *****************
//User: Jaineesh     Date: 7/06/09    Time: 4:55p
//Updated in $/LeapCC/Library
//put new fine category 
//
//*****************  Version 22  *****************
//User: Parveen      Date: 6/15/09    Time: 2:03p
//Updated in $/LeapCC/Library
//added $busPassArray
//
//*****************  Version 21  *****************
//User: Rajeev       Date: 6/01/09    Time: 7:22p
//Updated in $/LeapCC/Library
//changed 'Metric' to 'Matric' in class array
//
//*****************  Version 20  *****************
//User: Rajeev       Date: 6/01/09    Time: 2:39p
//Updated in $/LeapCC/Library
//added "suggest a feature" dropdown parameter array
//
//*****************  Version 19  *****************
//User: Pushpender   Date: 5/29/09    Time: 3:49p
//Updated in $/LeapCC/Library
//changed the URL to send SMS "http://72.232.217.94/send.php"
//
//*****************  Version 17  *****************
//User: Rajeev       Date: 5/27/09    Time: 7:00p
//Updated in $/LeapCC/Library
//added 'NATA' in entrance exam name array
//
//*****************  Version 15  *****************
//User: Jaineesh     Date: 5/07/09    Time: 5:43p
//Updated in $/LeapCC/Library
//define variable no data found
//
//*****************  Version 14  *****************
//User: Jaineesh     Date: 5/07/09    Time: 5:33p
//Updated in $/LeapCC/Library
//define No Data Found
//
//*****************  Version 13  *****************
//User: Jaineesh     Date: 5/05/09    Time: 12:00p
//Updated in $/LeapCC/Library
//added arrays made by Gurkeerat and added in VSS by Jaineesh
//
//*****************  Version 12  *****************
//User: Parveen      Date: 4/23/09    Time: 4:00p
//Updated in $/LeapCC/Library
//icardArr Remove
//
//*****************  Version 11  *****************
//User: Parveen      Date: 4/23/09    Time: 1:15p
//Updated in $/LeapCC/Library
//Array added  icardArr
//
//*****************  Version 10  *****************
//User: Parveen      Date: 4/14/09    Time: 5:04p
//Updated in $/LeapCC/Library
//timeTableFormatArr function added
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 10/04/09   Time: 13:33
//Updated in $/LeapCC/Library
//Added $busRepairTypeArr variable
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 1/04/09    Time: 15:14
//Updated in $/LeapCC/Library
//Added   Transport Staff Type array
//
//*****************  Version 7  *****************
//User: Pushpender   Date: 4/01/09    Time: 2:00p
//Updated in $/LeapCC/Library
//added ppt extension in $allowedExtension variable
//
//*****************  Version 6  *****************
//User: Pushpender   Date: 3/21/09    Time: 6:21p
//Updated in $/LeapCC/Library
//added $programType variable for event calendar whether event or holiday
//
//*****************  Version 5  *****************
//User: Pushpender   Date: 3/06/09    Time: 10:54a
//Updated in $/LeapCC/Library
//added extensions for office 2007
//
//*****************  Version 4  *****************
//User: Parveen      Date: 1/12/09    Time: 3:22p
//Updated in $/LeapCC/Library
//ADD VARIABLES FOR ICARD PRINTING
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 1/06/09    Time: 12:44p
//Updated in $/LeapCC/Library
//added "Other" in exam type array
//
//*****************  Version 2  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:37p
//Updated in $/LeapCC/Library
//changed DB account details
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:08p
//Created in $/LeapCC/Library
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library
//
//*****************  Version 56  *****************
//User: Dipanjan     Date: 11/29/08   Time: 11:06a
//Updated in $/Leap/Source/Library
//Added variable for bulk sms and bulk emails
//
//*****************  Version 55  *****************
//User: Pushpender   Date: 11/19/08   Time: 12:26p
//Updated in $/Leap/Source/Library
//added activity Global Variables to show or hide Quiz in time table
//
//*****************  Version 54  *****************
//User: Pushpender   Date: 11/07/08   Time: 11:52a
//Updated in $/Leap/Source/Library
//added 4 global variables for Menus and changed the variable
//ADMIN_MSG_EMAIL 's value to 'erp.chitkarauniversity.edu.in'
//
//*****************  Version 53  *****************
//User: Pushpender   Date: 11/03/08   Time: 1:48p
//Updated in $/Leap/Source/Library
//Rolled Back Praveen's Changes
//
//*****************  Version 51  *****************
//User: Pushpender   Date: 10/23/08   Time: 1:10p
//Updated in $/Leap/Source/Library
//modified function redirectBrowser, added ob_start and ob_flush to take
//care 'headers already sent' warning
//
//*****************  Version 50  *****************
//User: Rajeev       Date: 10/22/08   Time: 2:22p
//Updated in $/Leap/Source/Library
//updated with managment path and role
//
//*****************  Version 49  *****************
//User: Ajinder      Date: 10/08/08   Time: 10:58a
//Updated in $/Leap/Source/Library
//added checkAccessArray
//
//*****************  Version 48  *****************
//User: Pushpender   Date: 10/06/08   Time: 4:37p
//Updated in $/Leap/Source/Library
//changed the value of maximum_file_size to 1 MB
//
//*****************  Version 47  *****************
//User: Dipanjan     Date: 10/03/08   Time: 6:03p
//Updated in $/Leap/Source/Library
//Added ADMIN_MSG_EMAIL which will be used to send email messages
//to employees and students
//
//*****************  Version 46  *****************
//User: Pushpender   Date: 10/01/08   Time: 6:38p
//Updated in $/Leap/Source/Library
//increased the value of maximum_ file_ size variable
//
//*****************  Version 44  *****************
//User: Dipanjan     Date: 9/29/08    Time: 3:54p
//Updated in $/Leap/Source/Library
//Added 
//RECORDS_PER_PAGE_ADMIN_MESSAGE_EMPLOYEE variable
//to define no of records to be shown in teacher section for admin
//messages
//
//*****************  Version 43  *****************
//User: Pushpender   Date: 9/26/08    Time: 12:29p
//Updated in $/Leap/Source/Library
//corrected wednesday spellings and added a commented code for DB
//connection
//
//*****************  Version 42  *****************
//User: Rajeev       Date: 9/24/08    Time: 1:03p
//Updated in $/Leap/Source/Library
//added "EVENT_DAY_PRIOR"
////Number of days for showing events prior to current date
//
//*****************  Version 41  *****************
//User: Kabir        Date: 23/09/08   Time: 1:14p
//Updated in $/Leap/Source/Library
//added variables for SMS
//
//*****************  Version 40  *****************
//User: Rajeev       Date: 9/19/08    Time: 3:25p
//Updated in $/Leap/Source/Library
//added common student photo storage path
//
//*****************  Version 39  *****************
//User: Dipanjan     Date: 9/19/08    Time: 2:51p
//Updated in $/Leap/Source/Library
//Added STUDENT_PHOTO_PATH variable which is to be used where 
//student photo is to be uploaded or retrived
//
//*****************  Version 38  *****************
//User: Rajeev       Date: 9/18/08    Time: 8:02p
//Updated in $/Leap/Source/Library
//added $daysSingleArr  for master time table
//
//*****************  Version 37  *****************
//User: Pushpender   Date: 9/17/08    Time: 9:39p
//Updated in $/Leap/Source/Library
//undid Jaineesh changes
//
//*****************  Version 35  *****************
//User: Pushpender   Date: 9/17/08    Time: 11:35a
//Updated in $/Leap/Source/Library
//increased the value from 15 to 25 for records per page
//
//*****************  Version 34  *****************
//User: Jaineesh     Date: 9/09/08    Time: 5:59p
//Updated in $/Leap/Source/Library
//define new variable NOT_APPLICABLE_STRING to show value in student
//information
//
//*****************  Version 33  *****************
//User: Rajeev       Date: 9/05/08    Time: 3:22p
//Updated in $/Leap/Source/Library
//added CURRENT_PROCESS_FOR for "CU Process"
//
//*****************  Version 32  *****************
//User: Rajeev       Date: 9/02/08    Time: 3:49p
//Updated in $/Leap/Source/Library
//added variable to fetch fee receipt favor text
//
//*****************  Version 31  *****************
//User: Dipanjan     Date: 9/01/08    Time: 5:49p
//Updated in $/Leap/Source/Library
//Added RECORDS_PER_PAGE_ADMIN_MESSAGE to be used for pagination in admin
//message section
//
//*****************  Version 30  *****************
//User: Dipanjan     Date: 9/01/08    Time: 3:43p
//Updated in $/Leap/Source/Library
//Added RECORDS_PER_PAGE_TEACHER constant for teacher module
//
//*****************  Version 29  *****************
//User: Arvind       Date: 9/01/08    Time: 3:38p
//Updated in $/Leap/Source/Library
//
//*****************  Version 28  *****************
//User: Rajeev       Date: 8/28/08    Time: 4:51p
//Updated in $/Leap/Source/Library
//update receipt and instrument status
//
//*****************  Version 27  *****************
//User: Pushpender   Date: 8/27/08    Time: 1:35p
//Updated in $/Leap/Source/Library
//updated values in SMS gatway variables
//
//*****************  Version 26  *****************
//User: Pushpender   Date: 8/20/08    Time: 7:11p
//Updated in $/Leap/Source/Library
//add the condition below in queryLog
//
//if(DB_QUERY_LOG==1)
//
//*****************  Version 25  *****************
//User: Pushpender   Date: 8/19/08    Time: 6:37p
//Updated in $/Leap/Source/Library
//added queryLog function
//
//*****************  Version 24  *****************
//User: Pushpender   Date: 8/18/08    Time: 7:55p
//Updated in $/Leap/Source/Library
//defined variables for query log
//
//*****************  Version 23  *****************
//User: Pushpender   Date: 8/18/08    Time: 12:00p
//Updated in $/Leap/Source/Library
//increased the no for records per page
//
//*****************  Version 22  *****************
//User: Rajeev       Date: 8/13/08    Time: 2:41p
//Updated in $/Leap/Source/Library
//added week days array for time table
//
//*****************  Version 21  *****************
//User: Pushpender   Date: 8/09/08    Time: 5:37p
//Updated in $/Leap/Source/Library
//Added SMS Account Detail
//
//*****************  Version 20  *****************
//User: Pushpender   Date: 8/09/08    Time: 1:15p
//Updated in $/Leap/Source/Library
//SMS variables & account detail placed
//
//*****************  Version 19  *****************
//User: Rajeev       Date: 8/06/08    Time: 3:45p
//Updated in $/Leap/Source/Library
//added common array
//
//*****************  Version 18  *****************
//User: Pushpender   Date: 8/06/08    Time: 1:55p
//Updated in $/Leap/Source/Library
//Removed standard messages from common.inc.php and copied to
//message.inc.php , also placed the code to include message.inc file
//
//*****************  Version 17  *****************
//User: Dipanjan     Date: 8/06/08    Time: 11:12a
//Updated in $/Leap/Source/Library
//Defined SMS_MAX_LENGTH(max length of a sms) constant
//
//*****************  Version 16  *****************
//User: Pushpender   Date: 8/01/08    Time: 6:10p
//Updated in $/Leap/Source/Library
//changed db username & pass for centralized db
//
//*****************  Version 15  *****************
//User: Rajeev       Date: 7/21/08    Time: 6:59p
//Updated in $/Leap/Source/Library
//added feereceipt Prefix and feereceipt starting number
//
//*****************  Version 14  *****************
//User: Kabir        Date: 16/07/08   Time: 5:49p
//Updated in $/Leap/Source/Library
//gramatically corrected an error message for define "FAILURE"
//
//*****************  Version 13  *****************
//User: Rajeev       Date: 7/12/08    Time: 1:08p
//Updated in $/Leap/Source/Library
//added "Class seprator" constant
//
//*****************  Version 12  *****************
//User: Pushpender   Date: 7/05/08    Time: 4:54p
//Updated in $/Leap/Source/Library
//Changed LIB_PATH from /Leap/Source To /Leap
//
//*****************  Version 11  *****************
//User: Rajeev       Date: 7/05/08    Time: 2:47p
//Updated in $/Leap/Source/Library
//
//*****************  Version 10  *****************
//User: Jaineesh     Date: 6/27/08    Time: 8:15p
//Updated in $/Leap/Source/Library
//change the path with leap
//
//*****************  Version 9  *****************
//User: Rajeev       Date: 6/27/08    Time: 1:00p
//Updated in $/Leap/Source/Library
//reviewed the  file
//
//*****************  Version 8  *****************
//User: Pushpender   Date: 6/23/08    Time: 3:24p
//Updated in $/Leap/Source/Library
//Added slash(/) in LOCAL_STORAGE_DIR variable and removed '/' in the
//code to make writable log file
//
//*****************  Version 7  *****************
//User: Pushpender   Date: 6/16/08    Time: 7:19p
//Updated in $/Leap/Source/Library
//added slash one more place
//
//*****************  Version 5  *****************
//User: Pushpender   Date: 6/14/08    Time: 5:33p
//Updated in $/Leap/Source/Library
//added Variable MAXIMUM_FILE_SIZE
//
//*****************  Version 4  *****************
//User: Pushpender   Date: 6/14/08    Time: 11:30a
//Updated in $/Leap/Source/Library
//Added Comments Header and the php script to make log file writable


?>
