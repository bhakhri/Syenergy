<?php

//The file contains data base functions work on dashboard
//
// Author : Harpreet
// Created on : 04-12-2012
// Copyright : Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    global $sessionHandler; 
    $roleId=$sessionHandler->getSessionVariable('RoleId');
    if($roleId==4){
      UtilityManager::ifStudentNotLoggedIn(true);
    }
    else{
      UtilityManager::ifParentNotLoggedIn(true);
    }

    //global attendance parameter
    $GlobalAttendanceParameter ='50';

    require_once(MODEL_PATH."/CommonQueryManager.inc.php");
	require_once(MODEL_PATH . "/Student/StudentInformationManager.inc.php");
    $studentInformationManager = StudentInformationManager::getInstance();

    require_once(MODEL_PATH . "/DashBoardManager.inc.php");
    $dashboardManager = DashBoardManager::getInstance();

    $resourceCheck = trim($REQUEST_DATA['resourceCheck']); 
     $studentId= $sessionHandler->getSessionVariable('StudentId');

	if($studentId==''){
	 $studentId=0;
	}
	//migration check for student for previous classes fee
	 $migrationStudyPeriod=0;
        $migratedArray = $studentInformationManager->getStudentMigrationCheck($studentId);
	
	$migrationStudyPeriod=$migratedArray[0]['migrationStudyPeriod'];
	
	 $ttIsLeet = $migratedArray[0]['isLeet']; 

	 if($migrationStudyPeriod=='') {
          $migrationStudyPeriod=0;   
        }     
        
        if($migrationStudyPeriod==0) {
          if($ttIsLeet=='1') {
             $migrationStudyPeriod = 3; 
          }
        }
	
	
	//end
    function trim_output_r($str,$maxlength,$mode=1,$rep='...'){
       $ret=($mode==2?chunk_split($str,30):$str);

       if(strlen($ret) > $maxlength){
          $ret=substr($ret,0,$maxlength).$rep;
       }
      return $ret;
    }


    function trim_output2_r($str,$maxlength) {
        if (strlen($str) > $maxlength) {
            $str = substr($str, 0, $maxlength);
            $str .= '...';
        }
        return $str;
    }

  
    $blockedFlag=0;
    if($roleId==4){  
       //if student login is disabled due to incomplete feedback
       if($sessionHandler->getSessionVariable('UserIdDisabledForInCompleteFeedback')==2){
           $blockedFlag=1;
       }
    }
    
    if($resourceCheck=='1') {
          $studentId= $sessionHandler->getSessionVariable('StudentId');
          $classId= $sessionHandler->getSessionVariable('ClassId');
          if($blockedFlag==0){
            $resourceRecordArray = $studentInformationManager->getStudentCourseResourceList($studentId,$classId,'','courseResourceId DESC',$limit='LIMIT 0,5');
          }
          
          $resourceData = "<table border='0' width='100%' height='200' >
                            <tr>
                                <td valign='top'>
                                    <table width='100%' border='0'>";
          
          $recordCount = count($resourceRecordArray);
          if($recordCount >0 && is_array($resourceRecordArray) ) {
             for($i=0; $i<$recordCount; $i++ ) {
                $bg = $bg =='row0' ? 'row1' : 'row0';
                $title='Uploaded Date : '.strip_slashes($resourceRecordArray[$i]['postedDate']).'   '.trim_output2_r(strip_slashes(strip_tags($resourceRecordArray[$i]['description'])),150,2);
                $resourceData .= "<tr class='".$bg."'>
                                        <td valign='top' class='padding_top' >&bull;&nbsp;&nbsp;
                                          <a href='listStudentInformation.php?tabIndex=4' name='bubble'  title='".$title."' >".trim_output_r(strip_slashes(strip_tags($resourceRecordArray[$i]['subjectCode'])),25).'--'.trim_output_r(strip_slashes(strip_tags($resourceRecordArray[$i]['employeeName'])),25)."</a></td>";
                $fileName = IMG_PATH.'/CourseResource/'.$resourceRecordArray[$i]['attachmentFile'];
                if(file_exists($fileName) && ($resourceRecordArray[$i]['attachmentFile']!='')) {
                  $fileName1 = $resourceRecordArray[$i]['attachmentFile'];
                  $resourceData .= "<td valign='top'>
                                        <a href='Javascript:void(0);' title='Download File'>
                                        <img name='".$fileName1."'  onclick='download(".$resourceRecordArray[$i]['courseResourceId'].");return false;' src='".IMG_HTTP_PATH."/download.gif'></a>
                                        </td>";
                }
                else {
                    $resourceData .= "<td align='center'>".NOT_APPLICABLE_STRING."</td>";
                }
                $resourceData .= "</tr>";
             }
             $resourceData .= "<tr><td colspan='2' align='right' style='padding-right:10px' valign='bottom'>
                                  <a href='listStudentInformation.php?tabIndex=4'><u>Resource Detail</u></a></td></tr>";
          }
          else {
             $resourceData .= "<tr><td style='padding-top:90px;' align='center' class='redColor'>There are no new Resources Uploaded</td></tr>";
          }
          $resourceData .= " </table> </td> </tr> </table> ";
          
          echo $resourceData;
          die;
    }
    
	$studentId= $sessionHandler->getSessionVariable('StudentId');
	$classIdArray = CommonQueryManager::getInstance()->getStudyPeriodData($studentId);
    $cnt = count($classIdArray)-1;
	$classId = $classIdArray[$cnt]['classId'];
    $holdAttendance = $classIdArray[$cnt]['holdAttendance'];
	$holdTestMarks = $classIdArray[$cnt]['holdTestMarks'];
    if($classId=='') {
	  $classId=0;
	}

  
    /// ---------------------- BIRTHDAY WISHES ------------------------------
    $birthDayTakenRecordArray = $studentInformationManager->checkBirthDay();
    $greetingMsg="";
    if($birthDayTakenRecordArray[0]['birthDay'] >0){
      $greetingMsg="WISH YOU A VERY HAPPY BIRTHDAY";
    }
    else {
      $greetingMsg = "Welcome";
    }

    //********For Notices**************
     $noticeRecordArray = $studentInformationManager->getInstituteNotices1();
    //********For Notice(ends)**************

	
    // Teacher Comments
	$totalMessages=$studentInformationManager->getCommentsListing1();

    
	//Admin messages
	$adminMessages=$studentInformationManager->getAdminMessages1();
	
	//Task Messages
	$showTask=$studentInformationManager->getTaskMessages();


////------------------------------------------------------------------------------------------
///////                        FEE ALERT                              ////////////////////////
////------------------------------------------------------------------------------------------
        //alert for time table
        if($roleId==4){ 
          if($_SESSION['StudentAllAlerts']['view']==1) { 
            $timeTableMessages = $studentInformationManager->getStudentTimeTable1($classId);
          }
        }
        
        if($_SESSION['StudentAllAlerts']['view']==1) {
            if($roleId==4){ 
                $feeArray = array();
                if($_SESSION['FEE_ALERT_IN_STUDENT_LOGIN'] == 1){
                    $feeArray = $studentInformationManager->showFeeAlert($migrationStudyPeriod);
                }
                $totalFeeStatus = $studentInformationManager->getFeeStatus();
            }

            /************ testMarks*********/
            $testMartsArray=array();
            if($blockedFlag==0){
	          if($holdTestMarks==0){
	            $testMartsArray = $studentInformationManager->getStudentMarks1($classId);
		      }
            }

            if($roleId==4){     
                /************CODE FOR SHOWING ALERT FOR Remaining EXPECTED DATE OF CHECKOUT*********/
                  $dateInterval=7;
                  $expectedDateArray=array();
                  if($studentId!=''){
                   $expectedDateArray=$studentInformationManager->getExpectedDateOfCheckOut($studentId);
                  }
                  $expectedDateString='';
                  if(is_array($expectedDateArray) and count($expectedDateArray)>0){
                     if($expectedDateArray[0]['daysLeft']<=$dateInterval){
                       $linkString=$expectedDateArray[0]['daysLeft'].' days(s) remaining to checkout from hostel';
                       //$expectedDateString='<a href="Javascript:void(0);" class="redLink" title="'.$linkString.'">'.$linkString.'</a>';
                       $expectedDateString='<span style="font-size: 11px;font-weight: bold;color: #bb0000" >'.$linkString.'</span>';
                     }
                  }
            }
         }
        /************CODE FOR SHOWING ALERT FOR Remaining EXPECTED DATE OF CHECKOUT*********/
?>
