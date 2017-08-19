<?php
//-------------------------------------------------------
// Purpose: To store the records of class in array from the database functionality
//
// Author : Ajinder Singh
// Created on : (28-jan-2010)
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
	
	$classId	= $REQUEST_DATA['classId'];
	$allSubject	= $REQUEST_DATA['allSubject'];
	$filter1='';
	if(UtilityManager::notEmpty($REQUEST_DATA['subjectDetail'])) {


       $filter1 = ' AND (sub.subjectCode LIKE "%'.add_slashes($REQUEST_DATA['subjectDetail']).'%"  OR sub.subjectName LIKE "%'.add_slashes($REQUEST_DATA['subjectDetail']).'%" OR sub.subjectAbbreviation LIKE "%'.add_slashes($REQUEST_DATA['subjectDetail']).'%")';
    }
	
	$filter	.= " AND subtocls.classId=".$classId;

	$totalArray = $subjecttoclassManager->countSubList($filter.''.$filter1);
	$recCount = $totalArray[0]['countRecords']; 
	
	if($allSubject)
		$subjecttoclassRecordArray = $subjecttoclassManager->getSubToClassList($filter,$filter1,$limit,$orderBy);
	else
		$subjecttoclassRecordArray = $subjecttoclassManager->getSubList($filter.''.$filter1,$limit,$orderBy);

	$cnt = count($subjecttoclassRecordArray);

	
    for($i=0;$i<$cnt;$i++) {

		$subjectId = $subjecttoclassRecordArray[$i][subjectToClassId];
		$isSubjectId = '';

		$credits = $subjecttoclassRecordArray[$i]['credits'];
		$internalMarks = $subjecttoclassRecordArray[$i]['internalTotalMarks'];
		$externalMarks = $subjecttoclassRecordArray[$i]['externalTotalMarks'];

		if($subjectId!=''){

			$checkall = '<span style="background-color:RED"><input type="checkbox" name="chb[]" value="'.strip_slashes($subjecttoclassRecordArray[$i]['subjectId']).'" checked></span>';
		}
		else{

			$checkall = '<input type="checkbox" name="chb[]" value="'.strip_slashes($subjecttoclassRecordArray[$i]['subjectId']).'">';
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

		$action2 = '<img src="'.IMG_HTTP_PATH.'/edit2.gif" />';

        // add subjectId in actionId to populate edit/delete icons in User Interface   
        $valueArray = array_merge(array('noofcredits' => $noofcredits ,'internalMarks1' => $internalMarks ,'externalMarks1' => $externalMarks,'Optional' => $optional,'hasParentCategory1' => $hasParentCategory1 ,'Offered' => $offered ,'checkAll' => $checkall ,
			  'actionString' => '<a href="#" title="Edit"><img src="'.IMG_HTTP_PATH.'/edit.gif" alt="Edit" onclick="editSubjectWindow('.$subjecttoclassRecordArray[$i]['subjectId'].');return false;" border="0"></a>&nbsp;&nbsp;<a href="#" title="Delete"><img src="'.IMG_HTTP_PATH.'/delete.gif" alt="Delete" onclick="return deleteSubject('.$subjecttoclassRecordArray[$i]['subjectId'].');return false;" border="0"></a>', 'action2' => $action2, 'srNo' => ($records+$i+1) ),$subjecttoclassRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"classId":"'.$classId.'","sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// $History: classSubjectsInitList.php $
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 1/29/10    Time: 3:41p
//Created in $/LeapCC/Library/PromoteStudentsAdvanced
//file added for new interface of session end activities
//



?>