<?php 
//--------------------------------------------------------
//This file returns the array of subjects, based on class
//
// Author :Rajeev Aggarwal
// Created on : 13-Aug-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
   
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    define("MANAGEMENT_ACCESS",1);
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
     ini_set('MEMORY_LIMIT','10000M'); 
    set_time_limit(0);

    require_once(MODEL_PATH . "/StudentConsolidatedReportManager.inc.php");
    $studentConsolidatedReportManager = StudentConsolidatedReportManager::getInstance();
    
    $timeTable = trim($REQUEST_DATA['timeTable']);
    $degree = trim($REQUEST_DATA['degree']);
    $subjectTypeId = trim($REQUEST_DATA['subjectTypeId']);
    $subjectId = trim($REQUEST_DATA['subjectId']);
    $groupId = trim($REQUEST_DATA['groupId']);
    $reportFor = trim($REQUEST_DATA['reportFor']);
    $marksFor = trim($REQUEST_DATA['marksFor']);
    $average = trim($REQUEST_DATA['average']);
    $percentage = trim($REQUEST_DATA['percentage']);
    $reportForName = trim($REQUEST_DATA['reportForName']);
    $sortField = trim($REQUEST_DATA['sortField']);
    if($sortField == "firstName") {
		$sortField = "studentName";
	}
  //to parse csv values    
    function parseCSVComments($comments) {
       $comments = str_replace('"', '""', $comments);
       $comments = str_ireplace('<br/>', "\n", $comments);
       if(eregi(",", $comments) or eregi("\n", $comments)) {
         return '"'.$comments.'"'; 
       } 
       else {
         return $comments.chr(160); 
       }
    }    
    $sortOrderBy = trim($REQUEST_DATA['sortOrderBy']);
    $showGraceMarks = trim($REQUEST_DATA['showGraceMarks']);
    $showGrades = trim($REQUEST_DATA['showGrades']);
    
    $conditions= " AND c.classId=$degree";
	$conditionsS   = " AND c.classId=$degree";
	if($subjectTypeId){
	    $attCondition  .= " AND su.subjectTypeId = $subjectTypeId";
		$conditions	   .= " AND sub.subjectTypeId = $subjectTypeId";
	}
	if($subjectId){
	    $attCondition  .= " AND su.subjectId = $subjectId";
		$conditions	   .= " AND sub.subjectId = $subjectId";
	}
	if($groupId){
	    $attCondition  .= " AND grp.groupId = $groupId";
		$conditions	   .= " AND sg.groupId = $groupId";
	}
	

		$conditions1	= " AND ttm.conductingAuthority IN (1) ";
		$conditionext   ="  AND ttm.conductingAuthority IN (2)";
	
	if($average==1){
    
		$averagePer	= "  >=$percentage ";
	}
	if($average==2){

		$averagePer	= "  <$percentage ";
	}
       if($average==3){
        $averagePerAtt    = " >=$percentage ";
	}
	if($average==4){
          $averagePerAtt    = "  < $percentage ";
	}
      
if($average!='' && $reportFor=='2'){ 
//Fetch Internal according to pecentage given 
 if($marksFor=='1' && $reportFor=='2' ) { 
      $conditionForPer=" conductingAuthority IN (1,3)";
      if($subjectId){
        $degreeAndSubject="AND classId =$degree AND subjectId=$subjectId";
            } 
      else{
          $degreeAndSubject="AND classId =$degree";
         }
      $studentPerArray=$studentConsolidatedReportManager->getStudentWithPer($conditionForPer,$degreeAndSubject,$averagePer);
      $count=count($studentPerArray);
      $studentId=0;
      for($i=0;$i<$count;$i++){
        $studentIds.=",".$studentPerArray[$i]['studentId'];
         }
      $trimmedIds = ltrim($studentIds, " , ");     
   }

//Fetch External according to pecentage given 
  if($marksFor=='2' && $reportFor=='2' ) {
    $conditionForPer=" conductingAuthority IN (2)";
    if($subjectId){
      $degreeAndSubject="AND classId =$degree AND subjectId=$subjectId";
           } 
      else{
         $degreeAndSubject="AND classId =$degree";
            }
    $studentPerArray=$studentConsolidatedReportManager->getStudentWithPer($conditionForPer,$degreeAndSubject,$averagePer);
    $countExt=count($studentPerArray);
    for($j=0;$j<$countExt;$j++){
      $studentIds.=",".$studentPerArray[$j]['studentId'];
          }
         $trimmedIds = ltrim($studentIds, " , ");
    }

//Fetch Both  Internal and External according to pecentage given 
   if($marksFor=='0' && $reportFor=='2' ) {
     $conditionForPer=" conductingAuthority IN (1,2,3)";
     if($subjectId){
       $degreeAndSubject="AND classId =$degree AND subjectId=$subjectId";
           } 
     else{
       $degreeAndSubject="AND classId =$degree";
           }
     $studentPerArray=$studentConsolidatedReportManager->getStudentWithPer($conditionForPer,$degreeAndSubject,$averagePer);
     $countExt=count($studentPerArray);
     for($k=0;$k<$countExt;$k++){
        $studentIds.=",".$studentPerArray[$k]['studentId'];
           }
     $trimmedIds = ltrim($studentIds, " , ");
       }
}

//Fetch Attendance Marks according to pecentage given 
if($average!='' && $reportFor=='1'){   
      $conditionForPer=" conductingAuthority IN (3)";
      if($subjectId){
        $degreeAndSubject="AND classId =$degree AND subjectId=$subjectId";
           } 
      else{
        $degreeAndSubject="AND classId =$degree";
           }
      $attendancePerArray=$studentConsolidatedReportManager->getStudentWithPer($conditionForPer,$degreeAndSubject,$averagePerAtt);
      $countAtt=count($attendancePerArray);
      for($k=0;$k<$countAtt;$k++){
        $studentIds.=",".$attendancePerArray[$k]['studentId'];
           }
      $trimmedIdsForAtt = ltrim($studentIds, " , ");
       
}

// Fetch Attendance
    $orderBy = " rollNo ASC, subjectCode ";
    if($sortOrderBy=='DESC'){
       $orderBy = " rollNo DESC, subjectCode ";
    }
    
    // marksFor = 0 =Both 1 => inter ,2=>ext
    // reportFor = 2 Marks, 1 Att 

    if($reportFor=='1'){
        if($subjectTypeId){
	    $condition  .= " AND su.subjectTypeId = $subjectTypeId";
               }
	if($subjectId){
	    $condition  .= " AND su.subjectId = $subjectId";
       	       }
	if($groupId){
	    $condition  .= " AND grp.groupId = $groupId";
         	}
       $consolidate='1';
	$limit='';
      
       //$attendanceArray = $studentConsolidatedReportManager->getStudentAttendanceReport($condition,$orderBy,$consolidate,$limit, $percentCondition);
        if($reportFor=='1')
         {
            $conditions1=" AND ttm.conductingAuthority IN (3) ";
            }
        $attendanceArray=$studentConsolidatedReportManager->getConsolidatedMarksDetails($conditions.$conditions1,$conditions2,$sortField,$sortOrderBy);
       $countAtt=count($attendanceArray);
}
  
    // Fetch Student List
	$condition='';
	if($groupId){
	    $attCondition  .= " AND grp.groupId = $groupId";
		$condition	   .= "  AND sg.groupId = '$groupId'";
	}
    $condition .= " AND c.classId = '$degree' ";
    $orderBy = " ASC";
    if($sortOrderBy=='DESC'){
       $orderBy = " DESC";
         }
     $studentArray =  $studentConsolidatedReportManager->getStudentList($condition,$sortField,$orderBy); 
   
    
    // Fetch Subject List
    $condition = " AND c.classId = '$degree' ";  
    if($subjectId!=''){
       $condition = " AND su.subjectId='$subjectId' ";
    }

    $groupBy='';
    $orderBy=' subjectCode ASC';
    $subjectArray =  $studentConsolidatedReportManager->getSubjectList($condition,$groupBy,$orderBy); 
    $recordCount = count($subjectArray);
    $subjectIds =0;
	  for($i=0; $i<$recordCount; $i++) {
        $subjectIds .=",".$subjectArray[$i]['subjectId'];
      }
//Fetch Employee details
/*$employeeName='';
       $tableName = "employee emp, subject su,  ".TIME_TABLE_TABLE."  tt, `group` g ";
       $fieldsName ="su.subjectId, GROUP_CONCAT(DISTINCT CONCAT(emp.employeeName,' (',emp.employeeCode,')') ORDER BY emp.employeeName SEPARATOR ', ') AS employeeName";
       $empCondition = " WHERE
                                tt.timeTableLabelId=$timeTable AND
                                tt.toDate IS NULL AND
                                g.groupId = tt.groupId AND
                                tt.subjectId = su.subjectId AND
                                tt.subjectId IN ($subjectIds) AND
                                emp.employeeId = tt.employeeId AND
                                g.classId = $degree
                                $showGroupG
                                $conditionsEmp
                          GROUP BY
                                su.subjectId
                          ORDER BY
                                su.subjectTypeId, su.subjectCode  ";
       $employeeArray = $studentConsolidatedReportManager->getSingleField($tableName, $fieldsName, $empCondition);
      $employeeList1 = '';
       if(count($subjectArray)>0) {
           $employeeList1 = "<table width='100%' border='1' class='reportTableBorder'>
                                  <tr>
                                     <td width='2%' class='dataFont'><b>#</b></td>
                                     <td width='10%' class='dataFont'><b>Subject Code</b></td>
                                     <td width='25%' class='dataFont'><b>Subject Name</b></td>
                                     <td width='65%' class='dataFont'><b>Teacher</b></td>
                                  </tr>";

           $j=0;
           for($i=0;$i<count($subjectArray);$i++) {
              $employeeList1 .= "<tr>
                                 <td class='dataFont'>".($i+1)."</nobr></td>
                                 <td class='dataFont'>".$subjectArray[$i]['subjectCode']."</td>
                                 <td class='dataFont'>".$subjectArray[$i]['subjectName']."</td>";
              $employeeName ='';
              $subjectId = $subjectArray[$i]['subjectId'];
              if($employeeArray[$j]['subjectId']==$subjectId) {
                $employeeList1 .= "<td width='98%' class='dataFont'>".$employeeArray[$j]['employeeName']."</td>";
                $j++;
              }
              else {
                $employeeList1 .= "<td width='98%' class='dataFont'>".NOT_APPLICABLE_STRING."</td>";
              }
              $employeeList1 .= "</tr>";
           }
           $employeeList1 .= "</table>";
       }*/
    
    //Fetch  consolidated Marks
     
    if(($marksFor=='0' || $marksFor=='1') && $reportFor=='2' ) {
      $internalMarksArray = $studentConsolidatedReportManager->getConsolidatedMarksDetails($conditions.$conditions1,$conditions2,$sortField,$sortOrderBy);
    }
    if(($marksFor=='0' || $marksFor=='2') && $reportFor=='2' ) {  
      $externalMarksArray = $studentConsolidatedReportManager->getConsolidatedMarksDetails($conditions.$conditionext,$conditions2,$sortField,$sortOrderBy);
    }
    
    $csvData = ""; 
    $csvData =  '#,Univ Roll No.,Roll No.,Student Name';
                      
    $tableTest = "";
   for($i=0; $i<count($subjectArray); $i++) {

        $subjectCode = $subjectArray[$i]['subjectCode'];
        $colspan='';
        $maxExternalMarksHeader= $externalMarksArray[$i]['externalTotalMarks'];
	$maxInternalMarksHeader=$internalMarksArray[$i]['internalTotalMarks'];
        $showGraceMarks = trim($REQUEST_DATA['showGraceMarks']);
        $showGrades = trim($REQUEST_DATA['showGrades']);

		if($marksFor=='0' && $reportFor=='2') {
           $val='4';  
           $tableTest .=",I(".$maxInternalMarksHeader.")";
           if($showGraceMarks=='1') {
              $tableTest .=",GR"; 
              $val++;
           }
           $tableTest .=",E(".$maxExternalMarksHeader.")";
           
                         
           if($showGrades=='1') {
              $tableTest .=",GD";
              $val++;
           }$totalMaxMarks=$maxInternalMarksHeader+$maxExternalMarksHeader;
           $tableTest .=",T(".$totalMaxMarks.")";
           $tableTest .=",%age";
                         
        }
        else {
            $val='1';
            if($marksFor=='1' && $reportFor=='2') {   
                $tableTest .=",I(".$maxInternalMarksHeader.")";
                
                if($showGraceMarks=='1') {
		   $val='2';
                   $tableTest .=',GR,T('.$maxInternalMarksHeader.")";
                   $val++;
                }              
             }
            if($marksFor=='2' && $reportFor=='2') {    
		 $val='1';
                $tableTest .=",E(".$maxExternalMarksHeader.")";
             
                if($showGrades=='1') {
                  $tableTest .=',GD';
                  $val++;
                }              
             }
            if($reportFor=='1') {     
                $tableTest .='A';
               }
            $colspan="colspan='$val'";     
            if($val=='1') {
              $colspan='';
              }
        }
        $csvData .= ','.$subjectCode;
        for($j=1;$j<$val;$j++) {
          $csvData .=",";  
        } 
    }
   $csvData .= "\n";
    $csvData .=',,,'.$tableTest;
    $csvData .="\n"; 
    $resultValueArray = array();
    for($i=0; $i<count($studentArray); $i++) {
        $srNo = ($i+1);
        $studentId = $studentArray[$i]['studentId'];
        $classId = $studentArray[$i]['classId'];
        $univRollNo=$studentArray[$i]['universityRollNo'];
        $studentName = $studentArray[$i]['studentName'];
        $rollNo = $studentArray[$i]['rollNo'];
        
         $csvData .=$srNo.','.parseCSVComments($univRollNo).','.parseCSVComments($rollNo).','.parseCSVComments($studentName);

        $att='-1';                         
        if($reportFor=='1') {                                                        
            for($j=0; $j<count($attendanceArray); $j++) {  
               $aStudentId = $attendanceArray[$j]['studentId'];
               if($aStudentId==$studentId) {
                 $att=$j;
                 break;  
               }
            }
        }
        
        $intMks='-1';
        if(($marksFor=='0' || $marksFor=='1') && $reportFor=='2' ) {      
            for($k=0; $k<count($internalMarksArray); $k++) {  
               $internalStudentId = $internalMarksArray[$k]['studentId'];
               if($internalStudentId==$studentId) {
                 $intMks=$k;
                 break;  
               }
            }
        }
        
        $extMks='-1';
        if(($marksFor=='0' || $marksFor=='2') && $reportFor=='2' ) {  
            for($l=0; $l<count($externalMarksArray); $l++) {  
               $aStudentId = $externalMarksArray[$l]['studentId'];
               if($aStudentId==$studentId) {
                 $extMks=$l;
                 break;  
               }
            }
        }
        $csvData .= getResult($studentId,$subjectId,$classId,$att,$intMks,$extMks,$showGraceMarks,$showGrades);
         $csvData .= "\n";
        
    }

    if($i==0) {
       $csvData .= ",No Data Found"; 
    }
    else {
       UtilityManager::makeCSV($csvData,'StudentConslidatedReport.csv');  
    }
die;
  
  
       
function getResult($studentId,$subjectId='',$classId,$att,$intMks,$extMks,$showGraceMarks='0',$showGrades='0') {
    
     global $subjectArray;
     global $attendanceArray;
     global $internalMarksArray;
     global $externalMarksArray;
     global $reportFor;
     global $marksFor;
      global $studentConsolidatedReportManager;
     $result ='';
     $extraColumn='';
      
     for($j=0; $j<count($subjectArray); $j++) {  
           $subjectId = $subjectArray[$j]['subjectId'];  
           $tempSubjectCode = $subjectArray[$j]['subjectCode'];      
           $total=0;
	       $percentage=0;
          
           // Show Student Attendance Data
	       if($reportFor=='1'){
		         if($att=='-1') {
		           $result .= ','.NOT_APPLICABLE_STRING;   
		       }
		       else {
		             /*$attended = $attendanceArray[$att]['attended'];
		             $delivered = $attendanceArray[$att]['delivered'];    
		             $leaveTaken = $attendanceArray[$att]['leaveTaken'];    
		             $medicalLeaveTaken = $attendanceArray[$att]['medicalLeaveTaken'];*/
                             $attended = $attendanceArray[$att]['marksScored'];
                             $delivered = $attendanceArray[$att]['maxMarks'];     
		             $attSubjectId = $attendanceArray[$att]['subjectId'];
		             $attStudentId = $attendanceArray[$att]['studentId'];
		             $showAttendance = $attended.'/'.$delivered; 
		             if($attSubjectId==$subjectId && $attStudentId == $studentId) {
		               $result .= ','.$showAttendance;   
		               $att++;
		             }
		             else  {
		               $result .=','.NOT_APPLICABLE_STRING;    
		             }
		       }
           }
           
 
           // Show Student Internal Marks
           if(($marksFor=='0' || $marksFor=='1') && $reportFor=='2' ) {    
                if($intMks=='-1') {
                 $result .= ','.NOT_APPLICABLE_STRING;   
                }
                else {
                    $internalSubjectId = $internalMarksArray[$intMks]['subjectId'];
                    $internalStudentId = $internalMarksArray[$intMks]['studentId'];
                    $marksScored = round($internalMarksArray[$intMks]['marksScored']);
                    $maxInternalMarks=$internalMarksArray[$intMks]['maxMarks'];
	                //if($internalSubjectId!='' &&  $internalStudentId!=''){
			        $graceMarksArray = $studentConsolidatedReportManager->getGraceMarks($internalStudentId, $classId, $internalSubjectId);
			        //}
		            $graceMarks=$graceMarksArray[0]['graceMarks'];              
                    if($internalSubjectId==$subjectId && $internalStudentId == $studentId) {
                       $result .= ','.$marksScored;   
                       $total += $marksScored+$graceMarks;
                       $intMks++;
                    }
                    else{
                       $result .= ','.NOT_APPLICABLE_STRING;    
                    }
                }
                
                if($showGraceMarks=='1') {
		            if($graceMarks){
			$totalInternalMarks=$marksScored+$graceMarks;
                        $extraColumn = ','.$graceMarks;
                     if($marksFor=='1'){
                        $extraColumn .= ','.$totalInternalMarks;                   }
                   }        
                   else{
		              $extraColumn = ','.NOT_APPLICABLE_STRING; 
		           }
                } 
                $result .=$extraColumn;
           }
           
           $extraColumn='';
           // Show Student External Marks
           if(($marksFor=='0' || $marksFor=='2') && $reportFor=='2' ) {    
                if($extMks=='-1') {
                    $result .= ','.NOT_APPLICABLE_STRING;   
                }
                else {
                    $externalSubjectId = $externalMarksArray[$extMks]['subjectId'];
                    $externalStudentId = $externalMarksArray[$extMks]['studentId'];
                    $externalMarksscored = $externalMarksArray[$extMks]['marksScored'];
                    $maxMarksByAdmin=$externalMarksArray[$extMks]['externalTotalMarks'];
                    $maxMarksByExcel=$externalMarksArray[$extMks]['maxMarks'];
		            $grade=$externalMarksArray[$extMks]['grade'];
                    $finalMarks=round(($externalMarksscored/$maxMarksByExcel)*$maxMarksByAdmin);
                    if($externalSubjectId==$subjectId && $externalStudentId == $studentId) {
                        $result .= ','.$finalMarks;   
                        $total += $finalMarks;
                        $extMks++;
                    }
                    else  {
                        $result .= ','.NOT_APPLICABLE_STRING;    
                    }
                }
                
                if($showGrades=='1') {
		            if($grade){
                      $extraColumn = ','.$grade;
                    }
                    else{
                      $extraColumn = ','.NOT_APPLICABLE_STRING; 
		            }
                    $result .= ','.$extraColumn; 
                }
           } 
           
           if($marksFor=='0' && $reportFor=='2' ) { 
              $result .= ','.$total; 
              $percentage=round($total/($maxInternalMarks+$maxMarksByAdmin)*100,2);
              $result .= ','.$percentage;  
           }
      }  
     
     return $result;  
}
?>
