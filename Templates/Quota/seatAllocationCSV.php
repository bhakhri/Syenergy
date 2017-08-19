<?php 
//This file is used as csv output of SMS Detail Report.
//
// Author :Parveen Sharma
// Created on : 27-11-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','SeatAllocationReport');
    define('ACCESS','view');
    define("MANAGEMENT_ACCESS",1);
    UtilityManager::ifNotLoggedIn();
    UtilityManager::headerNoCache();
    
      // CSV data field Comments added 
    function parseCSVComments($comments) {
         $comments = str_replace('"', '""', $comments);
         $comments = str_ireplace('<br/>', "\n", $comments);
         if(eregi(",", $comments) or eregi("\n", $comments)) {
           return '"'.$comments.'"'; 
         } 
         else {
             return chr(160).$comments; 
         }
    }
            
            
    require_once($FE . "/Library/HtmlFunctions.inc.php");
    $htmlManager  = HtmlFunctions::getInstance();
    
    require_once(MODEL_PATH . "/QuotaManager.inc.php");
    
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();

   
    // Search filter /////  
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'branchName';
    
    $orderBy = " $sortField $sortOrderBy";
    
    global $sessionHandler; 
    
    $allocationDate = $sessionHandler->getSessionVariable('AllocationDate');
    $classId = $sessionHandler->getSessionVariable('AllocationClassId');
    $quotaId = $sessionHandler->getSessionVariable('AllocationQuotaId'); 
    $roundId = $sessionHandler->getSessionVariable('AllocationRoundId'); 
   
    if($classId=='') {
      $classId = -1; 
    }
    
    if($quotaId=='') {
      $quotaId = -1; 
    }
    
    if($roundId=='') {
      $roundId = -1; 
    }
   
    $alotSeat= trim($REQUEST_DATA['alotSeat']);
    $rptSeat = trim($REQUEST_DATA['rptSeat']);
    $vcnSeat = trim($REQUEST_DATA['vcnSeat']);
    
    if($alotSeat=='') {
      $alotSeat=0;  
    }
    
    if($rptSeat=='') {
      $rptSeat=0;  
    }
    
    if($vcnSeat=='') {
      $vcnSeat=0;  
    }
    
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

    //$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);       
    $csvData = "As On, ".UtilityManager::formatDate($allocationDate)."\n";
    
    $foundArray = QuotaManager::getInstance()->getClassQuotaAllocationList($condition,$conditionDate,$allocationDate,$conditionMain,$orderBy); 
    $cnt = count($foundArray);
    
     
    $csvData .= "Sr. No.,Course Name,Seat Type,Total Seats";
    if($alotSeat!='0') {
      $csvData .= ",Allotted Seats";
    }
    
    if($rptSeat!='0') {
      $csvData .= ",Reported Seats";
    }
    
    if($vcnSeat!='0') {
      $csvData .= ",Vacant Seats";
    }                         
    $csvData .="\n";
    
    $j=0;
    $find=0;
    $tableData = "";
    $branchName='';
    
    $tt="";
    
    for($i=0;$i<$cnt;$i++) {
        $tbranchName = $foundArray[$i]['branchName'];
        if($foundArray[$i]['branchName']==$branchName) {
          $tbranchName = ""; 
        }
        else {
           $j=0;
           if($i!=0) {
             $csvData .= getTotalSeatsValue($totalSeat);   
           }
           $totalSeat['totalSeats']=0;
           $totalSeat['allotedSeats']=0;
           $totalSeat['reportedSeats']=0;
           $totalSeat['vacantSeats'] =0;
           $tt="";
        }
        $csvData .= getRecordValue($tbranchName,$foundArray[$i],$j); 
        
        $tt="1";   
        $find=1;   
        $branchName = $foundArray[$i]['branchName'];          
        $j++;
    }
      
    if($find==0) {
      $csvData .= ",,,No Data Found"; 
    }
    
    if($tt=="1") {
      $csvData .= getTotalSeatsValue($totalSeat);    
    }   
  
  
    UtilityManager::makeCSV($csvData,'SeatAllocationReport.csv');
    die;        
   
    
  function getRecordValue($tbranchName,$foundArray,$j) {

       global $totalSeat;  
       global $alotSeat;
       global $rptSeat;
       global $vcnSeat;
       
       $vacantSeats = $foundArray['totalSeats']-$foundArray['reportedSeats'];  
           
       $result = ($j+1).",".parseCSVComments(strip_slashes($tbranchName)).",".parseCSVComments(strip_slashes($foundArray['quotaName'])).",".parseCSVComments(strip_slashes($foundArray['totalSeats']));

       if($alotSeat!='0') {
          $result .= ",".parseCSVComments(strip_slashes($foundArray['allotedSeats']));
       }
       if($rptSeat!='0') {
          $result .= ",".parseCSVComments(strip_slashes($foundArray['reportedSeats']));
       }
       if($vcnSeat!='0') {
          $result .= ",".parseCSVComments(strip_slashes($vacantSeats));
       } 
       $result .= "\n";
       
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
        
        $result = ",,Total,".parseCSVComments(strip_slashes($totalSeat['totalSeats']));
        if($alotSeat!='0') {
          $result .= ",".parseCSVComments(strip_slashes($totalSeat['allotedSeats']));
        }
        if($rptSeat!='0') {
          $result .= ",".parseCSVComments(strip_slashes($totalSeat['reportedSeats']));
        }
        if($vcnSeat!='0') {
          $result .= ",".parseCSVComments(strip_slashes($totalSeat['vacantSeats']));
        } 
        $result .= "\n";
        
        return $result;              
    }
   
   
   