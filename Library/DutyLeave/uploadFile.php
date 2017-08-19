<?php
//-------------------------------------------------------------------
// THIS FILE IS USED TO upload student photo from student login 
// Author : Dipanjan Bhattacharjee
// Created on : (14.06.2010)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//-------------------------------------------------------------------

set_time_limit(0);
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(BL_PATH . "/FileUploadManager.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
define('MODULE','DutyLeaveUpload');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);

$labelId=trim($REQUEST_DATA['labelId']);
if($labelId==''){
   echo '<script language="javascript">parent.fileUploadError("'.SELECT_TIME_TABLE.'")</script>';
   die; 
}

$eventId=trim($REQUEST_DATA['eventId']);
if($eventId==''){
   echo '<script language="javascript">parent.fileUploadError("'.SELECT_DUTY_LEAVE_EVENT.'")</script>';
   die; 
}
//check of max. file size
if($_FILES['dutyLeaveFile']['name']!='' and $_FILES['dutyLeaveFile']['tmp_name']==''){
    echo '<script language="javascript">parent.fileUploadError("Size of file must be euqal to or less than '.round(MAXIMUM_FILE_SIZE/(1024*1024),2).'MB",2)</script>';
    die;
}

$userId=$sessionHandler->getSessionVariable('UserId');
$instituteId=$sessionHandler->getSessionVariable('InstituteId');
$sessionId=$sessionHandler->getSessionVariable('SessionId');

$fileObj = FileUploadManager::getInstance('dutyLeaveFile');
$fileName = $fileObj->tmp;
if($fileName==''){
    echo '<script language="javascript">parent.fileUploadError("'.SELECT_FILE_FOR_UPLOAD.'")</script>';
    die;
}

$serverDate=explode('-',date('Y-m-d'));
$server_date=gregoriantojd($serverDate[1], $serverDate[2], $serverDate[0]);


/*******THIS FUNCTION IS USED TO TEST FOR ALLOWED CHARACTERS*******/
function checkAllowedChars($value){
    $allowed="0123456789,~";
    $val=trim($value);
    if($val==''){
        return 0;
    }
    $cnt=strlen($val);
    for($i=0;$i<$cnt;$i++){
        $c= substr($val,$i,1);
        if(!strpos($allowed,$c)){
            return 0;
        }
    }
    return 1;
}
/*******THIS FUNCTION IS USED TO TEST FOR ALLOWED CHARACTERS*******/

if($fileObj->fileExtension=='xls') {
   require_once(BL_PATH . "/reader.php");
   $data = new Spreadsheet_Excel_Reader();
   $data->setOutputEncoding('CP1251');
   $data->read($fileName); 
   
   require_once(MODEL_PATH . '/DutyLeaveManager.inc.php');
   $dlMgr=DutyLeaveManager::getInstance();
   
   $m=0;
   $sheetNameArray = array();
   $inconsistenciesArray = array();
   $groupInfoMissingArray = array();
   
   while(isset($data->boundsheets[$m]['name'])){
     $sheetNameArray[] =  $data->boundsheets[$m]['name'];
     $m++;
   }
   
   
   $studentMasterArray=array();
   $lc=0;
   if(SystemDatabaseManager::getInstance()->startTransaction()) {
    foreach($sheetNameArray as $sheetIndex=>$value) {
        for ($i = 1; $i <= $data->sheets[$sheetIndex]['numRows']; $i++) {
          if(trim($data->sheets[$sheetIndex]['cells'][1][1]) != "Sr.No") {
            $inconsistenciesArray[] = "Data has not been entered in given format";
            continue;
          }
        }
        if(count($inconsistenciesArray)!=0){
            break;
        }
        for ($i = 2; $i <= $data->sheets[$sheetIndex]['numRows']; $i++) { 
                 
                 $srNo = $data->sheets[$sheetIndex]['cells'][$i][1];
                 $leaveDate = trim($data->sheets[$sheetIndex]['cells'][$i][2]);
                 $rollNo = add_slashes(trim($data->sheets[$sheetIndex]['cells'][$i][3]));
                 $lectures = add_slashes(trim($data->sheets[$sheetIndex]['cells'][$i][4]));
                 $achievement = trim($data->sheets[$sheetIndex]['cells'][$i][5]);
                 $place = trim($data->sheets[$sheetIndex]['cells'][$i][6]);
                 
                 $achievement = str_replace('"',"`",$achievement);  
                 $achievement = str_replace("'","`",$achievement);  
                 $achievement = htmlentities(add_slashes($achievement));
                 
                 $place = str_replace('"',"`",$place);  
                 $place = str_replace("'","`",$place);  
                 $place = htmlentities(add_slashes($place));
                 
                 $rollNo=preg_replace('/\s+/','',$rollNo);
                 $achievement=preg_replace('/\s+/','',$achievement);
                 $place=preg_replace('/\s+/','',$place);
                 
                 if(trim($srNo)=='' or trim($rollNo)==''){
                     continue;
                 }
                 
                 //fetch studentId and classId of this rollno.*******/
                 $studentInfo=$dlMgr->getStudentInfo(' AND s.rollNo = "'.$rollNo.'"',$labelId);
                 $studentId=$studentInfo[0]['studentId'];
                 $classId=$studentInfo[0]['classId'];
                 
                 if($studentId=='' or $classId==''){
                     $inconsistenciesArray[] = "Invalid Roll No. at Sr.No.'$srNo'";
                     continue;    
                 }
                 
                 /*******date input checking*******/
                 $date = explode('.',$leaveDate);
                 $count = count($date);
                 $dutyLeaveDate='';
                 if($count == 3) {
                  $day = $date[0];
                  if(strlen($day)!=2){
                    $inconsistenciesArray[] = "Invalid Date at Sr.No.'$srNo'";
                    continue;  
                  }
                  $month = $date[1];
                  if(strlen($month)!=2){
                    $inconsistenciesArray[] = "Invalid Date at Sr.No.'$srNo'";
                    continue;  
                  }
                  $year = $date[2];
                  if(strlen($year)!=4){
                    $inconsistenciesArray[] = "Invalid Date at Sr.No.'$srNo'";
                    continue;  
                  }
                  $a = checkdate($month, $day, $year);
                  if($a) {
                    $dutyLeaveDate = trim($year).'-'.trim($month).'-'.trim($day);
                    $sDate1=explode('-',$dutyLeaveDate);
                    $duty_date  =gregoriantojd($sDate1[1], $sDate1[2], $sDate1[0]);
                    if(($server_date-$duty_date)<0){
                      $inconsistenciesArray[] = "Date of leave ( $leaveDate ) can not be greater than current date at Sr.No.'$srNo'";
                      continue;
                    }
                  }
                  else {
                    $inconsistenciesArray[] = "Invalid Date at Sr.No.'$srNo'";
                    continue;
                  }
                }
                else {
                    $inconsistenciesArray[] = "Invalid Date at Sr.No.'$srNo'";
                    continue;
                }
                /*******date input checking*******/
                
                /********lecture input checking********/
                if(trim($lectures)==''){
                    $inconsistenciesArray[] = "No lectures entered at Sr.No.'$srNo'";
                    continue;
                }
                if(!checkAllowedChars($lectures)){
                   $inconsistenciesArray[] = "Invalid Lecture format at Sr.No.'$srNo'";
                   continue; 
                }
                $lec=explode(',',$lectures);
                $lecCnt=count($lec);
                $lectureArray=array();
                for($x=0;$x<$lecCnt;$x++){
                    $lec2=explode('~',$lec[$x]);
                    $sLec=$eLec=0;
                    if(count($lec2)==2){
                       $sLec=intval($lec2[0]);
                       $eLec=intval($lec2[1]);
                       if($sLec>$eLec){
                           $temp=$sLec;
                           $sLec=$eLec;
                           $eLec=$temp;
                       }
                       for(;$sLec<=$eLec;$sLec++){
                          $lectureArray[]=$sLec; 
                       }
                    }
                    else{
                      $lectureArray[]=intval($lec[$x]);
                    }
                }
                if(count($lectureArray)==0){
                   $inconsistenciesArray[] = "Invalid Lectures at Sr.No.'$srNo'";
                   continue; 
                }
                if(count($lectureArray)!=count(array_unique($lectureArray))){
                   $inconsistenciesArray[] = "Duplicate Lectures at Sr.No.'$srNo'";
                   continue; 
                }
                /********lecture input checking********/
                
                /*****FINDING PERIOD SLOT of this student******/ 
                $periodSlotArray=$dlMgr->getStudentPeriodSlot($studentId,$classId,$labelId);
                if(count($periodSlotArray)==0){
                   //$inconsistenciesArray[] = "No period slot found for roll no. '$rollNo' at Sr.No.'$srNo'";
                   $inconsistenciesArray[] = "Time table record not found for roll no. '$rollNo' at Sr.No.'$srNo'";
                   continue;  
                }
                if(count($periodSlotArray)>1){
                   $inconsistenciesArray[] = "Multiple period slot found for roll no. '$rollNo' at Sr.No.'$srNo'";
                   continue;
                }
                $periodSlotId=$periodSlotArray[0]['periodSlotId'];
                
                /*****CHECKING FOR PERIOD IDS IN DATABASE******/
                $periodIdArray=$dlMgr->getPeriodIdInfo(implode(',',$lectureArray),$periodSlotId);
                if(count($periodIdArray)!=count($lectureArray)){
                   //$inconsistenciesArray[] = "Mismatched lectures found !!! for roll no. '$rollNo' at Sr.No.'$srNo'";
                   $inconsistenciesArray[] = "Mismatched lectures found for roll no. '$rollNo' at Sr.No.'$srNo'";
                   continue;
                }
                if(count($periodIdArray)==0){
                   //$inconsistenciesArray[] = "Mismatched lectures found @@@ for roll no. '$rollNo' at Sr.No.'$srNo'";
                   $inconsistenciesArray[] = "Mismatched lectures found for roll no. '$rollNo' at Sr.No.'$srNo'";
                   continue;                                            
                }
                $periodIds=UtilityManager::makeCSList($periodIdArray,'periodId');
                
                /**********CHECKING FOR DUPLICATE PERIODS IN EXCEL*********/
                $dupArrFlag=0;
                $dupRows='';
                foreach($studentMasterArray as $masterArray){
                   if($masterArray['studentId']==$studentId and $masterArray['dutyLeaveDate']==$dutyLeaveDate){
                       if($dupRows!=''){
                           $dupRows .=',';
                       }
                       $dupRows .= $masterArray['srNo'];
                       $dupArrFlag=1;
                       //break;
                   }
                }
                if($dupArrFlag==1){
                   $inconsistenciesArray[] = "Duplicate data found for roll no. '$rollNo' at Sr.No(s) '$dupRows,$srNo'";
                   continue;
                }
                
                /***********CHECK for date with  selected event**********/
                $selfEventArray=$dlMgr->checkWithSelfEvent($eventId,$dutyLeaveDate);
                if($selfEventArray[0]['cnt']==0){
                   $inconsistenciesArray[] = "Entered date does not fall within start and end of selected event for roll no. '$rollNo' at Sr.No.'$srNo'";
                   continue;
                }
                
                
                 $insertString='';
                 /*****************LOGIC FOR FETCHING DATA FROM ATTENDANCE TABLE*********************/
                  $daysOfWeek=date('w',strtotime($dutyLeaveDate));
                  
                  $periodIdsArray=explode(',',$periodIds);
                  $periodCnt=count($periodIdsArray);   
                  for($y=0;$y<$periodCnt;$y++){
                      //fetch subject and group from attendance table
                      $attendanceArray=$dlMgr->getSubjectAndGroupInfoFromAttendance($studentId,$classId,$periodIdsArray[$y],$dutyLeaveDate,$labelId);
                      $subjectId=$attendanceArray[0]['subjectId'];
                      $exactGroupId=$attendanceArray[0]['groupId'];
                      if($subjectId==''){
                         $subjectId=-1;
                      }
                      if($exactGroupId==''){
                         $exactGroupId=-1;
                      }
                     
                     //***********CHECK WITH OTHER EVENTS**********
                     
                     $otherEvents='';
                     $dupEventArray=$dlMgr->checkWithOtherEvents($studentId,$classId,$periodIdsArray[$y],$dutyLeaveDate,$eventId,$subjectId,$exactGroupId);
                     if(is_array($dupEventArray) and  count($dupEventArray)>0){
                         //$otherEvents=UtilityManager::makeCSList($dupEventArray,'eventTitle');
						 $returnStatus = $dlMgr->updateEventStatus($studentId, $dutyLeaveDate, $periodIdsArray[$y]);
                     }
                     /*if($otherEvents!=''){
                       $inconsistenciesArray[] = "Entered data conflicts with $otherEvents event(s) for roll no. '$rollNo' at Sr.No.'$srNo'";
                       continue;
                     }*/

                     if($insertString!=''){
                         $insertString .=',';
                     } 
                     if($subjectId==-1){
                         $subjectId='NULL';
                     }
                     if($exactGroupId==-1){
                         $exactGroupId='NULL';
                     }                 
                     $insertString .= " ( $eventId,'$dutyLeaveDate',$studentId,$classId,$periodIdsArray[$y],'$achievement','$place',$instituteId,$sessionId,$subjectId,$exactGroupId ) ";
                  }
                                  
                /**************GET SUBJECT AND GROUP OF THIS STUDENT FOR THIS DATE**********************/
                  //find group info for this student
                  
                  /*******LOGIC FOR FETCHING DATA FROM TIME TABLE********/
                  /*
                  $groupArray=$dlMgr->getStudentGroupInfo($studentId,$classId);
                  if(is_array($groupArray) and count($groupArray)>0){
                   $groupIds=UtilityManager::makeCSList($groupArray,'groupId');
                  }
                  else{
                   $groupIds=-1; 
                  }
                  
                  $daysOfWeek=date('w',strtotime($dutyLeaveDate));
                  
                  $periodIdsArray=explode(',',$periodIds);
                  $periodCnt=count($periodIdsArray);
                  for($y=0;$y<$periodCnt;$y++){
                     //find subjectId for this student
                     $subjectArray=$dlMgr->getStudentSubjectInfoFromTimeTable($studentId,$classId,$groupIds,$periodIdsArray[$y],$daysOfWeek,$labelId);
                     if(is_array($subjectArray) and count($subjectArray)>0){
                       $subjectId=$subjectArray[0]['subjectId'];
                     }
                     else{
                      $subjectId=-1;
                     }
                     
                     //find groupId corresponding to class,subject,period and label
                     $groupExactArray=$dlMgr->getExactGroupIdFromTimeTable($classId,$subjectId,$groupIds,$periodIdsArray[$y],$daysOfWeek,$labelId);
                     $exactGroupId=$groupExactArray[0]['groupId']; 

                     if($exactGroupId==''){
                      //$inconsistenciesArray[] = "Invalid Period information found for roll no. '$rollNo' at Sr.No.'$srNo' for period $periodIdsArray[$y] on ".UtilityManager::formatDate($dutyLeaveDate);
                       //******WHEN NO SUBJECT AND GROUP INFO IS FOUND THEN TREAT THEM AS NULL !!!! ----AS INSTRUCTED BY AJINDER SIR AND SACHIN SIR AS IN 10.11.2010********
                       $exactGroupId=-1;
                       //continue;  
                     }
                     
                     //***********CHECK WITH OTHER EVENTS**********
                     
                      $dupEventArray=$dlMgr->checkWithOtherEvents($studentId,$classId,$periodIdsArray[$y],$dutyLeaveDate,$eventId,$subjectId,$exactGroupId);
                      if($dupEventArray[0]['cnt']!=0){
                       $inconsistenciesArray[] = "Entered data conflicts with other events for roll no. '$rollNo' at Sr.No.'$srNo'";
                       continue;
                      }
                     
                     $otherEvents='';
                     $dupEventArray=$dlMgr->checkWithOtherEvents($studentId,$classId,$periodIdsArray[$y],$dutyLeaveDate,$eventId,$subjectId,$exactGroupId);
                     if(is_array($dupEventArray) and  count($dupEventArray)>0){
                         $otherEvents=UtilityManager::makeCSList($dupEventArray,'eventTitle');
                     }
                     if($otherEvents!=''){
                       $inconsistenciesArray[] = "Entered data conflicts with $otherEvents event(s) for roll no. '$rollNo' at Sr.No.'$srNo'";
                       continue;
                     }

                     if($insertString!=''){
                         $insertString .=',';
                     }
                     if($subjectId==-1){
                         $subjectId='NULL';
                     }
                     if($exactGroupId==-1){
                         $exactGroupId='NULL';
                     }
                     $insertString .= " ( $eventId,'$dutyLeaveDate',$studentId,$classId,$periodIdsArray[$y],'$achievement','$place',$instituteId,$sessionId,$subjectId,$exactGroupId ) ";
                  }
                  */
                 /*******LOGIC FOR FETCHING DATA FROM TIME TABLE********/ 
                  
                /***************************************************************************************/
                
                /*******IF ALL GOES WELL,FIRST DELETE DATA AND ADD NEW RECORDS*********/
                 $delRet=$dlMgr->deleteDutyLeave($studentId,$classId,$dutyLeaveDate,$eventId,$instituteId,$sessionId);
                 if($delRet==false){
                  $inconsistenciesArray[] = "Data could not be saved for roll no. '$rollNo' at Sr.No.'$srNo'";
                  continue;
                 }
                 else{
                   //now insert
                  if($insertString==''){
                      //$inconsistenciesArray[] = "Data could not be saved for roll no. '$rollNo' at Sr.No.'$srNo'";
                      continue;  
                  }
                  else{      
                     $insRet=$dlMgr->insertDutyLeave($insertString); 
                     if($insRet==false){
                      $inconsistenciesArray[] = "Data could not be saved for roll no. '$rollNo' at Sr.No.'$srNo'";
                      continue;
                     }
                  }
                }
                $insertString='';
                $studentMasterArray[$lc]['srNo']=$srNo;
                $studentMasterArray[$lc]['studentId']=$studentId;
                $studentMasterArray[$lc]['classId']=$classId;
                $studentMasterArray[$lc]['dutyLeaveDate']=$dutyLeaveDate;
                $studentMasterArray[$lc]['periodIds']=$periodIds;
                $lc++;
            }
       } 
       if (count($inconsistenciesArray) == 0) {
            if(SystemDatabaseManager::getInstance()->commitTransaction()) {
                $successString = "Data saved successfully for $lc student(s)";
                $i = 1;
                if(count($groupInfoMissingArray)>0){ // when error is of type "Period information missing"
                 $successString .= "\r\n";
                 foreach($groupInfoMissingArray as $key=>$record) {
                  $successString .= "$i $record\r\n";
                  $i++;
                 }
                }
                /*
                $fileName = "DutyLeaveUploaded.txt";
                ob_end_clean();
                header("Cache-Control: public, must-revalidate");
                header("Pragma: hack");
                header("Content-Type: application/octet-stream");
                header("Content-Length: " .strlen($successString));
                header('Content-Disposition: attachment; filename="'.$fileName.'"');
                header("Content-Transfer-Encoding: text\n");
                echo $successString;
                */
                $sessionHandler->setSessionVariable('DutyLeave_UploadStudentDetails',$successString);
                echo '<script language="javascript">parent.fileUploadError("1")</script>';
                die;
            }
        }
        else {
            $csvData = "No row from the uploaded file has been saved\r\n";
            $i = 1;
            foreach($inconsistenciesArray as $key=>$record) {
                $csvData .= "$i $record\r\n";
                $i++;
            }
            if(count($groupInfoMissingArray)>0){ // when error is of type "Period information missing"
              foreach($groupInfoMissingArray as $key=>$record) {
                $csvData .= "$i $record\r\n";
                $i++;
              }
            }
            $csvData = trim($csvData);
            /*
            $fileName = "DutyLeave_Inconsistencies.txt";
            ob_end_clean();
            header("Cache-Control: public, must-revalidate");
            header("Pragma: hack");
            header("Content-Type: application/octet-stream");
            header("Content-Length: " .strlen($csvData));
            header('Content-Disposition: attachment; filename="'.$fileName.'"');
            header("Content-Transfer-Encoding: text\n");
            echo $csvData;
            */
            $sessionHandler->setSessionVariable('DutyLeave_UploadStudentDetails',$csvData);
            echo '<script language="javascript">parent.fileUploadError("0")</script>';
            die;
        }
   }
}
else {
    echo '<script language="javascript">parent.fileUploadError("'.INCORRECT_FILE_EXTENSION.'")</script>';
    die;
}

// $History: fileUpload.php $
?>