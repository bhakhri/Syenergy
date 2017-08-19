<?php
//-------------------------------------------------------------------------------------------------------------- 
// Purpose: Teacher Substitutions Report
// functionality
//
// Author : Parveen Sharma
// Created on : (18.02.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------------- 

 set_time_limit(0); //to overcome the time taken for fetching Teacher Substitutions Report
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/TimeTableManager.inc.php");
    require_once(MODEL_PATH . "/StudentManager.inc.php");      

    define('MODULE','TeacherSubstitutions');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
   
    global $sessionHandler;  
    
    $timetableManager  = TimeTableManager::getInstance();
    $studentManager = StudentManager::getInstance();   
     
    $timeTableLabelId = add_slashes($REQUEST_DATA['labelId']);
    $timeTableType = add_slashes($REQUEST_DATA['timeTableType']); 
    $fromDate = add_slashes($REQUEST_DATA['fromDate']); 
    $employeeId = add_slashes($REQUEST_DATA['employeeId']); 
    $daysOfWeek= add_slashes($REQUEST_DATA['daysOfWeek']);  
    $periodId=   add_slashes($REQUEST_DATA['periodId']);
    $subjectId=  add_slashes($REQUEST_DATA['subjectId']);
    $instituteId = $sessionHandler->getSessionVariable('InstituteId');    

    
    
    /////////////////////////
    // to limit records per page    
    $page = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records = ($page-1)* RECORDS_PER_PAGE;
    $limit   = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////

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
    

    $i=$records;  
    $j=0;  
    if($total==0) {
       $json_val="";
    }
    else {
      while($j<RECORDS_PER_PAGE) {
        if(trim($json_val)=='') {
          $json_val = json_encode($found[$i]);
        }
        else {
          $json_val .= ','.json_encode($found[$i]);           
        }
        $i++;
        if(($total)==$i)
          break;
        $j++;
      }
    }

    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$total.'","page":"'.$page.'","info" : ['.$json_val.']}'; 


// $History: ajaxTeacherSubstitutions.php $
//
//*****************  Version 10  *****************
//User: Parveen      Date: 3/12/10    Time: 1:02p
//Updated in $/LeapCC/Library/TimeTable
//query format udpated
//
//*****************  Version 9  *****************
//User: Parveen      Date: 2/12/10    Time: 2:17p
//Updated in $/LeapCC/Library/TimeTable
//sortin order updated (employeeName1)
//
//*****************  Version 8  *****************
//User: Parveen      Date: 2/12/10    Time: 2:11p
//Updated in $/LeapCC/Library/TimeTable
//time Table label added (validation format updated)
//
//*****************  Version 7  *****************
//User: Parveen      Date: 1/27/10    Time: 12:12p
//Updated in $/LeapCC/Library/TimeTable
//set_time_limit added 
//
//*****************  Version 6  *****************
//User: Parveen      Date: 1/27/10    Time: 11:22a
//Updated in $/LeapCC/Library/TimeTable
//format updated free period view
//
//*****************  Version 5  *****************
//User: Parveen      Date: 1/25/10    Time: 12:10p
//Updated in $/LeapCC/Library/TimeTable
//format and validation updated
//
//*****************  Version 4  *****************
//User: Parveen      Date: 1/23/10    Time: 6:13p
//Updated in $/LeapCC/Library/TimeTable
//validation & condition updated (free period show)
//
//*****************  Version 3  *****************
//User: Parveen      Date: 9/18/09    Time: 5:34p
//Updated in $/LeapCC/Library/TimeTable
//instituteId check added
//
//*****************  Version 2  *****************
//User: Parveen      Date: 3/31/09    Time: 1:17p
//Updated in $/LeapCC/Library/TimeTable
//paging formatting setting
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/20/09    Time: 4:01p
//Created in $/LeapCC/Library/TimeTable
//initial checkin
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/20/09    Time: 3:32p
//Created in $/SnS/Library/TimeTable
//initial checkin
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/20/09    Time: 11:44a
//Created in $/Leap/Source/Library/ScTimeTable
//initial checkin
//
//*****************  Version 2  *****************
//User: Parveen      Date: 2/18/09    Time: 3:20p
//Updated in $/SnS/Library/EmployeeReports
//rights set
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/18/09    Time: 3:15p
//Created in $/SnS/Library/EmployeeReports
//initial checkin
//
//*****************  Version 5  *****************
//User: Parveen      Date: 2/17/09    Time: 6:26p
//Updated in $/Leap/Source/Library/ScTimeTable
// time table label id update
//
//*****************  Version 4  *****************
//User: Parveen      Date: 2/17/09    Time: 2:28p
//Updated in $/Leap/Source/Library/ScTimeTable
//query update
//
//*****************  Version 3  *****************
//User: Parveen      Date: 2/17/09    Time: 12:48p
//Updated in $/Leap/Source/Library/ScTimeTable
//rights set
//
//*****************  Version 2  *****************
//User: Parveen      Date: 2/17/09    Time: 12:43p
//Updated in $/Leap/Source/Library/ScTimeTable
//initial checkin
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/17/09    Time: 12:00p
//Created in $/Leap/Source/Library/ScTimeTable
//initial checkin
//

?>
