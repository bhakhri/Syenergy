<?php

//-------------------------------------------------------
// Purpose: to design the layout for student Information report.
//
// Author : Jaineesh
// Created on : (27.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
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
					<td valign="top">Reports&nbsp;&raquo;&nbsp;Student&nbsp;&raquo;&nbsp;Student Report</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td valign="top">
			<form name="studentInformation" method="post" action="">
				<table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
					<tr>
						<td valign="top" class="content">
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td>
										<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="contenttab_border">
											<tr>
												<td colspan="2" class="content_title">Student Identificaiton Report : </td>
												<td colspan="1" class="content_title" align="right"></td>
											</tr>
										</table>
										<table width="100%" border="0" cellspacing="0" cellpadding="0" id="anyid" class="contenttab_border2">
											<tr>
												<td valign="top" class="contenttab_row1">
													<table width="100%" align="center" border="0" >
														<tr>
															<td width="10%" class="padding" align="right"><nobr><b>Degree : </b></nobr></td>
															<td width="15%" class="padding" align="left">
																<select size="1" class="htmlElement" name="degree" id="degree">
																	<option value="select">Select</option>
																	<?php
																		require_once(BL_PATH.'/HtmlFunctions.inc.php');
																		echo HtmlFunctions::getInstance()->getConcatenateClassData($REQUEST_DATA['degree']==''? $groupRecordArray[0]['classId'] : $REQUEST_DATA['className'] );
																	?>
																</select>
															</td>
															<td width="10%" class="contenttab_internal_rows" align="right">
																<nobr><b>Batch : </b></nobr>
															</td>
															<td class="padding"  width="15%">
																<select size="1" class="htmlElement" name="batch" id="batch">
																	<option value="select">Select</option>
																	<?php 
																		require_once(BL_PATH.'/HtmlFunctions.inc.php'); 
																		echo HtmlFunctions::getInstance()->getBatchData($REQUEST_DATA['batch']==''? $groupRecordArray[0]['batchId'] : $REQUEST_DATA['batch'] );
																	?>
																</select>
															</td>
															<td class="contenttab_internal_rows" width="10%" align="right">
																<nobr><b>Study Period : </b></nobr>
															</td>
															<td class="padding" width="15%" align="left">
																<select size="1" class="htmlElement" name="studyPeriod" id="studyPeriod">
																	<option value="select">Select</option>
																	<?php 
																		require_once(BL_PATH.'/HtmlFunctions.inc.php');
																		echo HtmlFunctions::getInstance()->getStudyPeriod($REQUEST_DATA['studyPeriod']==''? $groupRecordArray[0]['studyPeriodId'] : $REQUEST_DATA['studyPeriod'] );
																	?>
																</select>
															</td>
															<td width="10%" align="right" class="contenttab_internal_rows">
																<nobr><b>Roll No. : </b></nobr>
															</td>
															<td width="15%" class="padding" align="left">
																<input type="text" id="rollNo" name="rollNo" class="htmlElement"  value=""/>
															</td>
														</tr>
													</table>
												</td>
											</tr>
											<tr>
												<td valign="top" align="center">
													<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
														<tr>
															<td align="center">
																<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" >
																	<tr>
																		<td class="contenttab_internal_rows1" width="15%" align="right">
																			<input type="radio" name="radioStudent" id="radioStudent" value="admitCard" />&nbsp;<label onClick="document.studentInformation.radioStudent[0].checked=true"><b>Admit Card</b></label>
																		</td>

																		<td class="contenttab_internal_rows1" width="15%" align="right">
																			<input type="radio" name="radioStudent" id="radioStudent"  value="iCard" class="contenttab_internal_rows"/>&nbsp;<label onClick="document.studentInformation.radioStudent[1].checked=true"><strong>I-Card</strong></label>
																		</td>
																		<td class="contenttab_internal_rows1" width="15%" align="right">
																			<input type="radio" name="radioStudent" id="radioStudent" value="busPass" class="contenttab_internal_rows" />&nbsp;<label onClick="document.studentInformation.radioStudent[2].checked=true"><strong>Bus Pass</strong></label>
																		</td>
																		<td class="contenttab_internal_rows1" width="15%" align="right">
																			<input type="radio" name="radioStudent" id="radioStudent" value="hostelCard" />&nbsp;<label onClick="document.studentInformation.radioStudent[3].checked=true"><strong>Hostel Card</strong></label>
																		</td>
																		<td class="contenttab_internal_rows1" width="15%" align="right">
																			<input type="radio" name="radioStudent" id="radioStudent" value="libraryCard" />&nbsp;<label onClick="document.studentInformation.radioStudent[4].checked=true"><strong>Library Card</strong></label>
																		</td>
																		<td class="contenttab_internal_rows1" width="15%" align="right">
																			<input type="radio" name="radioStudent" id="radioStudent" value="photoGallery" />&nbsp;<label onClick="document.studentInformation.radioStudent[5].checked=true"><strong>Photo Gallery</strong></label>&nbsp;
																		</td>
																		<td valign='middle' class='' width='10%'>
																			<input type="image" name="generate" src="<?php echo IMG_HTTP_PATH;?>/generate.gif" onclick="checkForm();return false;"/>
																		</td>
																	</tr>
																</table>
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</form>


<?php 
//$History: studentIdentificationReport.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/StudentReports
//
//*****************  Version 6  *****************
//User: Ajinder      Date: 8/22/08    Time: 6:19p
//Updated in $/Leap/Source/Templates/StudentReports
//improved the designing part of report
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 8/18/08    Time: 5:24p
//Updated in $/Leap/Source/Templates/StudentReports
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 8/18/08    Time: 3:43p
//Updated in $/Leap/Source/Templates/StudentReports
//modification in template
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 8/16/08    Time: 5:47p
//Updated in $/Leap/Source/Templates/StudentReports
//modification in student report
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 7/15/08    Time: 2:12p
//Updated in $/Leap/Source/Templates/StudentReports
//remove bread crum
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 7/10/08    Time: 11:06a
//Created in $/Leap/Source/Templates/Reports
//templates for student icard, bus pass, hostel card, admit card, photo
//gallery, library card
?>