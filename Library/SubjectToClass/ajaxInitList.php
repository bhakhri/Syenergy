<?php
//-------------------------------------------------------
// Purpose: To store the records of class in array from the database functionality
//
// Author : Rajeev Aggarwal
// Created on : (02.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','AssignCourseToClass');
	define('ACCESS','view');
    UtilityManager::ifNotLoggedIn();
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/SubjectToClassManager.inc.php");
    $subjecttoclassManager = SubjectToClassManager::getInstance();
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'DESC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'subtocls.subjectId';
	$orderBy = " $sortField $sortOrderBy";
	
    $searchSubjectCodeList = trim($REQUEST_DATA['searchSubjectCode']); 
    $classId    = $REQUEST_DATA['classId'];
    $allSubject    = $REQUEST_DATA['allSubject'];
    
    if($allSubject=='') {
      $allSubject='1';  
    }

    $filter1 = '';
    $duplicateArray =array();
    if($searchSubjectCodeList!='') {
       $searchSubjectCodeArray = explode(',',$searchSubjectCodeList);
       
       $searchSubjectCode='';  
       for($i=0;$i<count($searchSubjectCodeArray);$i++) {
          if(trim($searchSubjectCodeArray[$i])!='') {
            $find='0';  
            for($j=0;$j<count($duplicateArray);$j++) {
              if($duplicateArray[$j] == strtoupper(trim($searchSubjectCodeArray[$i])))  {
               $find='1';   
                break;  
              }
            }
            if($find=='0') {  
              if($searchSubjectCode!='') {
                $searchSubjectCode .=", ";  
              } 
              $searchSubjectCode .= "'".htmlentities(add_slashes(trim($searchSubjectCodeArray[$i])))."'";   
              $duplicateArray[] = strtoupper(trim($searchSubjectCodeArray[$i]));
            }
          }
       } 
       if($searchSubjectCode != '') {
         $filter1 .= " AND ( UPPER(TRIM(sub.subjectCode)) IN ($searchSubjectCode) ) ";
       }
    }

    
	$filter1 .= " AND st.universityId = (select universityId FROM class WHERE classId=$classId)";
	if(UtilityManager::notEmpty($REQUEST_DATA['subjectDetail'])) {
       $filter1 .= ' AND (sub.subjectCode LIKE "%'.add_slashes($REQUEST_DATA['subjectDetail']).'%"  OR sub.subjectName LIKE "%'.add_slashes($REQUEST_DATA['subjectDetail']).'%" OR sub.subjectAbbreviation LIKE "%'.add_slashes($REQUEST_DATA['subjectDetail']).'%")';
    }
	$filter	.= " AND subtocls.classId=".$classId;

	$totalArray = $subjecttoclassManager->countSubList($filter.''.$filter1);
	$recCount = $totalArray[0]['countRecords']; 
	
	if($allSubject=='0')
	  $subjecttoclassRecordArray = $subjecttoclassManager->getSubToClassList($filter,$filter1,$limit,$orderBy);
	else
	  $subjecttoclassRecordArray = $subjecttoclassManager->getSubList($filter.''.$filter1,$limit,$orderBy);

	$cnt = count($subjecttoclassRecordArray);
    for($i=0;$i<$cnt;$i++) {

		$subjectId = $subjecttoclassRecordArray[$i][subjectToClassId];
		$isSubjectId = '';

		$credits = $subjecttoclassRecordArray[$i]['credits'];
		$isAlternateSubject = $subjecttoclassRecordArray[$i]['isAlternateSubject'];
		$internalMarks = $subjecttoclassRecordArray[$i]['internalTotalMarks'];
		$externalMarks = $subjecttoclassRecordArray[$i]['externalTotalMarks'];

		if($subjectId!=''){

			$checkall = '<span style="background-color:RED"><input type="checkbox" name="chb[]" value="'.strip_slashes($subjecttoclassRecordArray[$i]['subjectId']).'" checked></span>';
		}
		else{

			$checkall = '<input type="checkbox" name="chb[]" value="'.strip_slashes($subjecttoclassRecordArray[$i]['subjectId']).'">';
		}
		
		if($isAlternateSubject=='1'){
			$isAlternate = '<input type="checkbox" name="isAlternate'.($subjecttoclassRecordArray[$i]['subjectId']).'" value="1" checked>Yes';
		}
		else{
			$isAlternate = '<input type="checkbox" name="isAlternate'.($subjecttoclassRecordArray[$i]['subjectId']).'" value="1" >Yes';

		}
		
		if($subjecttoclassRecordArray[$i]['optional']){

			$optional = '<span style="background-color:RED"><input type="checkbox" name="optional'.($subjecttoclassRecordArray[$i]['subjectId']).'" id="optional'.($subjecttoclassRecordArray[$i]['subjectId']).'" value="1" checked OnClick=CheckStatus('.$subjecttoclassRecordArray[$i]['subjectId'].')>Yes</span>';
		}
		else{
			$optional = '<input type="checkbox" name="optional'.($subjecttoclassRecordArray[$i]['subjectId']).'" id="optional'.($subjecttoclassRecordArray[$i]['subjectId']).'" value="1" OnClick=CheckStatus('.$subjecttoclassRecordArray[$i]['subjectId'].')>Yes';

		}

		if($subjecttoclassRecordArray[$i]['hasParentCategory']){

			$hasParentCategory1 = '<span style="background-color:RED"><input type="checkbox" name="hasParentCategory'.($subjecttoclassRecordArray[$i]['subjectId']).'" id="hasParentCategory'.($subjecttoclassRecordArray[$i]['subjectId']).'"  value="1" checked >Yes</span>';
		}
		else{

			if($subjecttoclassRecordArray[$i]['optional']){
			
				$hasParentCategory1 = '<input type="checkbox" name="hasParentCategory'.($subjecttoclassRecordArray[$i]['subjectId']).'" id="hasParentCategory'.($subjecttoclassRecordArray[$i]['subjectId']).'" value="1" "'.$disabled.'">Yes';
			}
			else{
				
				$hasParentCategory1 = '<input type="checkbox" name="hasParentCategory'.($subjecttoclassRecordArray[$i]['subjectId']).'" id="hasParentCategory'.($subjecttoclassRecordArray[$i]['subjectId']).'" value="1" DISABLED=TRUE>Yes'; 
				 
			}
			

		}

		if($subjecttoclassRecordArray[$i]['offered']){
			$offered = '<span style="background-color:RED"><input type="checkbox" name="offered'.($subjecttoclassRecordArray[$i]['subjectId']).'" "'.$isOffered.'" value="1" checked>Yes</span>';
		}
		else{
			$offered = '<input type="checkbox" name="offered'.($subjecttoclassRecordArray[$i]['subjectId']).'" "'.$isOffered.'" value="1" "">Yes';

		}
		$noofcredits = '<input type="text" class="inputbox1" size="4" name="credit'.($subjecttoclassRecordArray[$i]['subjectId']).'" id="credit'.($subjecttoclassRecordArray[$i]['subjectId']).'" value="'.$credits.'">';

		$internalMarks = '<input type="text" class="inputbox1" size="4" name="internalMarks'.($subjecttoclassRecordArray[$i]['subjectId']).'" id="internalMarks'.($subjecttoclassRecordArray[$i]['subjectId']).'" value="'.$internalMarks.'" maxlength="4">';

		$externalMarks = '<input type="text" class="inputbox1" size="4" name="externalMarks'.($subjecttoclassRecordArray[$i]['subjectId']).'" id="externalMarks'.($subjecttoclassRecordArray[$i]['subjectId']).'" value="'.$externalMarks.'" maxlength="4">';

        // add subjectId in actionId to populate edit/delete icons in User Interface   
        $valueArray = array_merge(array('noofcredits' => $noofcredits ,'isAlternate' => $isAlternate,'internalMarks1' => $internalMarks ,'externalMarks1' => $externalMarks,'Optional' => $optional,'hasParentCategory1' => $hasParentCategory1 ,'Offered' => $offered ,'checkAll' => $checkall ,'action' => $subjecttoclassRecordArray[$i]['subjectId'] , 'srNo' => ($records+$i+1) ),$subjecttoclassRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"classId":"'.$classId.'","sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxInitList.php $
//
//*****************  Version 10  *****************
//User: Ajinder      Date: 3/30/10    Time: 1:33p
//Updated in $/LeapCC/Library/SubjectToClass
//bugs fixed. FCNS No.1490
//
//*****************  Version 9  *****************
//User: Rajeev       Date: 09-08-18   Time: 3:07p
//Updated in $/LeapCC/Library/SubjectToClass
//Fixed 1090,1089,1088,1058 bugs
//
//*****************  Version 8  *****************
//User: Rajeev       Date: 7/20/09    Time: 12:56p
//Updated in $/LeapCC/Library/SubjectToClass
//Added "hasParentCategory" in subject to class module
//
//*****************  Version 7  *****************
//User: Rajeev       Date: 7/14/09    Time: 5:45p
//Updated in $/LeapCC/Library/SubjectToClass
//added define variable for Role Permission
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 5/07/09    Time: 2:11p
//Updated in $/LeapCC/Library/SubjectToClass
//Updated subject list function to show subjectype also
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 1/12/09    Time: 10:23a
//Updated in $/LeapCC/Library/SubjectToClass
//added required field and centralized message
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 1/05/09    Time: 6:04p
//Updated in $/LeapCC/Library/SubjectToClass
//Updated javascript validations
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 12/11/08   Time: 3:00p
//Updated in $/LeapCC/Library/SubjectToClass
//Updated module as per CC functionality
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/SubjectToClass
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 9/11/08    Time: 5:38p
//Updated in $/Leap/Source/Library/SubjectToClass
//updated formatting and added comments
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 8/18/08    Time: 7:34p
//Updated in $/Leap/Source/Library/SubjectToClass
//updated file
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 8/18/08    Time: 3:44p
//Updated in $/Leap/Source/Library/SubjectToClass
//updated checked property of check box
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 8/09/08    Time: 2:02p
//Updated in $/Leap/Source/Library/SubjectToClass
//updated the functionality to map subject with class.
//made ajax based and removed study period and batch from search
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 7/29/08    Time: 12:40p
//Updated in $/Leap/Source/Library/SubjectToClass
//optimize the query
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 7/07/08    Time: 12:56p
//Created in $/Leap/Source/Library/SubjectToClass
//intial checkin
?>