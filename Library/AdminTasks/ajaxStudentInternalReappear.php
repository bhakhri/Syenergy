<?php
//-------------------------------------------------------------------
// This File contains the show details of Student Internal Subject Re-appear detail
// Author :Parveen Sharma
// Created on : 19-01-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

   global $FE;
   require_once($FE . "/Library/common.inc.php");
   require_once(BL_PATH . "/UtilityManager.inc.php");
   define('MODULE','DisplayStudentReappear');
   define('ACCESS','view');

    UtilityManager::ifNotLoggedIn();    
    UtilityManager::headerNoCache();
    require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
    
    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();
   
   
    $reappearClassId  = add_slashes($REQUEST_DATA['reappearClassId']);   
    $startDate = add_slashes($REQUEST_DATA['startDate']);
    $endDate = add_slashes($REQUEST_DATA['endDate']);
    $rollNo = add_slashes(trim($REQUEST_DATA['rollNo']));
    
    if($reappearClassId=='') {
      $reappearClassId = 0;
    }
    
    $conditions = "";
    
    if($startDate!='' && $endDate =='')
       $conditions .= " AND dateOfEntry >='$startDate' ";

    if($startDate=='' && $endDate!='')
        $conditions .= " AND dateOfEntry <='$endDate'";

    if($startDate!='' && $endDate!=''){
       $conditions .= " AND ((dateOfEntry BETWEEN '$startDate' AND '$endDate') OR (dateOfEntry BETWEEN '$startDate' AND '$endDate'))";
    }
    
    $conditions .= " AND reapperClassId = $reappearClassId";
    
    if($rollNo!='') {
      $conditions .= " AND rollNo LIKE '$rollNo%' ";
    }
   
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'studentName';
    
    $orderBy = "ORDER BY $sortField $sortOrderBy";

    $totalRecordArray = $studentManager->getClasswiseReappearCount($conditions);
    $studentRecordArray = $studentManager->getClasswiseReappearDetails($conditions,$orderBy,$limit);
    
    global $reppearStatusArr; 
    
    $cnt = count($studentRecordArray);
    for($i=0;$i<$cnt;$i++) {
       $id = $studentRecordArray[$i]['studentId'];
       $curr = $studentRecordArray[$i]['currentClassId'];
       $reap = $studentRecordArray[$i]['reapperClassId'];
       $repStatus = $reppearStatusArr[$studentRecordArray[$i]['reppearStatus']];
       
       
       $studentName = $studentRecordArray[$i]['studentName'];  
       $rollNo = $studentRecordArray[$i]['rollNo'];  
       $repClassName = $studentRecordArray[$i]['reappearClassName'];
       
        
       $studentDetails = $studentRecordArray[$i]['studentId'].",".$curr.",".$reap;
       $checkall = '<input type="checkbox" name="chb[]" value="'.$studentDetails.'">
                     <input type="hidden" readonly name="studentDetails[]" id="studentDetails'.$id.'" value="'.$studentDetails.'">';
                     
       $studentDetained = $studentRecordArray[$i]['studentId'].",".$curr.",".$reap;                   
       $studentRecordArray[$i]['studentDetained'] = $studentDetained;
       
       $ids = "(".$studentDetained.")";
       $action1 = '<input type="image" title="Edit" alt="Edit" name="ddetails" src="'.IMG_HTTP_PATH.'/edit.gif" onClick="return refreshShowStudentDetails(\''.$ids.'\',\''.$studentName.'\',\''.$rollNo.'\',\''.$repClassName.'\',\'divInfo\',605,350); return false;" />&nbsp;
                   <input type="image" title="Delete" alt="Delete" name="sdetails" src="'.IMG_HTTP_PATH.'/delete.gif" onClick="return deleteApproval(\''.$ids.'\'); return false;" />';
        
       $valueArray = array_merge(array('checkAll' => $checkall, 
                                        'action1' => $action1,
                                        'srNo' => ($records+$i+1) ),$studentRecordArray[$i]);
       if(trim($json_val)=='') {
         $json_val = json_encode($valueArray);
       }
       else {
         $json_val .= ','.json_encode($valueArray);           
       }
    }
    
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalRecordArray[0]['cnt'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 

?>

<?php    
//$History : $  
?>