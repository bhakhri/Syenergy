 <?php 
//This file is used as printing version for Teacher Substitutions Report.  
//
// Author :Parveen Sharma
// Created on : 13-Aug-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
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
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();
   
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
   
    /// Search filter /////                          
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'employeeName1';
    
    $orderBy = " ORDER BY $sortField $sortOrderBy ";  
    
    
    if($employeeId=='') {
      $employeeId=0;  
    }
    
    
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
    
    
    $found = array();  
    $total=0;
    for($i=0;$i<$cnt;$i++) {      
       $periodArr = array(); 
       $periodArr1 = array();  
       
       $periodArrNumber = explode(",",trim($recordArray[$i]['periodNumber'])); 
       $periodArr       = explode(",",trim($recordArray[$i]['periodId']));
       $pp = implode(",",$periodArr);
       $ppNumber = implode(",",$periodArrNumber);
       $cntPeriod = count($periodArr);

       for($j=0;$j<count($periodsStatus); $j++) {
          if($periodsStatus[$j]['periodSlotId']==$recordArray[$i]['periodSlotId']) {
             $periodArr1Number = explode(",",trim($periodsStatus[$j]['periodNumber']));
             $periodArr1 = explode(",",trim($periodsStatus[$j]['periodIds']));
             $pp1 = implode(",",$periodArr1);
             $pp1Number = implode(",",$periodArr1Number);
             break;
          }
       }
       
       $periodFree       = "";
       $periodFreeNumber = "";   
       $k=0;
       for($j=0;$j<count($periodArr1); $j++) {
          if($periodArr[$k]!='' && $k < $cntPeriod ) { 
            if($periodArr1[$j]!=$periodArr[$k]) {  
              $periodFree .= $periodArr1[$j].','; 
              $periodFreeNumber .= $periodArr1Number[$j].','; 
            }
            else {
               $k++; 
            }
          }
          else {
             $periodFree .= $periodArr1[$j].',';  
             $periodFreeNumber .= $periodArr1Number[$j].',';  
          }
       }       
       
       if($pp == "") {
         $pp = NOT_APPLICABLE_STRING;  
         $ppNumber = NOT_APPLICABLE_STRING;  
       }
       
       if($periodFree == "") {
         $periodFree = $pp1;  
         $periodFreeNumber = $pp1Number; 
       }
       
       if($periodFree == "") {              
           $periodFree = NOT_APPLICABLE_STRING;  
           $periodFreeNumber = NOT_APPLICABLE_STRING;  
       }
      
    
       // Find Free Period Check
       if($periodFree!=NOT_APPLICABLE_STRING) { 
           $periodList1 = array(); 
           $periodList1 = explode(",",$periodFree); 
           
           $periodList1Number = array(); 
           $periodList1Number = explode(",",$periodFreeNumber); 
           
           $temp1=0;
           $periodFree1 = "";
           $periodFree1Number = "";
           for($k=0; $k<count($periodList1); $k++) {
              $ch=0; 
              for($j=0;$j<count($freePeriodList); $j++) {
                 if($freePeriodList[$j]==$periodList1[$k]) {
                   if($periodFree1=="") {  
                     $periodFree1 = "<span style=color:black;><b><u>".$periodList1[$k]."</b></u></span>";   
                     $periodFree1Number = "<span style=color:black;><b><u>".$periodList1Number[$k]."</b></u></span>";  
                   }
                   else {
                     $periodFree1 .= ", <span style=color:black;><b><u>".$periodList1[$k]."</b></u></span>";   
                     $periodFree1Number .= ", <span style=color:black;><b><u>".$periodList1Number[$k]."</b></u></span>";
                   }
                   $temp1=1;
                   $ch=1;
                   break;
                 } 
              }
              if($ch==0) {
                 if($periodFree1=="") {  
                   $periodFree1 = $periodList1[$k];   
                   $periodFree1Number  = $periodList1Number[$k];    
                 }
                 else {
                   $periodFree1 .= ", ".$periodList1[$k];   
                   $periodFree1Number  .= ", ".$periodList1Number[$k];    
                 }
              }
           } 
       } 
       
       if($periodFree1 == "") {              
          $periodFree1 = NOT_APPLICABLE_STRING; 
          $periodFree1Number  = NOT_APPLICABLE_STRING; 
       }
    
        
       if($temp1==1) {
         $found[$total]['srNo'] =  $total+1;  
         $found[$total]['employeeName']  = $recordArray[$i]['employeeName'];
         $found[$total]['employeeName1']  = $recordArray[$i]['employeeName1'];    
         $found[$total]['contactNumber'] = check($recordArray[$i]['contactNumber']);
         $found[$total]['subjectName']   = $recordArray[$i]['subjectName'];
         $found[$total]['periodNumber']  = "<span style=font-size:14px;>".$ppNumber."</span>";
         $found[$total]['periodFree']    = "<span style=font-size:14px;>".check($periodFree1Number)."</span>";
         $total=$total+1;
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
   
    $headValue = "For ".$employeeName;
    $headValue .= "<br>Days: ".$daysArr[$daysOfWeek];
    $headValue .= ", Periods: ".$periodsName1;   
    $headValue .= "<br>As on $formattedDate ";
    
    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Teacher Substitutions Report');
    $reportManager->setReportInformation($headValue);

    $reportTableHead                        =    array();
                    //associated key                  col.label,            col. width,      data align        
    $reportTableHead['srNo']                =    array('#',                 'width="2%"   align="left" ',"align='left'");
    $reportTableHead['employeeName1']       =    array('Teacher Name ',     'width="18%"  align="left" ','align="left" ');
    $reportTableHead['contactNumber']       =    array("Contact Number",    'width="15%"  align="left" ','align="left"');
    $reportTableHead['subjectName']         =    array('Subject',           'width="25%"  align="left" ','align="left"');
    $reportTableHead['periodNumber']        =    array('Teaching Periods',  'width="20%"  align="left" ','align="left"');
    $reportTableHead['periodFree']          =    array('Free Periods',      'width="20%"  align="left" ','align="left"');    
    
    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $found);
    $reportManager->showReport(); 

    
    
//$History : listTeacherSubstitutionsPrint.php $
//
?>
