<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE QUOTA Seats LIST
//
// Author : Parveen Sharma
// Created on : (12.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/QuotaManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

    
    $classId = trim($REQUEST_DATA['classId']);
    $allocationDate = trim($REQUEST_DATA['allocationDate']);       
    $roundId = trim($REQUEST_DATA['roundId']);       

    if($classId=='') {
      $classId=0;  
    }
    
    if($roundId=='') {
      $roundId=0;
    }
    
  
    //////
    /// Search filter /////  
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'quotaName';
    
    $orderBy = " $sortField $sortOrderBy";
    
    $conditionClassId =  " AND qs.classId = '$classId' ";
    $conditionDate = " AND cc1.classId = '$classId' AND cc1.allocationDate < '$allocationDate' ";
    $condition = " AND qa.classId = '$classId' AND qa.allocationDate='$allocationDate' AND qa.roundId = '$roundId'";
    
    $foundArray = QuotaManager::getInstance()->getClasswiseQuotaList($conditionDate,$condition,$conditionClassId); 
    $cnt = count($foundArray);
  
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* $cnt;
    $limit      = ' LIMIT '.$records.','.$cnt;  
    
    //$records    = ($page-1)* RECORDS_PER_PAGE;
    //$limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;  
    
    //echo "<pre>";
    //print_r($foundArray);
    //die;
    
    for($i=0;$i<$cnt;$i++) {
       $id = $foundArray[$i]['quotaId'];
      
       $classAllocationId = $foundArray[$i]['classAllocationId'];   
       $newAllocatedSeats = $foundArray[$i]['newSeatsAllocation']; 
       if($newAllocatedSeats==0) {
         $newAllocatedSeats = $foundArray[$i]['seatsAllocated'];  
       }
       
       $foundArray[$i]['newAllocatedSeats']= "<input  type='text' class='inputbox'  onkeydown='return sendKeys(event,this.form);' style='width:100px' name='newAllocatedSeats[]' maxlength='5' id='newAllocatedSeats".$id."' value='".$newAllocatedSeats."' />
                                              <input  type='hidden' class='inputbox'  style='width:100px' name='quotaId[]' readonly='readonly' id='quotaId".$id."' value='".$id."' />";
                     
       $valueArray = array_merge(array('srNo' => ($records+$i+1)),$foundArray[$i]);
       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$cnt.'","page":"'.$page.'","info" : ['.$json_val.']}';
        
?>


