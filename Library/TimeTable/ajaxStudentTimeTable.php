<?php
//--------------------------------------------------------  
//It contains the time table
//
// Author :Rajeev Aggarwal
// Created on : 07-08-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

 global $FE;
  require_once($FE . "/Library/common.inc.php");
  require_once(BL_PATH . "/UtilityManager.inc.php");
  define('MODULE','COMMON');
  define('ACCESS','view');
  UtilityManager::ifNotLoggedIn(true);
  UtilityManager::headerNoCache();
  
  global $sessionHandler;     
  
    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance(); 
  
    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    
    require_once(MODEL_PATH . "/TimeTableManager.inc.php");
    $timeTableManager = TimeTableManager::getInstance();

    require_once($FE . "/Library/HtmlFunctions.inc.php");      
    $htmlFunctionsManager = HtmlFunctions::getInstance();    
    
    $timetableFormat = $sessionHandler->getSessionVariable('TIMETABLE_FORMAT');
 
    $orderBy =($timetableFormat == 1) ? " ORDER BY periodSlotId, daysOfWeek, LENGTH(periodNumber)+0,periodNumber" : " ORDER BY periodSlotId, LENGTH(periodNumber)+0,periodNumber,daysOfWeek";
    
    $rollNo = $REQUEST_DATA['rollNo'];
	
    
    $tableName = " class c, student s LEFT JOIN `user` u ON s.userId = u.userId ";
    $fieldsName =" CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName, className,
                   IF(IFNULL(s.rollNo,'')='','".NOT_APPLICABLE_STRING."',s.rollNo) AS rollNo,
                   IF(IFNULL(s.universityRollNo,'')='','".NOT_APPLICABLE_STRING."',s.universityRollNo) AS universityRollNo";
    $ssCondition = " WHERE c.classId = s.classId AND (rollNo = '$rollNo' OR userName = '$rollNo')";  
    $findStudentArray = $studentManager->getSingleField($tableName, $fieldsName, $ssCondition);
    
    if(count($studentRecordArray)>0) {      
       $tclassName  =$findStudentArray[0]['className']; 
       $tstudentName  =$findStudentArray[0]['studentName'];
       $trollNo  =$findStudentArray[0]['rollNo']; 
       $tstudentId =$findStudentArray[0]['studentId'];
       $tuniversityRollNo  =$findStudentArray[0]['universityRollNo'];  
       
       $search = "<b>Class&nbsp;:&nbsp;</b>$tclassName
                  <br><b>Name&nbsp;:&nbsp;</b>$tstudentName
                  <br><b>Roll No.&nbsp;:&nbsp;</b>$trollNo
                  <br><b>University Roll No.&nbsp;:&nbsp;</b>$tuniversityRollNo";
    }
    
	if($rollNo != '' ) {
		$studentRecordArray = $timeTableManager->getStudentUser($rollNo);
		$userId = $studentRecordArray[0]['userId'];
        
		$studentRollNoArray = $timeTableManager->getStudentRollNo($rollNo);
		$rollNoCount = $studentRollNoArray[0]['totalRecords'];
		if($userId == '' AND $rollNoCount == 0 ) {
		   echo "<div align='center'><b><br><br>Roll No./User Name doesn't exist</div>";   
           die;
		}                      
	}

	//die('line'.__LINE__);

    $timeTableType = $REQUEST_DATA['timeTableType'];
       
    if($rollNo=='') {
      $rollNo=-1;  
    }
    
    if($timeTableType=='') {
      $timeTableType=0;  
    }

    $condDate='';
    if($timeTableType==2) {
        $fromDate = $REQUEST_DATA['fromDate'];
        $toDate = $REQUEST_DATA['toDate'];
        $condDate = " AND ttl.timeTableType=$timeTableType  AND (tt.fromDate BETWEEN '$fromDate' AND '$toDate') "; 
    }

	if($userId != '') {
		$studentRollNo = $timeTableManager->getStudentRollNoRecord($userId);
		//$userCondition = " AND s.rollNo = '$userId' ";
		$rollNo = $studentRollNo[0]['rollNo'];
	}
 
    $condition = " AND s.rollNo = '$rollNo' ";
    //$conditions='', $order='ORDER BY periodSlotId, LENGTH(periodNumber)+0,periodNumber, daysOfWeek',$startDate='',$endDate='',$fieldName=''
  
    $fieldName = "DISTINCT 
                        sg.studentId, sg.classId, CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
                        className,
                        IF(IFNULL(s.rollNo,'')='','".NOT_APPLICABLE_STRING."',s.rollNo) AS rollNo,
                        IF(IFNULL(s.universityRollNo,'')='','".NOT_APPLICABLE_STRING."',s.universityRollNo) AS universityRollNo,
                        IF(IFNULL(s.fatherName,'')='','".NOT_APPLICABLE_STRING."',s.fatherName) AS fatherName,
                        IF(IFNULL(s.motherName,'')='','".NOT_APPLICABLE_STRING."',s.motherName) AS motherName,
                        IF(IFNULL(s.guardianName,'')='','".NOT_APPLICABLE_STRING."',s.guardianName) AS guardianName";
    $orderFrom = " ORDER BY studentId, classId DESC";                      
    
    $studentRecordArray = $timeTableManager->getStudentShowTimeTable($condition,$orderFrom,'','',$fieldName);

      
    $classId=0;
    $studentId=0;
    if(count($studentRecordArray)>0) {
       $className  =$studentRecordArray[0]['className']; 
       $studentName  =$studentRecordArray[0]['studentName'];
       $rollNo  =$studentRecordArray[0]['rollNo']; 
       $studentId =$studentRecordArray[0]['studentId'];
       $classId =$studentRecordArray[0]['classId'];
       $universityRollNo  =$studentRecordArray[0]['universityRollNo'];  
       
       $search = "<b>Class&nbsp;:&nbsp;</b>$className
                  <br><b>Name&nbsp;:&nbsp;</b>$studentName
                  <br><b>Roll No.&nbsp;:&nbsp;</b>$rollNo
                  <br><b>University Roll No.&nbsp;:&nbsp;</b>$universityRollNo";
    }
    
    if($userId != '' ) {  
		//$search .= "<b>Class&nbsp;:&nbsp;</b>".$className."<br><b>Name&nbsp;:&nbsp;</b>".$studentName."<br><b>User Name&nbsp;:&nbsp;</b>".$REQUEST_DATA['rollNo'];	   
	}
	else {
	//	$search .= "<b>Class&nbsp;:&nbsp;</b>".$className."<br><b>Name&nbsp;:&nbsp;</b>".$studentName."<br><b>Roll No.&nbsp;:&nbsp;</b>".$rollNo;
	}
    if($timeTableType==2) {
      // $search .= "<br><b>From&nbsp;:&nbsp;</b>".UtilityManager::formatDate($fromDate)."<b>&nbsp;To&nbsp;</b>".UtilityManager::formatDate($toDate);      
    }

    $findTimeTable='';
    $chkTable='';
    $results = CommonQueryManager::getInstance()->getTimeTableLabel('');  
    if(isset($results) && is_array($results)) {
       for($i=0; $i<count($results); $i++) {
            //Get the time table date according to class selected
            $timeTableLabelId = $results[$i]['timeTableLabelId'];  
            if($timeTableLabelId=='') {
              $timeTableLabelId=0; 
            }

             // Fetch Period Arrays
            $periodCondition = " tt.timeTableLabelId = ".$timeTableLabelId;
            $orderByList= " p.periodSlotId, LENGTH(p.periodNumber)+0,p.periodNumber";
            $periodSlotArr = $timeTableManager->getTimeTablePeriodList($periodCondition,$orderByList,' DISTINCT p.periodSlotId');   
 
            //Get the time table date according to time table slot wise
            for($ps=0; $ps < count($periodSlotArr); $ps++) {          // Period Slot Wise  --  Start --
               $periodSlotId = $periodSlotArr[$ps]['periodSlotId'];
            
               $periodCondition = " tt.timeTableLabelId = ".$timeTableLabelId." AND p.periodSlotId = ".$periodSlotId;
               $orderByList= " p.periodSlotId, LENGTH(p.periodNumber)+0,p.periodNumber";
               $periodArray = $timeTableManager->getTimeTablePeriodList($periodCondition,$orderByList);  
                                
               $conditions  = " AND sg.studentId=".$studentId." AND cl.classId = $classId";
               $conditions .= " AND tt.timeTableLabelId=".$timeTableLabelId;
               
               $cond1 =  $conditions." AND p.periodSlotId = ".$periodSlotArr[$ps]['periodSlotId']." ".$condDate;  
               
               if($timeTableType==1) {
                   $orderBy =($timetableFormat == 1) ? " ORDER BY periodSlotId, daysOfWeek, LENGTH(periodNumber)+0,periodNumber" : " ORDER BY periodSlotId, LENGTH(periodNumber)+0,periodNumber,daysOfWeek";
               }
               else 
               if($timeTableType==2) {
                  $orderBy = " ORDER BY periodSlotId, fromDate, LENGTH(periodNumber)+0,periodNumber ";   
               }             
                
               if($timeTableType==2) {
                    // Date Format 
                    $fieldName = " DISTINCT tt.fromDate";
                    $orderFrom = " ORDER BY fromDate";
                    $timeTableDateArray = $timeTableManager->getStudentShowTimeTable($cond1,$orderFrom,'','',$fieldName);
               }  
               
               $teacherRecordArray = $timeTableManager->getStudentShowTimeTable($cond1,$orderBy);
               $recordCount = count($teacherRecordArray);
               if($recordCount >0 && is_array($teacherRecordArray)) { 
                   $chkTable='1';
                   if($timeTableType==1) {       
                        if($timetableFormat=='1') {
                           $findTimeTable .= $htmlFunctionsManager->showTimeTablePeriodsColumns($teacherRecordArray,$periodArray);
                           $findTimeTable .= "<br>";
                        }
                        else{ 
                            if($timetableFormat=='2') {
                                $findTimeTable .= $htmlFunctionsManager->showTimeTablePeriodsRows($teacherRecordArray,$periodArray);
                                $findTimeTable .= "<br>";
                            }                  
                        }
                   }
                   else 
                   if($timeTableType==2) {       
                       $findTimeTable .= $htmlFunctionsManager->showTimeTablePeriodsColumns($teacherRecordArray,$periodArray,'0',$timeTableType,$timeTableDateArray);
                       $findTimeTable .= "<br>";
                   }
                }
            } 
       }
    }
  
   echo "<br><table width='100%' class='reportTableBorder'>
                <tr><td width='100%' class='dataFont'  align='left'>$search</td></tr>     
              </table>";
   if($chkTable=='') {
      if($timeTableType == '1') {
         echo "<div align='center'><b>Time Table was not defined in Weekly Basis</b></div></td></tr></table></div>";
      }
      else {
        echo "<div align='center'><b>Time Table was not defined in Daily Basis</b></div></td></tr></table></div>";
      }
   }
   else {
      echo $findTimeTable.'<br><div id = "saveDiv" align="right">
               <input type="image" name="imageField" src="'.IMG_HTTP_PATH.'/print.gif" onClick="printReport()"/>&nbsp;
            </div>';
   }

  
  
//$History: ajaxStudentTimeTable.php $
//
//*****************  Version 8  *****************
//User: Parveen      Date: 4/22/10    Time: 11:51a
//Updated in $/LeapCC/Library/TimeTable
//validation & condition format updated 
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 8/07/09    Time: 4:04p
//Updated in $/LeapCC/Library/TimeTable
//Gurkeerat: updated access defines
//
//*****************  Version 6  *****************
//User: Parveen      Date: 6/04/09    Time: 11:31a
//Updated in $/LeapCC/Library/TimeTable
//Order by Clause Update (LENGTH(p.periodNumber)+0,p.periodNumber)
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 5/30/09    Time: 7:09p
//Updated in $/LeapCC/Library/TimeTable
//Updated with "No Data Found" message
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 5/05/09    Time: 11:22a
//Updated in $/LeapCC/Library/TimeTable
//Changed Time table format so that admin can decide the display of time
//table i.e in periods in rows or periods in column
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 4/03/09    Time: 12:31p
//Updated in $/LeapCC/Library/TimeTable
//Updated time table with groupShort field
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 3/09/09    Time: 12:47p
//Updated in $/LeapCC/Library/TimeTable
//Updated formatting
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 12/10/08   Time: 5:53p
//Created in $/LeapCC/Library/TimeTable
//intial checkin
?>