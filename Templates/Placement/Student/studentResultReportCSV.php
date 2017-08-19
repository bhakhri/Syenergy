<?php 
// This file is used as csv version for Company.
// Author :Dipanjan Bhattacharjee
// Created on : 24.10.2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/Placement/StudentUploadManager.inc.php");
    $studentManager = StudentUploadManager::getInstance();

//to parse csv values    
function parseCSVComments($comments) {
 $comments = str_replace('"', '""', $comments);
 $comments = str_ireplace('<br/>', "\n", $comments);
 if(eregi(",", $comments) or eregi("\n", $comments)) {
   return '"'.$comments.'"'; 
 } 
 else {
 return $comments; 
 }
 
}

    //////search functionility not needed  
    $placementDriveId=trim($REQUEST_DATA['placementDriveId']);
    $graceMarks=trim($REQUEST_DATA['graceMarks']);
    
    if($placementDriveId==''){
       die('Required Parameters Missing'); 
    }
    $conditions =' WHERE pd.placementDriveId='.$placementDriveId.' AND pd.instituteId='.$sessionHandler->getSessionVariable('InstituteId');
    
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
    
    $studentRecordArray = $studentManager->getPlacementDriveStudentResultList($conditions,' ',$orderBy);
    $cnt=count($studentRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
       $studentRecordArray[$i]['dob']=UtilityManager::formatDate($studentRecordArray[$i]['dob']);
       if($studentRecordArray[$i]['isSelected']==1){
           $selectionChecked='Yes';
       }
       else{
           $selectionChecked='No';
       }
       if($isTest==1){
        if($studentRecordArray[$i]['clearedTest']==1){
           $testChecked='Yes';
        }
        else{
           $testChecked='No';
        }
        $clearTestString=$testChecked;
       }
       else{
         $clearTestString=NOT_APPLICABLE_STRING;    
       }
       
       if($isGD==1){
        if($studentRecordArray[$i]['clearedGroupDiscussion']==1){
           $gdChecked='Yes';
        }
        else{
           $gdChecked='No';
        }
        $clearGDString=$gdChecked;
       }
       else{
         $clearGDString=NOT_APPLICABLE_STRING;    
       }
       
       if($isInterview==1){
        if($studentRecordArray[$i]['clearedInterview']==1){
           $intvChecked='Yes';
        }
        else{
           $intvChecked='No';
        }
        $clearIntvString=$intvChecked;
       }
       else{
         $clearIntvString=NOT_APPLICABLE_STRING;    
       }
        
       $valueArray[] = array_merge(
                                 array(
                                       'srNo' => ($records+$i+1),
                                       "clearSelection"=>$selectionChecked,
                                       "clearTest" =>$clearTestString,
                                       "clearGD" =>$clearGDString,
                                       "clearInterview" =>$clearIntvString 
                                       )
                                       ,$studentRecordArray[$i]
                                 );
    }

	$csvData = "Search By: Placement Drive : ".trim($REQUEST_DATA['placementDriveName'])."\n";
    $csvData .= "#, Student, DOB, College, Cleared Test, Cleared G.D., Cleared Interview, Selected \n";
	foreach($valueArray as $record) {
        $csvData .= $record['srNo'].','.parseCSVComments($record['studentName']).', '.parseCSVComments($record['dob']).','.parseCSVComments($record['college']).','.parseCSVComments($record['clearTest']).','.parseCSVComments($record['clearGD']).','.parseCSVComments($record['clearInterview']).','.parseCSVComments($record['clearSelection']);
		$csvData .= "\n";
	}
    
    if(count($valueArray)==0){
       $csvData .=",".NOT_APPLICABLE_STRING;  
    }
    
	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	header('Content-type: application/octet-stream; charset=utf-8');
	header("Content-Length: " .strlen($csvData) );
	header('Content-Disposition: attachment;  filename="studentResultList.csv"');
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    
// $History: testTypeCSV.php $
?>