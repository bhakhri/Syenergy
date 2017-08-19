<?php

set_time_limit(0);
//global $FE;
$FE= "/var/www/html/ciet/ShortCodeService";
//$FE= "D:/wamp/www/ShortCodeService";
//exit;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
UtilityManager::headerNoCache();
//UtilityManager::sendSMS('9779450739', "hello");
logError("this is the browwser: ".$_SERVER['HTTP_USER_AGENT']);
if(trim($_SERVER['HTTP_USER_AGENT'])=="")
{
    //UtilityManager::sendSMS("9779450739", $_SERVER['HTTP_USER_AGENT']);
    //exit;
    $msg=$_REQUEST['msg'];
    $mobile=$_REQUEST['pno'];
                logError("xxxxxxxxxxxxxxxxxxxxx".$msg);
                logError("xxxxxxxxxxxxxxxxxxxxx".$mobile);
    global $sessionHandler;
    $msg_array=explode(" ",$msg);
    if(count($msg_array)==3)
    {
       if($msg_array[2]=="NOTICE")
        {
            
            require_once(MODEL_PATH . "/CietManager.inc.php");
            require_once(MODEL_PATH . "/PayrollManager.inc.php");
            $studentId = PayrollManager::getInstance()->getSingleField('student','studentId',"where studentMobileNo like'%".$mobile."%'");
            if(count($studentId)==0)
            {
                UtilityManager::sendSMS($mobile, "Your mobile does not match any student's mobile number in college records.","Chitkara");
                exit;
            }
            else
            {
            $notice=CietManager::getInstance()->getNotices(date('Y-m-d'));
            $cnt=count($notice);
            $countStudent=0;
            $countParent=0;
            if($cnt>0)
            {
                for($i=0;$i<$cnt;$i++)
                {
                    $noticeStudent=CietManager::getInstance()->getNoticeStudent($notice[$i]['noticeId'],$mobile);
                    $noticeParent=CietManager::getInstance()->getNoticeParent($notice[$i]['noticeId'],$mobile);
                    if($noticeStudent>0)
                    {
                        $countStudent++;
                    }
                    if($noticeParent>0)
                    {
                        $countParent++;
                    }
                }
                if($countStudent==0 && $countParent==0)
                {
                    echo "no unread messages";
                    UtilityManager::sendSMS($mobile, "No Unread Notices","Chitkara");
                    exit;
                }
                else
                {
                    $msg="Unread Notice(s) Summary:\n Student Notice(s): ".$countStudent."\n Parent Notice(s): ".$countParent."\nPlease visit the website to view these notices";
                    //echo $msg;
                    UtilityManager::sendSMS($mobile, $msg,"Chitkara");
                    exit;
                }
            }
            else
            {
                UtilityManager::sendSMS($mobile, "No notices available","Chitkara");
                exit;
            }
        }
        }
        else
            {
                UtilityManager::sendSMS($mobile, "Invalid Message Format","Chitkara");
                exit;
            } 
    }
    elseif(count($msg_array)==4)
    {
        if($msg_array[2]=="ATT")
        {
            /*if(count($msg_array)!=4)
            {
                UtilityManager::sendSMS($mobile, "Invalid Message Format"); 
                exit();
            }*/
             
            require_once(MODEL_PATH . "/PayrollManager.inc.php");
            require_once(MODEL_PATH."/CommonQueryManager.inc.php");
            $studentId = PayrollManager::getInstance()->getSingleField('student','studentId','where rollNo='."'".$msg_array[3]."'");
            if(count($studentId)==0)
            {
                UtilityManager::sendSMS($mobile, "Invalid Roll No","CHITKARA");
                exit();
            }
            $mobileNo = PayrollManager::getInstance()->getSingleField('student','studentMobileNo','where rollNo='."'".$msg_array[3]."'");
            $classId = PayrollManager::getInstance()->getSingleField('student','classId','where rollNo='."'".$msg_array[3]."'");
            $mobileNo=$mobileNo[0]['studentMobileNo'];
            $studentId=$studentId[0]['studentId'];
            $classId=$classId[0]['classId'];
            $sessionId=PayrollManager::getInstance()->getSingleField('class','sessionId','where classId='.$classId);
            $instituteId=PayrollManager::getInstance()->getSingleField('class','instituteId','where classId='.$classId);
            $sessionId=$sessionId[0]['sessionId'];
            $instituteId=$instituteId[0]['instituteId'];
            $sessionHandler->setSessionVariable('SessionId',$sessionId);
            $sessionHandler->setSessionVariable('InstituteId',$instituteId);
            $sessionInstituteId = $sessionHandler->getSessionVariable('InstituteId');
            if (MULTI_INSTITUTE == 0) {
                $sessionInstituteId = '';        
            }

            define('ATTENDANCE_TABLE',    'attendance'    . $sessionInstituteId);
            logError("xxxxxxxxx".ATTENDANCE_TABLE);

/*            if(count($studentId)>0 && $mobileNo!=$mobile)
            {
                UtilityManager::sendSMS($mobile, "Mobile Number Is Not Registered ", "CHITKARA");
                exit();
            }*/
            $where .= " AND su.hasAttendance = 1 ";
            $studentInformationArray = CommonQueryManager::getInstance()->getConsolidatedStudentAttendance($studentId,$classId,'',$where,"ORDER BY subject ASC");

            if(count($studentInformationArray)==0)
            {
                logError("xxxxxxxxxxxxxxxxxxxxx".count($studentInformationArray));
                logError("xxxxxxxxxxxxxxxxxxxxx".$msg);
                logError("xxxxxxxxxxxxxxxxxxxxx".$mobile);
                UtilityManager::sendSMS($mobile, "No attendance records exists", "CHITKARA");
            }
            else
            {
                //$message=$studentInformationArray[0]['studentName']."-".$studentInformationArray[0]['periodName'];
                $message="";
                $cnt=count($studentInformationArray);
                for($i=0;$i<$cnt;$i++)
                {
                    $message .="".$studentInformationArray[$i]['subjectCode']." ".substr($studentInformationArray[$i]['attended'],0,strpos($studentInformationArray[$i]['attended'],"."))."/".$studentInformationArray[$i]['delivered']." ";
                }
                UtilityManager::sendSMS($mobile, $message, "CHITKARA");
                //echo $message;
            }
        }
        else
        {
            UtilityManager::sendSMS($mobile, "Invalid Message Format", "CHITKARA");
        }
    }
    elseif(count($msg_array)==5)
    {
        
        if($msg_array[2]=="MARKS")
        {
            require_once(MODEL_PATH . "/PayrollManager.inc.php");
            require_once(MODEL_PATH."/CommonQueryManager.inc.php");
            require_once(MODEL_PATH . "/StudentManager.inc.php");
            $studentManager = StudentManager::getInstance();
            $studentId = PayrollManager::getInstance()->getSingleField('student','studentId','where rollNo='."'".$msg_array[3]."'");
            if(count($studentId)==0)
            {
                //echo "Invalid Roll No";
                UtilityManager::sendSMS($mobile, "Invalid Roll No", "CHITKARA");
                exit();
            }
            logError("yyyyyyyyyyyyyyyy".$msg);
            logError("yyyyyyyyyyyyyyyy".$mobile);
            $mobileNo = PayrollManager::getInstance()->getSingleField('student','studentMobileNo','where rollNo='."'".$msg_array[3]."'");
            $classId = PayrollManager::getInstance()->getSingleField('student','classId','where rollNo='."'".$msg_array[3]."'");
            $mobileNo=$mobileNo[0]['studentMobileNo'];
            $studentId=$studentId[0]['studentId'];
            $classId=$classId[0]['classId'];
            $sessionId=PayrollManager::getInstance()->getSingleField('class','sessionId','where classId='.$classId);
            $instituteId=PayrollManager::getInstance()->getSingleField('class','instituteId','where classId='.$classId);
            $sessionId=$sessionId[0]['sessionId'];
            $instituteId=$instituteId[0]['instituteId'];
            logError("zzzzzzzzzzzzz".$sessionId);
            logError("zzzzzzzzzzzzz".$instituteId);
            $sessionHandler->setSessionVariable('SessionId',$sessionId);
            $sessionHandler->setSessionVariable('InstituteId',$instituteId);
            logError("hello");
            $sessionInstituteId = $sessionHandler->getSessionVariable('InstituteId');
            logError("aaaaaaaaaaaaa".$sessionInstituteId);
            /*if (MULTI_INSTITUTE == 0) {
                $sessionInstituteId = '';        
            }

            define('ATTENDANCE_TABLE',    'attendance'    . $sessionInstituteId);
            logError("xxxxxxxxx".ATTENDANCE_TABLE);
            */
         /*   if(count($studentId)>0 && $mobileNo!=$mobile)
            {
                UtilityManager::sendSMS($mobile, "Mobile Number Is Not Registered ", "CHITKARA");
                exit();
            }   */
            define('TEST_TABLE',     'test'      . $sessionInstituteId);
            define('TEST_MARKS_TABLE',    'test_marks'    . $sessionInstituteId);
            $condition = " AND su.hasMarks = 1";
            $studentSectionArray = $studentManager->getStudentMarks($studentId,$classId,'subjectName Asc','',$condition);

            if(count($studentSectionArray)==0)
            {
                logError("xxxxxxxxxxxxxxxxxxxxx".count($studentInformationArray));
                logError("xxxxxxxxxxxxxxxxxxxxx".$msg);
                logError("xxxxxxxxxxxxxxxxxxxxx".$mobile);
                //echo "No marks exists for this test type";
                UtilityManager::sendSMS($mobile, "No attendance records exists", "CHITKARA");
            }
            else
            {
                //$message=$studentInformationArray[0]['studentName']."-".$studentInformationArray[0]['periodName'];
                $message="";
                $cnt=count($studentSectionArray);
                $testType=PayrollManager::getInstance()->getSingleField('test_type_category','testTypeName','where testTypeAbbr='."'".$msg_array[4]."'");
                $countTestType=count($testType);
                //print_r($studentSectionArray);
                if($countTestType>0)
                {
                for($i=0;$i<$cnt;$i++)
                {
                   //echo $studentSectionArray[$i]['testTypeName'], trim($testType[0]['testTypeName']);
                    if(trim($studentSectionArray[$i]['testTypeName'])==trim($testType[0]['testTypeName']))
                    {
                    $message .="".$studentSectionArray[$i]['subjectCode']." ".substr($studentSectionArray[$i]['obtainedMarks'],0,strpos($studentSectionArray[$i]['obtainedMarks'],"."))."/".$studentSectionArray[$i]['totalMarks']." ";
                    }
                }
                //echo $message;
                UtilityManager::sendSMS($mobile, $message, "CHITKARA");
                }
                else
                {
                    echo "Invalid Test Type";
                }
                
                logError("xxxxxxxxxxxxmsg".$message);
                logError("xxxxxxxxxxxxmsg".$msg);
                logError("xxxxxxxxxxxxmsg".$mobile);
                //logError("xxxxxxxxxxxxmsg".$message);
                
            }
        }
        else
        {
            UtilityManager::sendSMS($mobile, "Invalid Message Format", "CHITKARA");
        }
    }
    else
    {
            UtilityManager::sendSMS($mobile, "Invalid Message Format", "CHITKARA");
    }
}
?>
