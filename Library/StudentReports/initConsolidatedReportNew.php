<?php 
//--------------------------------------------------------
//This file returns the array of subjects, based on class
//
// Author :Rajeev Aggarwal
// Created on : 13-Aug-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
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
    $sortOrderBy = trim($REQUEST_DATA['sortOrderBy']);
    $showGraceMarks = trim($REQUEST_DATA['showGraceMarks']);
    $showGrades = trim($REQUEST_DATA['showGrades']);
    
    $conditions= " AND c.classId=$degree";
	$conditionsS   = " AND c.classId=$degree";
	if($subjectTypeId){
	    
		$conditions	   .= " AND sub.subjectTypeId = $subjectTypeId";
	}
	if($subjectId){
	    
		$conditions	   .= " AND sub.subjectId = $subjectId";
	}
	if($groupId){
	    
		$conditions	   .= " AND sg.groupId = $groupId";
	}
	

		$conditions1	= " AND ttm.conductingAuthority IN (1,3) ";
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
	if($groupId!=''){
	    
		$condition.= "  AND sg.groupId = '$groupId'";
	} 
    $condition .= " AND c.classId = '$degree' ";
    $orderBy = " ASC";
    //$sortField='';
    if($sortOrderBy=='DESC'){
     $orderBy = "  DESC";
       }
    if($average!='' && $reportFor=='2'){
        $condition=" AND c.classId = '$degree' AND s.studentId IN ($trimmedIds)"; 
        }
    if($average!='' && $reportFor=='1')
      {
         $condition=" AND c.classId = '$degree' AND s.studentId IN ($trimmedIdsForAtt)"; 
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
$employeeName='';
       $tableName = "employee emp, subject su,  ".TIME_TABLE_TABLE." tt, `group` g ";
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
       }
   
    //Fetch  consolidated Marks
     
    if(($marksFor=='0' || $marksFor=='1') && $reportFor=='2' ) {
      $internalMarksArray = $studentConsolidatedReportManager->getConsolidatedMarksDetails($conditions.$conditions1,$conditions2,$sortField,$sortOrderBy);
     
    }
    if(($marksFor=='0' || $marksFor=='2') && $reportFor=='2' ) {  
      $externalMarksArray = $studentConsolidatedReportManager->getConsolidatedMarksDetails($conditions.$conditionext,$conditions2,$sortField,$sortOrderBy);
    }
    
    
    $tableData = '<table width="100%" border="0" cellspacing="2" cellpadding="0" class="">
                     <tr class="rowheading">
                      <td width="2%" valign="middle" rowspan="2"><b>&nbsp;#</b>
                      <td valign="middle" align="left" rowspan="2" width="8%"><b>Univ Roll No.</b></td>
		      <td valign="middle" align="left" rowspan="2" width="15%"><b>Roll No.</b></td>
                      <td valign="middle" align="left" rowspan="2" width="15%"><b>Student Name</b></td>';
                      
    $tableTest = "";
    for($i=0; $i<count($subjectArray); $i++) {

        $subjectCode = $subjectArray[$i]['subjectCode'];
        $colspan='';
        $maxExternalMarksHeader=$externalMarksArray[$i]['externalTotalMarks'];
	$maxInternalMarksHeader=$internalMarksArray[$i]['internalTotalMarks'];
        $showGraceMarks = trim($REQUEST_DATA['showGraceMarks']);
        $showGrades = trim($REQUEST_DATA['showGrades']);

		if($marksFor=='0' && $reportFor=='2') {
           $val='4'; 
           $tableTest .='<td width="2%" align="center" valign="middle"><b>I'."(".$maxInternalMarksHeader.")".'</b></td>';
           if($showGraceMarks=='1') {
              $tableTest .='<td width="2%" align="center" valign="middle"><b>GR</b></td>';  
              $val++;
           }
           $tableTest .='<td width="2%" align="center" valign="middle"><b>E'."(".$maxExternalMarksHeader.")".'</b></td>';
                         
           if($showGrades=='1') {
              $tableTest .='<td width="2%" align="center" valign="middle"><b>GD</b></td>';
              $val++;
           }$totalMaxMarks=$maxInternalMarksHeader+$maxExternalMarksHeader;
           $tableTest .='<td width="2%" align="center" valign="middle"><b>T'."(".$totalMaxMarks.")".'</b></td>
                         <td width="2%" align="center" valign="middle"><b>%age</b></td>';
           $colspan="colspan='$val'";              
        }
        else {
            $val='1';
            if($marksFor=='1' && $reportFor=='2') {   
                $tableTest .='<td width="2%" align="center" valign="middle"><b>I'."(".$maxInternalMarksHeader.")".'</b></td>';
                if($showGraceMarks=='1') {
		           $val++;
                   $tableTest .='<td width="2%" align="center" valign="middle"><b>GR</b></td>'; 
                   $val++;
		           $tableTest .='<td width="2%" align="center" valign="middle"><b>T'."(".$maxInternalMarksHeader.")".'</b></td>';
                }              
            }
            if($marksFor=='2' && $reportFor=='2') {    
                $val='1';
                $tableTest .='<td width="2%" align="center" valign="middle"><b>E'."(".$maxExternalMarksHeader.")".'</b></td>';
                if($showGrades=='1') {
                  $tableTest .='<td width="2%" align="center" valign="middle"><b>GD</b></td>';
                  $val++;
                }              
            }
            if($reportFor=='1') {     
                $tableTest .='<td width="10%" align="center" valign="middle"><b>A</b></td>';
            }
            $colspan="colspan='$val'";     
            if($val=='1') {
              $colspan='';
            }
        }
        $tableData .= '<td width="20%" '.$colspan.' align="center" valign="middle"><b>'.$subjectCode.'</b></td>'; 
    }
    $tableData .= '</tr>';
    $tableData .= '<tr class="rowheading">'.$tableTest.'</tr>';
    
    $resultValueArray = array();
    for($i=0; $i<count($studentArray); $i++) {
        $srNo = ($i+1);
        $studentId = $studentArray[$i]['studentId'];
        $classId = $studentArray[$i]['classId'];
        $univRollNo=$studentArray[$i]['universityRollNo'];
        $studentName = $studentArray[$i]['studentName'];
        $rollNo = $studentArray[$i]['rollNo'];
        $bg = $bg =='row0' ? 'row1' : 'row0';
        $tableData .= '<tr class="'.$bg.'">
                         <td valign="middle">&nbsp;'.$srNo.'</td>
			             <td valign="middle">&nbsp;'.$univRollNo.'</td>
                         <td valign="middle">&nbsp;'.$rollNo.'</td>
                         <td valign="middle">&nbsp;'.$studentName.'</td>';

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
        $tableData .= getResult($studentId,$subjectId,$classId,$att,$intMks,$extMks,$showGraceMarks,$showGrades);
        $tableData .= '</tr>';
        $resultValueArray[] = $tableData;
        $tableData ="";
    }
    
    if(count($resultValueArray)>0) {
        for($i=0;$i<count($resultValueArray);$i++) {
           echo trim($resultValueArray[$i]); 
        }
        echo "!~~!~~!".$employeeList1;
    }
    else {
       echo "No Data Found"; 
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
		           $result .= '<td width="2%" align="center" valign="middle">'.NOT_APPLICABLE_STRING.'</td>';   
		       }
		       else {
		            /* $attended = $attendanceArray[$att]['attended'];
		             $delivered = $attendanceArray[$att]['delivered'];    
		             $leaveTaken = $attendanceArray[$att]['leaveTaken'];    
		             $medicalLeaveTaken = $attendanceArray[$att]['medicalLeaveTaken'];    */
                             $attended = $attendanceArray[$att]['marksScored'];
                             $delivered = $attendanceArray[$att]['maxMarks']; 
		             $attSubjectId = $attendanceArray[$att]['subjectId'];
		             $attStudentId = $attendanceArray[$att]['studentId'];
		             $showAttendance = $attended.'/'.$delivered; 
		             if($attSubjectId==$subjectId && $attStudentId == $studentId) {
		               $result .= '<td width="10%" align="center" valign="middle">'.$showAttendance.'</td>';   
		               $att++;
		             }
		             else  {
		               $result .='<td width="2%" align="center" valign="middle">'.NOT_APPLICABLE_STRING.'</td>';    
		             }
		       }
           }
           
 
           // Show Student Internal Marks
           if(($marksFor=='0' || $marksFor=='1') && $reportFor=='2' ) {    
                $graceMarks='';
                $marksScored='';
                $totalInternalMarks='';
                if($intMks=='-1') {
                 $result .= '<td width="2%" align="center" valign="middle">'.NOT_APPLICABLE_STRING.'</td>';   
                }
                else {
                    $internalSubjectId = $internalMarksArray[$intMks]['subjectId'];
                    $internalStudentId = $internalMarksArray[$intMks]['studentId'];
                    $marksScored = round($internalMarksArray[$intMks]['marksScored']);
                    $maxInternalMarks=$internalMarksArray[$intMks]['maxMarks'];
                      if($internalSubjectId==$subjectId && $internalStudentId == $studentId) {
                       $graceMarksArray = $studentConsolidatedReportManager->getGraceMarks($internalStudentId, $classId, $internalSubjectId);
                       $graceMarks=$graceMarksArray[0]['graceMarks'];               
                       if($graceMarks=='') {
                         $graceMarks='0';  
                       }
                       
                       $result .= '<td width="2%" align="center" valign="middle">'.$marksScored.'</td>';   
                       $total += $marksScored;
                       if($showGraceMarks=='1'){
                         $total +=$graceMarks;
                           }
                       $intMks++;
                    }
                    else{
                       $result .= '<td width="2%" align="center" valign="middle">'.NOT_APPLICABLE_STRING.'</td>';    
                    }
                }
                $extraColumn = '';  
                $totalInternalMarks = NOT_APPLICABLE_STRING;
                
                if($showGraceMarks=='1') {
		            if($graceMarks!=''){
	                   $totalInternalMarks=$marksScored+$graceMarks;
                       $extraColumn = '<td width="2%" align="center" valign="middle">'.$graceMarks.'</td>';                
                    }       
                    else{
		                $extraColumn = '<td width="2%" align="center" valign="middle">'.NOT_APPLICABLE_STRING.'</td>'; 
		            }
                    if($marksFor=='1'){
                      $extraColumn .= '<td width="2%" align="center" valign="middle">'.$totalInternalMarks.'</td>';                       }            
                } 
                $result .=$extraColumn;
           }
           
           $extraColumn='';
           // Show Student External Marks
           if(($marksFor=='0' || $marksFor=='2') && $reportFor=='2' ) {    
                if($extMks=='-1') {
                    $result .= '<td width="2%" align="center" valign="middle">'.NOT_APPLICABLE_STRING.'</td>';   
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
                        $result .= '<td width="2%" align="center" valign="middle">'.$finalMarks.'</td>';   
                        $total += $finalMarks;
                        $extMks++;
                    }
                    else  {
                        $result .= '<td width="2%" align="center" valign="middle">'.NOT_APPLICABLE_STRING.'</td>';    
                    }
                }
                
                if($showGrades=='1') {
		            if($grade!=''){
                      $extraColumn = '<td width="2%" align="center" valign="middle">'.$grade.'</td>';
                    }
                    else{
                      $extraColumn = '<td width="2%" align="center" valign="middle">'.NOT_APPLICABLE_STRING.'</td>'; 
		            }
                    $result .= $extraColumn; 
                }
           } 
           
           if($marksFor=='0' && $reportFor=='2' ) { 
              $result .= '<td width="2%" align="center" valign="middle">'.$total.'</td>'; 
              $percentage=round($total/($maxInternalMarks+$maxMarksByAdmin)*100,2);
              $result .= '<td width="2%" align="center" valign="middle">'.$percentage.'</td>';  
           }
      }  
     
     return $result;  
}
?>
