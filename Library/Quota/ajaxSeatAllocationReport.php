<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE QUOTA Seats LIST
//
// Author : Parveen Sharma
// Created on : (12.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/QuotaManager.inc.php");
define('MODULE','SeatAllocationReport');
define('ACCESS','view');
define("MANAGEMENT_ACCESS",1);
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

    
    $allocationDate = $REQUEST_DATA['allocationDate'];
    $classId = trim($REQUEST_DATA['classId']);
    $quotaId = trim($REQUEST_DATA['quotaId']);
    $roundId = trim($REQUEST_DATA['roundId']);
    $alotSeat= trim($REQUEST_DATA['alotSeat']);
    $rptSeat = trim($REQUEST_DATA['rptSeat']);
    $vcnSeat = trim($REQUEST_DATA['vcnSeat']);
    
    
    // Search filter /////  
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'branchName';
    $orderBy = " $sortField $sortOrderBy";
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
   
    global $sessionHandler; 
    
    if($classId=='') {
      $classId = -1; 
    }
    
    if($quotaId=='') {
      $quotaId = -1; 
    }
    
    if($roundId=='') {
      $roundId = -1; 
    }
    
    if($alotSeat=='') {
      $alotSeat=0;  
    }
    
    if($rptSeat=='') {
      $rptSeat=0;  
    }
    
    if($vcnSeat=='') {
      $vcnSeat=0;  
    }
    
    $sessionHandler->setSessionVariable('AllocationDate',$allocationDate);
    $sessionHandler->setSessionVariable('AllocationClassId',$classId);
    $sessionHandler->setSessionVariable('AllocationQuotaId',$quotaId); 
    $sessionHandler->setSessionVariable('AllocationRoundId',$roundId); 
   
    
    $conditionDate = " AND cc1.allocationDate <= '$allocationDate' ";
   
    
    $condition = '';
    $conditionMain = '';
    
    if($classId!='-1') {
      $condition .= " AND cc1.classId IN ($classId) ";
      $conditionMain .= " AND qs.classId IN ($classId) ";
    }
    
    if($quotaId!='-1') {
      $condition .= " AND cc2.quotaId IN ($quotaId) ";
      $conditionMain .= " AND qs.quotaId IN ($quotaId) ";  
    }
    
    if($roundId!='-1') {
       $condition .= " AND cc1.roundId IN ($roundId) ";
       $conditionMain .= " AND cc1.roundId IN ($roundId) ";  
    }
    
    $foundArray = QuotaManager::getInstance()->getClassQuotaAllocationList($condition,$conditionDate,$allocationDate,$conditionMain,$orderBy); 
    $totalRecord = count($foundArray);
    
    $foundArray = QuotaManager::getInstance()->getClassQuotaAllocationList($condition,$conditionDate,$allocationDate,$conditionMain,$orderBy,$limit); 
    $cnt = count($foundArray);
  
    $tableHead = "<table width='100%' border='0' cellspacing='2' cellpadding='0'>
                    <tr class='rowheading'> 
                      <td width='2%'   style='padding-left:2px'  class='searchhead_text'  ><b>#</b></td>
                      <td width='20%'  style='padding-left:2px'  class='searchhead_text'  align='left'><strong>Course Name</strong></td>
                      <td width='25%'  style='padding-left:2px'  class='searchhead_text'  align='left'><strong>Seat Type</strong></td>
                      <td width='10%'  style='padding-right:2px' class='searchhead_text'  align='right'><strong>Total Seats</strong></td>";
    if($alotSeat!='0') {
      $tableHead .= "<td width='12%'  style='padding-right:2px' class='searchhead_text'  align='right'><strong>Allotted Seats</strong></td>";
    }
    
    if($rptSeat!='0') {
      $tableHead .= "<td width='14%'  style='padding-right:2px' class='searchhead_text'  align='right'><strong>Reported Seats</strong></td>";
    }
    
    if($vcnSeat!='0') {
       $tableHead .= "<td width='12%'  style='padding-right:2px' class='searchhead_text'  align='right'><strong>Vacant Seats</strong></td>";
    }                                  
    $tableHead .= "</tr>";

    $j=0;
    $find=0;
    $tableData = "";
    $branchName='';
    for($i=0;$i<$cnt;$i++) {
        $bg = $bg =='trow0' ? 'trow1' : 'trow0';           
        if($tableData == "") {
             $tableData = $tableHead;  
        }
        $tbranchName = $foundArray[$i]['branchName'];
        if($foundArray[$i]['branchName']==$branchName) {
            $tbranchName = ""; 
        }
        else {
             $j=0;
             if($i!=0) {
               $tableData .= getTotalSeatsValue($totalSeat);   
             }
             $totalSeat['totalSeats']=0;
             $totalSeat['allotedSeats']=0;
             $totalSeat['reportedSeats']=0;
             $totalSeat['vacantSeats'] =0;
        }
        $tableData .= getRecordValue($tbranchName,$foundArray[$i],$recordStart+$j); 
        $find=1;   
        $branchName = $foundArray[$i]['branchName'];          
        $j++;
    }
      
    if($find==0) {
      $totalRecord = 0;  
      $tableHead .= "<tr><td colspan='8'><center>No Data Found</center></td></tr></table>".'!~~!'.$totalRecord;
      echo $tableHead.'!~~!'.$totalRecord;  
      die;
    }
    else {
      $tableData .= getTotalSeatsValue($totalSeat);    
      $tableData .= "</table>";      
      echo $tableData.'!~~!'.$totalRecord;
      die;
    }
    
    die;
    
   function getRecordValue($tbranchName,$foundArray,$j) {
       global $reportManager;  
       global $totalSeat;  
       global $bg;  
       global $alotSeat;
       global $rptSeat;
       global $vcnSeat;
        
       
       $vacantSeats = $foundArray['totalSeats']-$foundArray['reportedSeats'];
       $result = "<tr class='$bg'>  
                         <td width='2%'   style='padding-left:2px'  class='padding_top' >".($records+$j+1)."</td>
                         <td width='20%'  style='padding-left:2px'  class='padding_top' align='left'>".strip_slashes($tbranchName)."</td>
                         <td width='25%'  style='padding-left:2px'  class='padding_top' align='left'>".strip_slashes($foundArray['quotaName'])."</td>
                         <td width='10%'  style='padding-right:2px' class='padding_top' align='right'>".strip_slashes($foundArray['totalSeats'])."</td>";
       if($alotSeat!='0') {
         $result .= "<td width='12%'  style='padding-right:2px' class='padding_top' align='right'>".strip_slashes($foundArray['allotedSeats'])."</td>";
       }
       if($rptSeat!='0') {
         $result .= "<td width='14%'  style='padding-right:2px' class='padding_top' align='right'>".strip_slashes($foundArray['reportedSeats'])."</td>";
       }
       if($vcnSeat!='0') {
         $result .= "<td width='12%'  style='padding-right:2px' class='padding_top' align='right'>".strip_slashes($vacantSeats)."</td> ";
       }  
       $result .= "</tr>";
        
       $totalSeat['totalSeats'] += $foundArray['totalSeats'];
       $totalSeat['allotedSeats'] += $foundArray['allotedSeats'];
       $totalSeat['reportedSeats'] += $foundArray['reportedSeats'];
       $totalSeat['vacantSeats']  += $vacantSeats;      
        
       return $result;              
    }
    
    
    function getTotalSeatsValue($totalSeat) {
       global $alotSeat;
       global $rptSeat;
       global $vcnSeat;
       
       $result = "<tr>
                         <td colspan='3'  style='padding-right:2px'  class='padding_top' align='right'><b>Total</b></td>
                         <td width='10%'  style='padding-right:2px' class='padding_top' align='right'>".strip_slashes($totalSeat['totalSeats'])."</td>";
       if($alotSeat!='0') {
         $result .= "<td width='12%'  style='padding-right:2px' class='padding_top' align='right'>".strip_slashes($totalSeat['allotedSeats'])."</td>";
       }
       if($rptSeat!='0') {
         $result .= "<td width='14%'  style='padding-right:2px' class='padding_top' align='right'>".strip_slashes($totalSeat['reportedSeats'])."</td>";
       }
       if($vcnSeat!='0') {
         $result .= "<td width='12%'  style='padding-right:2px' class='padding_top' align='right'>".strip_slashes($totalSeat['vacantSeats'])."</td> ";
       }   
       $result .= "</tr>";   
       return $result;              
    }

    
?>