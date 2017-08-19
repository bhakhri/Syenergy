<?php 
//This file creates Html Form output "ListStudentLabelContents " Module 
//
// Author :Ajinder Singh
// Created on : 15-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td valign="top" class="title">
			<table border="0" cellspacing="0" cellpadding="0" width="100%">
				<tr>
					<td height="10"></td>
				</tr>
				<tr>
					<td valign="top" colspan="2">Reports&nbsp;&raquo;&nbsp; Students &nbsp;&raquo;&nbsp;Student Labels </td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td valign="top">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
				<tr>
					<td valign="top" class="content">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td class="contenttab_border" height="20">
									<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
										<tr>
											<td colspan="2" class="content_title">Student Labels Report :</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td valign="top" class="contenttab_row">
									<form name="studentLabelReport" action="" method="post">
										<table width="90%" align="center" border="0" >
											<tr>
												<td class="padding" colspan="1" width='15%' align="right">
													<strong>Degree :</strong> &nbsp;
												</td>
												<td class="padding" colspan="1" width='15%' align="left">
													<select size="1" class="selectfield" name="degree" id="degree" style="width:140px">
														<option value="">Select</option>
														<?php 
															require_once(BL_PATH.'/HtmlFunctions.inc.php');
															echo HtmlFunctions::getInstance()->getConcatenateClassData($REQUEST_DATA['degree']==''?$REQUEST_DATA['degree'] : $REQUEST_DATA['degree']);?>
													</select>
												</td>
												<td class="padding" colspan="1" width='15%' align="right">
													<b>Batch : </b>&nbsp;
												</td>
												<td class="padding" colspan="1" width='15%' align="left">
													<select size="1" class="selectfield" name="batchId" id="batchId" style="width:140px">
														<option value="">Select</option>
														<?php 
															require_once(BL_PATH.'/HtmlFunctions.inc.php');
															echo HtmlFunctions::getInstance()->getBatchData($REQUEST_DATA['batchId']==''? $REQUEST_DATA['batchId'] : $REQUEST_DATA['batchId'] );?>
													</select>
												</td>
												<td class="padding" colspan="1" width='15%' align="right">&nbsp;<strong>Study Period :</strong>&nbsp;
												</td>
												<td class="padding" colspan="1" width='15%' align="left">
													<select name="studyPeriodId"  class="selectfield" id="studyPeriodId" style="width:140px">
														<option value="">Select</option>
														<?php 
															require_once(BL_PATH.'/HtmlFunctions.inc.php');
															echo HtmlFunctions::getInstance()->getStudyPeriodData($REQUEST_DATA['studyPeriodId']==''? $studyPeriodRecordArray[0]['studyPeriodId'] : $REQUEST_DATA['studyPeriodId'] );?>
													</select>
												</td>
												<td colspan="2" ><input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/generate.gif" onClick="return validateAddForm(this.form);return false;" /></td>
											</tr>
											</table>
										</form>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</table>

<?php 
//$History: listStudentLabelContents.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/StudentReports
//
//*****************  Version 6  *****************
//User: Ajinder      Date: 8/26/08    Time: 1:18p
//Updated in $/Leap/Source/Templates/StudentReports
//removed  word following "select"  keyword from dropdown boxes
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 8/25/08    Time: 6:20p
//Updated in $/Leap/Source/Templates/StudentReports
//improved page designing.
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 8/23/08    Time: 10:32a
//Updated in $/Leap/Source/Templates/StudentReports
//working on designing part of page
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 8/18/08    Time: 3:02p
//Updated in $/Leap/Source/Templates/StudentReports
//removed cancel button
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 8/14/08    Time: 6:17p
//Updated in $/Leap/Source/Templates/StudentReports
//Removed Width and Height on cancel button
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 7/17/08    Time: 10:45a
//Created in $/Leap/Source/Templates/StudentReports
//file added for : creating studentLabels report
//
//*****************  Version 1  *****************
//User: Ajinder       Date: 7/15/08    Time: 12:45p
//Created in $/Leap/Source/Templates/StudentReports
//added a new file for StudentLablesReport  Module
?>
