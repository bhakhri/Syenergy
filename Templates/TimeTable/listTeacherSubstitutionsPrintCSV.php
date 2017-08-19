 <?php 
//This file is used as printing version for Teacher Substitutions Report CSV.
//
// Author :Parveen Sharma
// Created on : 13-Aug-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
    set_time_limit(0); //to overcome the time taken for fetching Teacher Substitutions Report   
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/TimeTableManager.inc.php");
    require_once(MODEL_PATH . "/StudentManager.inc.php");      
   
    global $sessionHandler;  
    
    $timetableManager  = TimeTableManager::getInstance();
    $studentManager = StudentManager::getInstance();  
    
    define('MODULE','TeacherSubstitutions');
    define('ACCESS','view');

    UtilityManager::ifNotLoggedIn();
   
    $timeTableLabelId = add_slashes($REQUEST_DATA['labelId']);
    $timeTableType = add_slashes($REQUEST_DATA['timeTableType']); 
    $fromDate = add_slashes($REQUEST_DATA['fromDate']); 
    $employeeId = add_slashes($REQUEST_DATA['employeeId']); 
    $daysOfWeek= add_slashes($REQUEST_DATA['daysOfWeek']);  
    $periodId=   add_slashes($REQUEST_DATA['periodId']);
    $subjectId=  add_slashes($REQUEST_DATA['subjectId']);
    $instituteId = $sessionHandler->getSessionVariable('InstituteId');     
    

    
    //--------------------------------------------------------       
    //Purpose:To escape any newline or comma present in data
    //--------------------------------------------------------   
    function parseCSVComments($comments) {
         $comments = str_replace('"', '""', $comments);
         if(eregi(",", $comments) or eregi("\n", $comments)) {
            return '"'.$comments.'"'; 
         } 
         else {
            return $comments.chr(160); 
         }
    }

    
    /// Search filter /////                          
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'employeeName1';
    
    $orderBy = " ORDER BY $sortField $sortOrderBy ";  
    
    if($periodId != '') {
       $freePeriodList = explode(",",$periodId);  
    }
    
    if($timeTableType==1) {              // Weekly 
       if($daysOfWeek != '')   
          $conditions .= " AND tt.daysOfWeek = '$daysOfWeek' ";
    }
    else if($timeTableType==2) {         // Daily
       $conditions .= " AND tt.fromDate = '$fromDate' ";
    }
   
    if($employeeId != '')   
      $conditions .= " AND tt.employeeId != $employeeId ";
    
    $cond = $conditions;

    $temp=0;   
    if($subjectId != '') {
      $cond .= " AND tt.subjectId IN ($subjectId) ";   
      $temp=1;
    }
    
    
    $where     = " WHERE p1.periodSlotId = ps1.periodSlotId AND ps1.instituteId = $instituteId  GROUP BY p1.periodSlotId ";
    $filedName = " p1.periodSlotId, 
                   GROUP_CONCAT(DISTINCT p1.periodNumber ORDER BY p1.periodSlotId, p1.periodNumber ASC SEPARATOR ',') AS periodNumber,
                   GROUP_CONCAT(DISTINCT p1.periodId     ORDER BY p1.periodSlotId, p1.periodNumber ASC SEPARATOR ',') AS periodIds,
                   GROUP_CONCAT(DISTINCT ps1.slotName ORDER BY p1.periodSlotId ASC SEPARATOR ',') AS slotName,
                   GROUP_CONCAT(DISTINCT ps1.slotAbbr ORDER BY p1.periodSlotId ASC SEPARATOR ',') AS slotAbbr ";
    $tableName = " period p1, period_slot ps1  ";
    $periodsStatus = $studentManager->getSingleField($tableName, $filedName, $where);
    
    $cond .= " AND tt.timeTableLabelId= '".$timeTableLabelId."'"; 
    $countRecordArray = $timetableManager->getTeacherSubstitutions($cond); 
    $total = count($countRecordArray);
    
    if($temp==1) {
      $employeeIds = 0;  
      if($total > 0) {
         for($j=0; $j<$total; $j++) {
           $employeeIds .= ','.$countRecordArray[$j]['employeeId']; 
         }
      }
      $conditions .= " AND tt.employeeId IN ($employeeIds)";
    }
    
    //$recordArray = $timetableManager->getTeacherSubstitutions($conditions, $orderBy, $limit); 
    $conditions .= " AND tt.timeTableLabelId= '".$timeTableLabelId."'";                    
    $recordArray = $timetableManager->getTeacherSubstitutions($conditions, $orderBy); 
    $cnt = count($recordArray);   
    
    
    // function remove extra commas 
    function check($str) {
        $str=trim($str);
        if(empty($str)) {
            $str =  NOT_APPLICABLE_STRING;
            return $str;
        }
        else {
            if(substr($str,-1) == ',') {
                $str = substr($str,0,-1);
            }
            if(substr($str,0,1) == ',') {
              $str = substr($str,1,-1);
            }
            return $str;  
        } 
    }   
    
    
    global $daysArr;  
   
    $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);
    
    $where = " WHERE employeeId = $employeeId";
    $filedName  = "CONCAT(employeeName,' (',employeeCode,')') AS employeeName";
    $tableName   = " employee ";
    $employeeArray = $studentManager->getSingleField($tableName, $filedName, $where);
    $employeeName = $employeeArray[0]['employeeName'];
    
   
    $where      = "WHERE p1.periodId IN ($periodId) ";
    $filedName  = "GROUP_CONCAT(DISTINCT p1.periodNumber ORDER BY p1.periodSlotId, p1.periodNumber ASC SEPARATOR ', ') AS periodNumber";
    $tableName   = " period p1";
    $periodsFound = $studentManager->getSingleField($tableName, $filedName, $where);
    $periodsName1 = $periodsFound[0]['periodNumber'];
   
    $csvData = '';
    $csvData .= "For,".parseCSVComments($employeeName);
    $csvData .= "\nDays,".parseCSVComments($daysArr[$daysOfWeek]);
    $csvData .= "\nPeriods,".parseCSVComments($periodsName1);   
    $csvData .= "\nAs on,".parseCSVComments($formattedDate);
    $csvData .= "\n";   
    
    $csvData .= "Sr No.,Teacher Name,Contact Number,Subjects,Teaching Periods,Free Periods\n";
   
    $found = array();  
    $total=0;
    
    for($i=0;$i<$cnt;$i++) {      

       $periodArr = array(); 
       $periodArr1 = array();  
        
       $periodArr = explode(",",trim($recordArray[$i]['periodNumber']));
       $pp = implode(",",$periodArr);
       $cntPeriod = count($periodArr);
       
       for($j=0;$j<count($periodsStatus); $j++) {
          if($periodsStatus[$j]['periodSlotId']==$recordArray[$i]['periodSlotId']) {
             $periodArr1 = explode(",",trim($periodsStatus[$j]['periodNumber']));
             $pp1 = implode(",",$periodArr1);
             break;
          }
       }
       
       $periodFree = "";
       $k=0;
       for($j=0;$j<count($periodArr1); $j++) {
          if($periodArr[$k]!='' && $k < $cntPeriod ) { 
            if($periodArr1[$j]!=$periodArr[$k]) {  
              $periodFree .= $periodArr1[$j].',';  
            }
            else {
               $k++; 
            }
          }
          else {
             $periodFree .= $periodArr1[$j].',';  
          }
       }       
       
       if($pp == "") {
         $pp = NOT_APPLICABLE_STRING;  
       }
       
       if($periodFree == "") {
         $periodFree = $pp1;  
       }
       
       if($periodFree == "") {              
           $periodFree = NOT_APPLICABLE_STRING;  
       }
      
    
       // Find Free Period Check
       if($periodFree!=NOT_APPLICABLE_STRING) { 
           $periodList1 = array(); 
           $periodList1 = explode(",",$periodFree); 
           
           $temp1=0;
           $periodFree1 = "";
           for($k=0; $k<count($periodList1); $k++) {
              $ch=0; 
              for($j=0;$j<count($freePeriodList); $j++) {
                 if($freePeriodList[$j]==$periodList1[$k]) {
                   if($periodFree1=="") {  
                     $periodFree1 = $periodList1[$k];   
                   }
                   else {
                     $periodFree1 .= ", ".$periodList1[$k];   
                   }
                   $temp1=1;
                   $ch=1;
                   break;
                 } 
              }
              if($ch==0) {
                 if($periodFree1=="") {  
                   $periodFree1 = $periodList1[$k];   
                 }
                 else {
                   $periodFree1 .= ", ".$periodList1[$k];   
                 }
              }
           } 
       } 
       
       if($periodFree1 == "") {              
          $periodFree1 = NOT_APPLICABLE_STRING;  
       }
    
       if($temp1==1) {
         $found[$total]['srNo'] =  $total+1;  
         $found[$total]['employeeName']  = $recordArray[$i]['employeeName'];
         $found[$total]['employeeName1']  = $recordArray[$i]['employeeName1']; 
         $found[$total]['contactNumber'] = check($recordArray[$i]['contactNumber']);
         $found[$total]['subjectName']   = $recordArray[$i]['subjectName'];
         $found[$total]['periodNumber']  = $pp;
         $found[$total]['periodFree']    = check($periodFree1);
         $csvData .=  ($total+1).",".parseCSVComments($recordArray[$i]['employeeName1']).",".parseCSVComments(check($recordArray[$i]['contactNumber'])).",";
         $csvData .=  parseCSVComments(check($recordArray[$i]['subjectName'])).",".parseCSVComments($pp).",".parseCSVComments(check($periodFree1))."\n";
         $total=$total+1;
       } 
    }
    
    ob_end_clean();
    header("Cache-Control: public, must-revalidate");
    // We'll be outputting a PDF
    header('Content-type: application/octet-stream');
    header("Content-Length: " .strlen($csvData) );
    // It will be called downloaded.pdf
    header('Content-Disposition: attachment;  filename="TeacherSubstitutionReport.csv"');
    // The PDF source is in original.pdf
    header("Content-Transfer-Encoding: binary\n");
    echo $csvData;
    die; 

    
  
//$History : listTeacherSubstitutionsPrintCSV.php $
//

?>