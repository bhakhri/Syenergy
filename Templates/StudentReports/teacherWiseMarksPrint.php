<?php 
//This file is used as printing version for testwise marks report.
//
// Author :Rajeev Aggarwal
// Created on : 14-Aug-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	UtilityManager::ifNotLoggedIn();
    UtilityManager::headerNoCache();

	require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
	$studentReportsManager = StudentReportsManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();

	$imageName = $REQUEST_DATA['imageName'];
	$degreeName = $REQUEST_DATA['degreeName'];
	$typeName = $REQUEST_DATA['typeName'];
	$groupName = $REQUEST_DATA['groupName'];
	$markName = $REQUEST_DATA['marksFor'];
	
	 $timeTable	   = $REQUEST_DATA['timeTable'];
	$degree		   = $REQUEST_DATA['degree'];
	$subjectTypeId = $REQUEST_DATA['subjectTypeId'];
	$subjectId     = $REQUEST_DATA['subjectId']; 
	$groupId       = $REQUEST_DATA['groupId']; 
	$marksFor      = $REQUEST_DATA['marksFor']; 
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
	 
?>
	<table border="0" cellspacing="0" cellpadding="0" width="90%" align="center">
	<tr>
		<td align="left" colspan="1" width="25%" class=""><?php echo $reportManager->showHeader();?></td>
		<th align="center" colspan="1" width="50%" <?php echo $reportManager->getReportTitleStyle();?>><?php echo $reportManager->getInstituteName(); ?></th>
		<td align="right" colspan="1" width="25%" class="">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td valign="" colspan="1" <?php echo $reportManager->getDateTimeStyle();?> align="right" width="50%">Date :&nbsp;</td><td valign="" colspan="1" <?php echo $reportManager->getDateTimeStyle();?>><?php echo date("d-M-y");?></td>
				</tr>
				<tr>
					<td valign="" colspan="1" <?php echo $reportManager->getDateTimeStyle();?> align="right">Time :&nbsp;</td><td valign="" colspan="1" <?php echo $reportManager->getDateTimeStyle();?>><?php echo date("h:i:s A");?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><th colspan="3" <?php echo $reportManager->getReportHeadingStyle(); ?> align="center">Subject Wise Consolidated Report</th></tr>
	 
	<tr><th colspan="3" <?php echo $reportManager->getReportInformationStyle(); ?>>For <B>Class:</B><?php echo $degreeName?> <B>Subject Type:</B> <?php echo $typeName?> <B>Subject :</B> <?php echo $subjectName?><B>Group :</B> <?php echo $groupName?><B>Marks :</B> <?php echo $markName?></th></tr>
	 
	</table> <br>
	<table width="80%" border="0" cellspacing="1" cellpadding="3"  id="anyid" class="timtd" align="center">
     <tr>
				<td valign="top" colspan="3">
				<table width="80%" cellspacing="3" cellpadding="0" border="0">
					<tr>
						<td width="22%" valign="middle" <?php echo $reportManager->getReportDataStyle()?>><b>Class Name</b></td> 
						<td colspan="2" valign="middle"  <?php echo $reportManager->getReportDataStyle()?>><?php echo $fetchMarksDetailArray[0]['className']?></td>  
						</tr>
						<tr>
						<td width="22%" valign="middle"  <?php echo $reportManager->getReportDataStyle()?>><b>Subject</b></td> 
						<td colspan="2" valign="middle"  <?php echo $reportManager->getReportDataStyle()?>><?php echo $fetchMarksDetailArray[0]['subjectName'].' ('.$fetchMarksDetailArray[0]['subjectCode']?>)</td>  
						</tr>
						<tr>
						<td width="22%" valign="middle"  <?php echo $reportManager->getReportDataStyle()?>><b>Subject Type</b></td> 
						<td colspan="2" valign="middle"  <?php echo $reportManager->getReportDataStyle()?>><?php echo $typeName ?></td>  
						</tr>
						<tr>
						<td width="22%" valign="middle"  <?php echo $reportManager->getReportDataStyle()?>><b>Group</b></td> 
						<td colspan="2" valign="middle"  <?php echo $reportManager->getReportDataStyle()?>><?php echo $groupName ?></td>  
						</tr>
						<tr>
						<td width="22%" valign="middle"  <?php echo $reportManager->getReportDataStyle()?>><b>Report For</b></td> 
						<td colspan="2" valign="middle"  <?php echo $reportManager->getReportDataStyle()?>><?php echo $reportFor ?></td>  
						</tr>
						<tr>
						<td width="22%" valign="middle"  <?php echo $reportManager->getReportDataStyle()?>><b>Faculty</b></td> 
						<td colspan="2" valign="middle"  <?php echo $reportManager->getReportDataStyle()?>><?php echo $teacher ?></td>  
						</tr>
				</table>
				</td>
			</tr>
			<tr class="rowheading">
				<td width="22%" valign="middle"  <?php echo $reportManager->getReportDataStyle()?>><b>&nbsp;</b> </td>
				<td width="12%" valign="middle"  <?php echo $reportManager->getReportDataStyle()?>><b>&nbsp;Total No</b></td>
				<td valign="middle" align="left" width="10%"  <?php echo $reportManager->getReportDataStyle()?>><b>Percentage</b></td> 
			</tr>
			<tr class="row1">
				<td width="22%" valign="middle"  <?php echo $reportManager->getReportDataStyle()?>><b>Total Students</b> </td>
				<td valign="middle" align="left" width="10%"  <?php echo $reportManager->getReportDataStyle()?>><?php echo $cnt1 ?></td> 
				<td width="12%" valign="middle"  <?php echo $reportManager->getReportDataStyle()?>><b>&nbsp;</b>
			<tr>
			<tr class="row0">
			<td width="22%" valign="middle"  <?php echo $reportManager->getReportDataStyle()?>><b>Total Appeared</b> </td>
			
			<td valign="middle" align="left" width="10%"  <?php echo $reportManager->getReportDataStyle()?>><?php echo $cnt2 ?></td> 
			<td width="12%" valign="middle"  <?php echo $reportManager->getReportDataStyle()?>>&nbsp;<?php echo number_format((($cnt2/$cnt1)*100), 2, '.', '') ?>
			<tr>

			<tr class="row1">
			<td width="22%" valign="middle"  <?php echo $reportManager->getReportDataStyle()?>><b>Total Absent</b> </td>
			
			<td valign="middle" align="left" width="10%"  <?php echo $reportManager->getReportDataStyle()?>><?php echo ($cnt1-$cnt2) ?></td> 
			<td width="12%" valign="middle"  <?php echo $reportManager->getReportDataStyle()?>>&nbsp;<?php echo number_format(((($cnt1-$cnt2)/$cnt1)*100), 2, '.', '') ?>
			<tr>
			<?php
				
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
				echo '<tr class="'.$bg.'">
				 
				<td width="22%" valign="middle" '.$reportManager->getReportDataStyle().'><b>'.$key.'</b></td>
				<td valign="middle" align="left" width="10%" '.$reportManager->getReportDataStyle().'>'.($value).'</td> 
				<td width="12%" valign="middle" '.$reportManager->getReportDataStyle().'>&nbsp;'.number_format(((($value)/$cnt2)*100), 2, '.', '').'
				<tr>'; 
				 
			
			}
			?>
	</table>
	
 

	<table border='0' cellspacing='0' cellpadding='0' width='70%' align='center'>
	<tr>
		<td valign='' align='center'><img src = "<?php echo IMG_HTTP_PATH ."/".$imageName;?>"/>
		
		</td>
	</tr>
	<tr>
		<td height="20"></td>
	</tr>
	<tr>
		<td valign='' align='left' <?php echo $reportManager->getFooterStyle();?>><?php echo $reportManager->showFooter();?></td>
	</tr>

	</table>
	
<?php
	}
//$History : listTestWiseMarksReportPrint.php $
//
?>