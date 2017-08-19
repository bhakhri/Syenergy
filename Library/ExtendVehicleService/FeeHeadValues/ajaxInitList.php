<?php
//-------------------------------------------------------
// Purpose: To store the records of states in array from the database, pagination and search, delete 
// functionality
//
//
// Author :Arvind Singh Rawat
// Created on : 18-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','FeeHeadValues');  
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/FeeHeadValuesManager.inc.php");
    $feeHeadValuesManager =FeeHeadValuesManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       
       if(add_slashes(strtolower($REQUEST_DATA['searchbox'])) == 'yes' || add_slashes(strtolower($REQUEST_DATA['searchbox'])) == 'y' || add_slashes(strtolower($REQUEST_DATA['searchbox'])) == 'ye') 
          $str = ' OR fh.isConsessionable = "1" OR fh.transportHead = "1" OR fh.hostelHead = "1" OR fh.miscHead = "1"  OR fhv.isLeet = "1"  ';
       else if(add_slashes(strtolower($REQUEST_DATA['searchbox'])) == 'no'  || add_slashes(strtolower($REQUEST_DATA['searchbox'])) == 'n') 
          $str = ' OR fh.isConsessionable = "0" OR fh.transportHead = "0" OR fh.hostelHead = "0" OR fh.miscHead = "0"  OR fhv.isLeet = "0"  ';  
       else if(add_slashes(strtolower($REQUEST_DATA['searchbox'])) == 'both'  || add_slashes(strtolower($REQUEST_DATA['searchbox'])) == 'bo'  || add_slashes(strtolower($REQUEST_DATA['searchbox'])) == 'bot') 
          $str = '  OR fhv.isLeet = "2" OR fhv.isLeet IS NULL ';   
        
       if(strtoupper($REQUEST_DATA['searchbox'])=='ALL') {
          $filter = ' AND (bat.batchName IS NULL  OR b.branchCode IS NULL OR std.periodName IS NULL OR 
                           d.degreeAbbr IS NULL OR univ.universityAbbr IS NULL '.$str.')'; 
       }
       else {
          $filter = ' AND (fc.cycleName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR 
                           fh.headAbbr LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR 
                           ffa.allocationEntity LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR 
                           fhv.feeHeadAmount LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR 
                           bat.batchName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR 
                           b.branchCode LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR 
                           std.periodName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR 
                           d.degreeAbbr LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR 
                           univ.universityAbbr LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%"'.$str.')';         
       }
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'headAbbr';
    
     $orderBy = " $sortField $sortOrderBy";         
       
    ////////////
    $totalArray = $feeHeadValuesManager->getTotalFeeHeadValues($filter);  
    $feeHeadValuesRecordArray = $feeHeadValuesManager->getFeeHeadValuesList($filter,$limit,$orderBy);
    
    $cnt = count($feeHeadValuesRecordArray);
    
    function changeNull($null){
        if(strtoupper($null) == NULL){
            return All;
        }
        else{
            return $null;
        }
    }
    for($i=0;$i<$cnt;$i++) {
        $feeHeadValuesRecordArray[$i]['batchName']=changeNull($feeHeadValuesRecordArray[$i]['batchName']);
        $feeHeadValuesRecordArray[$i]['branchCode']=changeNull($feeHeadValuesRecordArray[$i]['branchCode']);
        $feeHeadValuesRecordArray[$i]['universityAbbr']=changeNull($feeHeadValuesRecordArray[$i]['universityAbbr']);
        $feeHeadValuesRecordArray[$i]['periodName']=changeNull($feeHeadValuesRecordArray[$i]['periodName']);
        $feeHeadValuesRecordArray[$i]['degreeAbbr']=changeNull($feeHeadValuesRecordArray[$i]['degreeAbbr']);

		if($feeHeadValuesRecordArray[$i]['isLeet']=="1"){
 		   $feeHeadValuesRecordArray[$i]['isLeet']="Yes";
		}
		elseif($feeHeadValuesRecordArray[$i]['isLeet']=="0"){
		   $feeHeadValuesRecordArray[$i]['isLeet']="No";
		}
		else {		
		   $feeHeadValuesRecordArray[$i]['isLeet']="Both";
		}

		//$feeHeadValuesRecordArray[$i]['isLeet']=changeNull($feeHeadValuesRecordArray[$i]['isLeet']);
        // add feeHeadValueId in actionId to populate edit/delete icons in User Interface   
       $feeHeadValuesRecordArray[$i]['feeHeadAmount']=number_format($feeHeadValuesRecordArray[$i]['feeHeadAmount'],2,'.','');
       $valueArray = array_merge(
       array('action' => $feeHeadValuesRecordArray[$i]['feeHeadValueId'] , 
       'srNo' => ($records+$i+1) ,
       'headAbbr'=>$feeHeadValuesRecordArray[$i]['headAbbr'],
        'cycleName'=>$feeHeadValuesRecordArray[$i]['cycleName'],
         'allocationEntity'=>$feeHeadValuesRecordArray[$i]['allocationEntity'], 
         'universityAbbr'=> $feeHeadValuesRecordArray[$i]['universityAbbr'],
         'branchCode'=> $feeHeadValuesRecordArray[$i]['branchCode'], 
         'periodName'=>$feeHeadValuesRecordArray[$i]['periodName'],
         'degreeAbbr'=> $feeHeadValuesRecordArray[$i]['degreeAbbr'], 
         'batchName'=>$feeHeadValuesRecordArray[$i]['batchName'], 
		   'isLeet'=>$feeHeadValuesRecordArray[$i]['isLeet'], 
         'feeHeadAmount'=>$feeHeadValuesRecordArray[$i]['feeHeadAmount']
         ));
       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
  
  //$History : $  
?>