<?php
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','ClassWiseEvaluationReport');
    define('ACCESS','view');
    define('MANAGEMENT_ACCESS',1);
    UtilityManager::ifNotLoggedIn(true);

    require_once(MODEL_PATH . "/ClassWiseEvaluationReportManager.inc.php");
    $classWiseEvaluationReportManager = ClassWiseEvaluationReportManager::getInstance();


   $timeTableLabelId = $REQUEST_DATA['timeTable'];
   $classId= $REQUEST_DATA['classId'];

   // Fetch Subject List 
   $subjectIds =0;                                 
   $conditions = " AND c.classId=$classId ";
   $orderSubjectBy = " classId,  subjectTypeId, subjectCode ";
   
   $recordArray =  $classWiseEvaluationReportManager->getAllSubjectAndSubjectTypes($conditions, $orderSubjectBy );   
   for($i=0;$i<count($recordArray);$i++) {
      $subjectIds .=",".$recordArray[$i]['subjectId']; 
   }
  
   // Fetch Employee and Group List 
   $conditions = " AND tt.subjectId IN ($subjectIds) AND  g.classId=$classId AND tt.timeTableLabelId=$timeTableLabelId";
   $orderBy=' classId, subjectTypeId, subjectId, employeeName, groupName';
   $employeeGroupArray = $classWiseEvaluationReportManager->getEmployeeGroupList($conditions, $orderBy);
   
   $tableData = getTableHead();
   $tableData .= getTableRow();

   echo $tableData.'!~~!'.count($recordArray);  
   
die;

   
function getTableHead() {
    
    $tableData = "<table width='100%' border='0' cellspacing='1px' cellpadding='2px' class='' align='center'> 
                    <tr class='rowheading'>
                      <td width='3%'  rowspan='2' align='left' class='searchhead_text'><b>#</b></td>
                      <td width='15%' rowspan='2' align='left' class='searchhead_text'><strong>Subject Code</strong></td>
                      <td width='20%' rowspan='2' align='left' class='searchhead_text'><strong>Subject</strong></td>
                      <td width='22%' rowspan='2' align='left' class='searchhead_text'><strong>Employee</strong></td>
                      <td width='40%' colspan='4' align='center' class='searchhead_text'><strong>Student</strong></td>
                    </tr>
                    <tr class='rowheading'>
                      <td width='25%' align='center' class='searchhead_text'><strong>Total</strong></td>
                      <td width='25%' align='center' class='searchhead_text'><strong>Appeared</strong></td>
                      <td width='25%' align='center' class='searchhead_text'><strong>Passed</strong></td>
                      <td width='25%' align='center' class='searchhead_text'><strong>%age</strong></td>
                    </tr>"; 
                    
     return $tableData;                    
}

function getTableRow() {
    
    global $recordArray;
    global $employeeGroupArray;
    
    $tableData = '';
    
    if(count($recordArray)>0){
       $j=0;                                                         
       for($i=0;$i<count($recordArray);$i++) {
          $bg = $bg =='trow0' ? 'trow1' : 'trow0';
          $subjectId = $recordArray[$i]['subjectId'];
          $classId = $recordArray[$i]['classId'];
          
          $tableData.= "<tr class='$bg'>
                          <td valign='top' class='padding_top' align='left'>".($i+1)."</nobr></td> 
                          <td valign='top' class='padding_top' align='left'>".$recordArray[$i]['subjectCode']."</td>
                          <td valign='top' class='padding_top' align='left'>".$recordArray[$i]['subjectName']."</td>";
                          
          $findData = '';
          $find=0;
          $lineBr ="";
          $bg1 =$bg;   
          for($j=0;$j<count($employeeGroupArray);$j++) {
             $tsubjectId = $employeeGroupArray[$j]['subjectId'];
             $tclassId = $employeeGroupArray[$j]['classId']; 
             $groupName = $employeeGroupArray[$j]['groupName']; 
             $employeeName = $employeeGroupArray[$j]['employeeName']; 
             if($tsubjectId==$subjectId && $tclassId == $classId) {
               $groupArray = explode(',',$groupName);  
               $findData .= "<table width='100%' border='0' cellspacing='1px' cellpadding='2px' class='' align='center'> 
                                <tr>
                                    <td valign='top' colspan='2' class='padding_top' align='left'><b>$employeeName</b></td>
                                 </tr>";
                for($k=0;$k<count($groupArray);$k++) {
                  $bg1 = $bg1 =='trow0' ? 'trow1' : 'trow0';   
                  $findData .= "<tr class='$bg1'> 
                                    <td width='2%'  nowrap valign='top' class='padding_top' style='padding-left:10px' align='left'><b>*</b></td>
                                    <td width='98%' nowrap valign='top' class='padding_top' style='padding-left:5px' align='left'>".$groupArray[$k]."</td>
                                </tr>";     
                }                   
                $findData .= "</table>";  
               
               $find=1; 
             }
             else if($find==1) {
               break;  
             }
          } // End for employee and group loop
          
          if($findData == '') {
            $findData = NOT_APPLICABLE_STRING;  
          }
          $tableData .= "<td width='98%' class='dataFont'>".$findData."</td>
                         <td width='98%' class='dataFont'>".NOT_APPLICABLE_STRING."</td>
                         <td width='98%' class='dataFont'>".NOT_APPLICABLE_STRING."</td>
                         <td width='98%' class='dataFont'>".NOT_APPLICABLE_STRING."</td>
                         <td width='98%' class='dataFont'>".NOT_APPLICABLE_STRING."</td>
                        </tr>";
       } // End for subject loop
       $tableData .= "</table>";
   }
   else {
        $bg = $bg =='trow0' ? 'trow1' : 'trow0';
        $tableData.= "<tr class='$bg'>
                          <td valign='top' class='padding_top' align='center'>No Data Found</nobr></td> 
                        </tr>";  
   }
   
   return $tableData;
}
       
?>
