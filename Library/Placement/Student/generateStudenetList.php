<?php
//-------------------------------------------------------------------------------------------------------------
// Purpose: To store the records of students in array from the database, pagination and search, delete 
// functionality
// Author : Dipanjan Bbhattacharjee
// Created on : (21.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','PlacementGenerateStudentList');
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
    
    //check for eligibility criteria
    $eliArray=$studentManager->getPlacementDriveCriteria($placementDriveId);
    $criteriaConditions='';
    if($eliArray[0]['eligibilityCriteria']==1){
      if($graceMarks!=''){
        if($eliArray[0]['lastSem']==1){  
         $conditions .=" AND ( 
                           (s.marks10th >=(pdc.cutOffMarks10th-$graceMarks))
                            AND 
                           (s.marks12th >=(pdc.cutOffMarks12th-$graceMarks))
                            AND
                           (s.marksLastSem >=(pdc.cutOffMarksLastSem-$graceMarks))
                           ) ";  
        }
        if($eliArray[0]['grads']==1){  
         $conditions .=" AND ( 
                           (s.marks10th >=(pdc.cutOffMarks10th-$graceMarks))
                            AND 
                           (s.marks12th >=(pdc.cutOffMarks12th-$graceMarks))
                            AND
                           (s.marksGraduation >=(pdc.cutOffMarksGraduation-$graceMarks))
                           ) ";  
        }
      }
    }
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'studentName';
    $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
	$totalArray = $studentManager->getTotalPlacementDriveStudent($conditions,'',$orderBy);
    $studentRecordArray = $studentManager->getPlacementDriveStudentList($conditions,$limit,$orderBy);
    $cnt=count($studentRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
       $studentRecordArray[$i]['dob']=UtilityManager::formatDate($studentRecordArray[$i]['dob']);
       if($studentRecordArray[$i]['isAllocated']==1){
           $checked='checked="checked"';
       }
       else{
           $checked='';
       } 
       
       if($studentRecordArray[$i]['marksLastSem']==''){
         $studentRecordArray[$i]['marksLastSem']=NOT_APPLICABLE_STRING;
       }
       if($studentRecordArray[$i]['marksGraduation']==''){
         $studentRecordArray[$i]['marksGraduation']=NOT_APPLICABLE_STRING;
       }
       
       
       $valueArray = array_merge(array('srNo' => ($records+$i+1),"students" => "<input type=\"checkbox\" name=\"studentList\" id=\"studentList\" $checked value=\"".$studentRecordArray[$i]['studentId'] ."\">")
        , $studentRecordArray[$i]);

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