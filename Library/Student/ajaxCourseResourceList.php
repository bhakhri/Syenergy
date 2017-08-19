<?php
//-----------------------------------------------------------------------------------------------------------
// Purpose: To fetch the records of course resource
//
// Author : Jaineesh
// Created on : (08.11.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(BL_PATH.'/HtmlFunctions.inc.php');  
    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifStudentNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/Student/StudentInformationManager.inc.php");
    $scStudentManager = StudentInformationManager::getInstance();


	
    
    /////////////////////////
    
//---------------------------------------------------------------------------------------------------------------  
//purpose: to trim a string and output str.. etc
//Author:Jaineesh
//Date:2.09.2008
//$str=input string,$maxlenght:no of characters to show,$rep:what to display in place of truncated string
//$mode=1 : no split after 30 chars,mode=2:split after 30 characters
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------  
function trim_output($str,$maxlength='250',$rep='...'){
   $ret=chunk_split($str,60);

   if(strlen($ret) > $maxlength){
      $ret=substr($ret,0,$maxlength).$rep; 
   }
  return $ret;  
}
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
 
     /// Search filter /////  
     if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) { 
       $filter = ' AND ( s.subjectCode LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR 
       					 a.description LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR 
       					 a.resourceUrl LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR 
       					 r.resourceName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR 
       					 DATE_FORMAT(a.postedDate,"%d-%b-%y") LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR 
       					 e.employeeName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%")';         
    }
	
	if(UtilityManager::notEmpty($REQUEST_DATA['subjectCode'])) { 
       $filter .= ' AND ( a.subjectId='.add_slashes(trim($REQUEST_DATA['subjectCode'])).' )';         
    }
	
	if(UtilityManager::notEmpty($REQUEST_DATA['type'])) { 
       $filter .= ' AND ( a.resourceTypeId ='.add_slashes(trim($REQUEST_DATA['type'])).' )';         
    }
	
	if(UtilityManager::notEmpty($REQUEST_DATA['teacher'])) { 
       $filter .= ' AND ( a.employeeId='.add_slashes(trim($REQUEST_DATA['teacher'])).')';         
    }
	
	if(UtilityManager::notEmpty($REQUEST_DATA['postDate'])) { 
       $filter .= ' AND ( a.postedDate LIKE "%'.add_slashes(trim($REQUEST_DATA['postDate'])).'%" )';         
    }
    

    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'subjectCode';
    
     $orderBy = " $sortField $sortOrderBy";         

	$studentId= $sessionHandler->getSessionVariable('StudentId');
	$classId = $REQUEST_DATA['semesterDetail'];
	if($classId == 0) {
		require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
		$commonQueryManager = CommonQueryManager::getInstance();
		$getClassArray = $commonQueryManager->getStudyPeriodData($studentId);
		$classCount = count($getClassArray);
		if($classCount > 0 AND is_array($getClassArray)) {
		 $classList = UtilityManager::makeCSList($getClassArray,'classId');
		 if($classList != '') {
			//$totalArray = $scStudentManager->getTotalCourseResourceList($studentId,$classList,$filter);
			$totalArray = $scStudentManager->getCourseResourceList($studentId,$classList,$filter,$orderBy,'');
    		$resourceRecordArray = $scStudentManager->getCourseResourceList($studentId,$classList,$filter,$orderBy,$limit);
		//	print_r($resourceRecordArray);die;
		 }
		}
    }
	else {
		//$totalArray = $scStudentManager->getTotalStudentCourseResourceList($studentId,$classId,$filter);
		$totalArray = $scStudentManager->getStudentCourseResourceLists($studentId,$classId,$filter,$orderBy,'');
		$resourceRecordArray = $scStudentManager->getStudentCourseResourceLists($studentId,$classId,$filter,$orderBy,$limit);
	//	$resourceRecordArray = $scStudentManager->getStudentCourseResourceList($studentId,$classId,$filter,$orderBy,$limit);
	}
	$cnt = count($resourceRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
 
        // add stateId in actionId to populate edit/delete icons in User Interface
       
       //for file downloading
	   $resourceRecordArray[$i]['postedDate'] = UtilityManager::formatDate($resourceRecordArray[$i]['postedDate']);
        $fileResource = IMG_PATH."/CourseResource/".$resourceRecordArray[$i]['attachmentFile'];
		
			if(file_exists($fileResource) && ($resourceRecordArray[$i]['attachmentFile']!="")){
				$fileResource1 = IMG_HTTP_PATH."/CourseResource/".$resourceRecordArray[$i]['attachmentFile'];
				//$fileResource1= '<a href="#"><img src="'.IMG_HTTP_PATH.'/download.gif" onclick="download(this.name);" title="Download File" name="'.$resourceRecordArray[$i]['attachmentFile'].'"></a>';
                $fileResource1= '<a href="#"><img src="'.IMG_HTTP_PATH.'/download.gif" onclick="download('.$resourceRecordArray[$i]['courseResourceId'].');" title="Download File" name="'.$resourceRecordArray[$i]['attachmentFile'].'"></a>';
			}
			else {
			 	 $fileResource1 = NOT_APPLICABLE_STRING;
			}
                                
        //for url clicking
        if ($resourceRecordArray[$i]['resourceUrl']!=""){
				$fileName = '<a href="'.strip_slashes($resourceRecordArray[$i]['resourceUrl']).'" target="_blank">'.trim_output(strip_slashes($resourceRecordArray[$i]['resourceUrl']),40).'</a>';
				}
				else {
					$fileName = NOT_APPLICABLE_STRING;
				}
                     
       $valueArray = array_merge(
                                 array('resUrl'=>$fileName,'attFile'=>$fileResource1,'srNo' => ($records+$i+1)), 
                                 $resourceRecordArray[$i]
                                );

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.COUNT($totalArray).'","page":"'.$page.'","info" : ['.$json_val.']}'; 
// for VSS
// $History: ajaxCourseResourceList.php $
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 11/03/09   Time: 4:40p
//Updated in $/LeapCC/Library/Student
//fixed bug nos.0001899, 0001898, 0001891,0001889
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 10/24/09   Time: 3:50p
//Updated in $/LeapCC/Library/Student
//fixed bug nos. 0001883, 0001877 and modification in query
//getStudentCourseResourceList() to get courses of current class and make
//searchable course
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 10/21/09   Time: 12:29p
//Updated in $/LeapCC/Library/Student
//added access defines
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 12/25/08   Time: 4:38p
//Updated in $/LeapCC/Library/Student
//modified for paging
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/09/08   Time: 5:23p
//Created in $/LeapCC/Library/Student
//new file for course resourse for cc
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 11/11/08   Time: 10:40a
//Created in $/Leap/Source/Library/Teacher/ScStudentActivity
//Added Resource Paging and sorting in student tab view
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 11/05/08   Time: 2:44p
//Created in $/Leap/Source/Library/Teacher/ScTeacherActivity
//Created CourseResource Module
?>
