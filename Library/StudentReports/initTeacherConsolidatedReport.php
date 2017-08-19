<?php
//--------------------------------------------------------
//This file returns the array of subjects, based on class
//
// Author :Rajeev Aggarwal
// Created on : 13-Aug-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','COMMON');
    define('ACCESS','view');
    define("MANAGEMENT_ACCESS",1);
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

	require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
	$studentReportsManager = StudentReportsManager::getInstance();

	$timeTable	   = $REQUEST_DATA['timeTable'];
	$degree		   = $REQUEST_DATA['degree'];
	$subjectTypeId = $REQUEST_DATA['subjectTypeId'];
	$subjectId     = $REQUEST_DATA['subjectId']; 
	$groupId       = $REQUEST_DATA['groupId']; 
	 
	$marksFor      = $REQUEST_DATA['marksFor']; 
	$typeName      = $REQUEST_DATA['typeName']; 
	$groupName      = $REQUEST_DATA['groupName']; 
	if($marksFor==1)
		$reportFor = " Internal consolidated Report";
	else if($marksFor==2)
		$reportFor = " External consolidated Report";
	else 
		$reportFor = " Consolidated Report (Internal and External)";

	$conditions	   = " AND c.classId=$degree"; 
	if($timeTable){
	
		//$conditions	   .= " AND dcd.timeTableLabelId = $timeTable"; 
		$teacherCondition .= " AND tt.timeTableLabelId = $timeTable"; 
	}
	
	if($subjectId){
	
		$conditions	   .= " AND sub.subjectId = $subjectId"; 
		$teacherCondition .= " AND tt.subjectId = $subjectId"; 
	}
	if($groupId){
	
		$conditions	   .= " AND sg.groupId = $groupId"; 
		$teacherCondition .= " AND tt.groupId = $groupId"; 
	}
	if($subjectTypeId){
	
		$conditions	   .= " AND sub.subjectTypeId = $subjectTypeId"; 
	}
	if($marksFor==1){
	
		$conditions1	= " AND ttm.conductingAuthority IN (1,3) "; 
	}
	if($marksFor==2){
	
		$conditions1	= " AND ttm.conductingAuthority IN (2) "; 
	}
	$condition2 = " AND marksScoredStatus='Marks'" ;

    
   
	$fetchMarksDetailArray = $studentReportsManager->getConsolidatedTeacherDetails($conditions.$conditions1.$condition2); 
	$cnt2 = count($fetchMarksDetailArray);
    
    $studentDetailArray = $studentReportsManager->getConsolidatedTeacherDetails($conditions.$conditions1); 
	$cnt1 = count($studentDetailArray);
	if($cnt1){
	//echo $teacherCondition;
	$teacherArray = $studentReportsManager->getTeachers($teacherCondition); 
	//print_r($teacherArray);
	$cnt3 = count($teacherArray);
	$teacher = "";
	for($i=0;$i<$cnt3;$i++){

		$querySeprator = '';
		if($teacher!=''){

			$querySeprator = ", ";
		}
				
		$teacher .= $querySeprator.$teacherArray[$i]['employeeName'];
	}
	$timeTableStr="";
	//build the string
	$timeTableStr='<table width="80%" border="0" cellspacing="1" cellpadding="3"  id="anyid" class="timtd" align="center">';
		$timeTableStr .= '<tr>
				<td valign="top" colspan="3">
				<table width="80%" cellspacing="3" cellpadding="0" border="0">
					<tr>
						<td width="22%" valign="middle" style="font-size:12px"><b>Class Name</b></td> 
						<td colspan="2" valign="middle" style="font-size:12px">'.$fetchMarksDetailArray[0]['className'].'</td>  
						</tr>
						<tr>
						<td width="22%" valign="middle" style="font-size:12px"><b>Subject</b></td> 
						<td colspan="2" valign="middle" style="font-size:12px">'.$fetchMarksDetailArray[0]['subjectName'].' ('.$fetchMarksDetailArray[0]['subjectCode'].')</td>  
						</tr>
						<tr>
						<td width="22%" valign="middle" style="font-size:12px"><b>Subject Type</b></td> 
						<td colspan="2" valign="middle" style="font-size:12px">'.$typeName.'</td>  
						</tr>
						<tr>
						<td width="22%" valign="middle" style="font-size:12px"><b>Group</b></td> 
						<td colspan="2" valign="middle" style="font-size:12px">'.$groupName.'</td>  
						</tr>
						<tr>
						<td width="22%" valign="middle" style="font-size:12px"><b>Report For</b></td> 
						<td colspan="2" valign="middle" style="font-size:12px">'.$reportFor.'</td>  
						</tr>
						<tr>
						<td width="22%" valign="middle" style="font-size:12px"><b>Faculty</b></td> 
						<td colspan="2" valign="middle" style="font-size:12px">'.$teacher.'</td>  
						</tr>
				</table>
				</td>
			</tr>
			<tr class="rowheading">
			<td width="22%" valign="middle" style="font-size:12px"><b>&nbsp;</b> </td>
			<td width="12%" valign="middle" style="font-size:12px"><b>&nbsp;Total No</b></td>
			<td valign="middle" align="left" width="10%" style="font-size:12px"><b>Percentage</b></td> 
			</tr>
			<tr class="row1">
			<td width="22%" valign="middle" style="font-size:12px"><b>Total Students</b> </td>
			
			<td valign="middle" align="left" width="10%" style="font-size:12px">'.$cnt1.'</td> 
			<td width="12%" valign="middle" style="font-size:12px"><b>&nbsp;</b>
			<tr>
			<tr class="row0">
			<td width="22%" valign="middle" style="font-size:12px"><b>Total Appeared</b> </td>
			
			<td valign="middle" align="left" width="10%" style="font-size:12px">'.$cnt2.'</td> 
			<td width="12%" valign="middle" style="font-size:12px">&nbsp;'.number_format((($cnt2/$cnt1)*100), 2, '.', '').'
			<tr>

			<tr class="row1">
			<td width="22%" valign="middle" style="font-size:12px"><b>Total Absent</b> </td>
			
			<td valign="middle" align="left" width="10%" style="font-size:12px">'.($cnt1-$cnt2).'</td> 
			<td width="12%" valign="middle" style="font-size:12px">&nbsp;'.number_format(((($cnt1-$cnt2)/$cnt1)*100), 2, '.', '').'
			<tr>
			';
			//print_r($fetchMarksDetailArray);
			$divisionName='';
			for($k=0;$k<$cnt2;$k++){

				$divisionName1 = $fetchMarksDetailArray[$k]['divName'];
				if(($divisionName1==$divisionName)){

					$count++;
					$array1[$divisionName1]=$count;
					$divisionName1 = "";
				}
				else{
					$count=1;
					$array1[$divisionName1]=$count;
				}
				
				if($divisionName1 != "")
					$divisionName = $divisionName1;
			}

			//print_r($array1);
			//die();
			foreach($array1 as $key=>$value){
			
				$bg = $bg =='row0' ? 'row1' : 'row0';
				$timeTableStr .= '<tr class="'.$bg.'">
				 
				<td width="22%" valign="middle" style="font-size:12px"><b>'.$key.'</b></td>
				<td valign="middle" align="left" width="10%" style="font-size:12px">'.($value).'</td> 
				<td width="12%" valign="middle" style="font-size:12px">&nbsp;'.number_format(((($value)/$cnt2)*100), 2, '.', '').'
				<tr>'; 
				 
			
			}

	 

	$classId = $REQUEST_DATA['degree'];
	$subjectTypeId = $REQUEST_DATA['subjectTypeId'];
	$subjectId = $REQUEST_DATA['subjectId'];
	$groupId = $REQUEST_DATA['groupId'];

	$condition = ' AND ttm.classId ='.$classId.' AND s.subjectTypeId ='.$subjectTypeId.' AND ttl.timeTableLabelId ='.$timeTable;
	if($subjectId)
		$condition .=' AND s.subjectId ='.$subjectId;
	if($groupId)
		$condition .=' AND sg.groupId ='.$groupId;

	$intervalArr = array("0-25","26-30","31-35","36-40","41-45","46-50","51-55","56-60","61-65","66-70","71-75","76-80","81-85","86-90","91-95","96-100");
	$countInterval = count($intervalArr);
	$foundArray = $studentReportsManager->getSubjectPercentageInternalMarks($condition);
	$cnt = count($foundArray);
	$strList ='';
	if($cnt){

		$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<chart>\n";
		$strList .="<series>\n\t";
		for($k=0;$k<$countInterval;$k++){
		
			$strList .="<value xid='".$k."'>".$intervalArr[$k]."</value>\n\t";
		} 
		$strList .="\n</series><graphs>";

		for($i=0;$i<$cnt;$i++) {
			
			$strList .="\n\t<graph gid='".$foundArray[$i]['subjectCode']."' title='".$foundArray[$i]['subjectCode']."'>";
			for($j=0;$j<$countInterval;$j++) {

				$strList .= "\n\t\t<value xid='".$j."' url='javascript:showData(\"".$intervalArr[$j]."\",\"".$foundArray[$i]['subjectId']."\",\"".$classId."\",\"".$subjectTypeId."\")'>".$foundArray[$i]['total'.$j]."</value>";
			}
			$strList .="\n\t</graph>\n";
		} 
		 
		$strList .="\n</graphs>\n</chart>";
	} 
	$xmlFilePath = TEMPLATES_PATH."/Xml/teacherConsolidatedData.xml";
	if(is_writable($xmlFilePath)){

		$handle = @fopen($xmlFilePath, 'w');
		if (@fwrite($handle, $strList) === FALSE){
			die("unable to write");
		}
	}
	else{
		logError("unable to open user activity data xml file");
	}
	 
		

	echo SUCCESS."~".$timeTableStr;
	die();
}
echo FAILURE."~";
// $History: initTeacherConsolidatedReport.php $
//
//*****************  Version 4  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:12p
//Updated in $/LeapCC/Library/StudentReports
//added access defines for management login
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 09-09-03   Time: 1:07p
//Updated in $/LeapCC/Library/StudentReports
//0001439: Teacher Consolidated Reports (Admin) > No action performed as
//clicked on Show List button. 
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/10/09    Time: 5:38p
//Updated in $/LeapCC/Library/StudentReports
//Gurkeerat: updated access defines
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 4/30/09    Time: 7:11p
//Created in $/LeapCC/Library/StudentReports
//intial checkin
 
?>