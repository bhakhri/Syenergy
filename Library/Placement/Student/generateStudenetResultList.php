<?php
//-------------------------------------------------------------------------------------------------------------
// Purpose: To store the records of students in array from the database, pagination and search, delete 
// functionality
// Author : Dipanjan Bbhattacharjee
// Created on : (21.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','PlacementGenerateStudentResultList');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE_ADMIN_MESSAGE;
    //$limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.',1000';

     //////
    
    /////search functionility not needed  
    $placementDriveId=trim($REQUEST_DATA['placementDriveId']);
    $graceMarks=trim($REQUEST_DATA['graceMarks']);
    
    if($placementDriveId==''){
       echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$count.'","page":"'.$page.'","info" : ['.$json_val.']}'; 
       die; 
    }
    $conditions =' WHERE pd.placementDriveId='.$placementDriveId.' AND pd.instituteId='.$sessionHandler->getSessionVariable('InstituteId');
    
    require_once(MODEL_PATH . "/Placement/StudentUploadManager.inc.php");
    $studentManager = StudentUploadManager::getInstance();
    
    //check for eligibility criteria,test,gd and interview
    $eliArray=$studentManager->getPlacementDriveCriteria($placementDriveId);

    $isTest=0;
    if($eliArray[0]['isTest']==1){
      $isTest=1;  
    }
    
    $isGD=0;
    if($eliArray[0]['groupDiscussion']==1){
      $isGD=1;  
    }
    
    $isInterview=0;
    if($eliArray[0]['individualInterview']==1){
      $isInterview=1;  
    }
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'studentName';
    $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
	$totalArray = $studentManager->getTotalPlacementDriveStudentResult($conditions,'',$orderBy);
    $studentRecordArray = $studentManager->getPlacementDriveStudentResultList($conditions,$limit,$orderBy);
    $cnt=count($studentRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
       $studentRecordArray[$i]['dob']=UtilityManager::formatDate($studentRecordArray[$i]['dob']);
       if($studentRecordArray[$i]['isSelected']==1){
           $selectionChecked='checked="checked"';
       }
       else{
           $selectionChecked='';
       }
       if($isTest==1){
        if($studentRecordArray[$i]['clearedTest']==1){
           $testChecked='checked="checked"';
        }
        else{
           $testChecked='';
        }
        $clearTestString="<input type=\"checkbox\" name=\"studentTestList\" id=\"studentTestList\" $testChecked value=\"".$studentRecordArray[$i]['studentId'] ."\"  onclick=vanishPrintExcel()>";
       }
       else{
         $clearTestString=NOT_APPLICABLE_STRING;    
       }
       
       if($isGD==1){
        if($studentRecordArray[$i]['clearedGroupDiscussion']==1){
           $gdChecked='checked="checked"';
        }
        else{
           $gdChecked='';
        }
        $clearGDString="<input type=\"checkbox\" name=\"studentGDList\" id=\"studentGDList\" $gdChecked value=\"".$studentRecordArray[$i]['studentId'] ."\" onclick=vanishPrintExcel()>";
       }
       else{
         $clearGDString=NOT_APPLICABLE_STRING;    
       }
       
       if($isInterview==1){
        if($studentRecordArray[$i]['clearedInterview']==1){
           $intvChecked='checked="checked"';
        }
        else{
           $intvChecked='';
        }
        $clearIntvString="<input type=\"checkbox\" name=\"studentIntvList\" id=\"studentIntvList\" $intvChecked value=\"".$studentRecordArray[$i]['studentId'] ."\" onclick=vanishPrintExcel()>";
       }
       else{
         $clearIntvString=NOT_APPLICABLE_STRING;    
       }
        
       $valueArray = array_merge(
                                 array(
                                       'srNo' => ($records+$i+1),
                                       "clearSelection" => "<input type=\"checkbox\" name=\"studentSelectionList\" id=\"studentSelectionList\" $selectionChecked value=\"".$studentRecordArray[$i]['studentId'] ."\" onclick=vanishPrintExcel()>",
                                       "clearTest" =>$clearTestString,
                                       "clearGD" =>$clearGDString,
                                       "clearInterview" =>$clearIntvString 
                                       )
                                       ,$studentRecordArray[$i]
                                 );

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    
	echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxAdminEmployeeMessageList.php $
?>