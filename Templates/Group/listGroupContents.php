   <?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR Group LISTING
//
//
// Author :&nbsp;Jaineesh
// Created on :&nbsp; (14.06.2008)
// Copyright 2008-2000:&nbsp; Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(TEMPLATES_PATH . "/breadCrumb.php");
 ?>
		<tr>
        <td valign="top" colspan="2">
				<table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
					<tr>
						<td valign="top" class="content">
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr height="30">
                                <td class="contenttab_border" height="20" style="border-right:0px;">
                                    <?php require_once(TEMPLATES_PATH . "/searchForm.php"); ?>
                                </td>
                                <td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddGroup',1530,1200);blankValues();return false;" />&nbsp;</td></tr>
                            <tr>
                                <td class="contenttab_row" colspan="2" valign="top" ><div id="results"></div></td>
                            </tr>
								<tr>
                                <td align="right" colspan="2">
										<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
											<tr>
                                            <td class="content_title" valign="middle" align="right" width="20%">
                                                <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();" >&nbsp;
                                                <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printCSV();" >
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
<!--Start Add Div-->

<?php floatingDiv_Start('AddGroup','Add Group'); ?>
<form name="addGroup" action="" method="post">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
		<tr>
			<td width="21%" class="contenttab_internal_rows"><nobr><b>Class<?php echo REQUIRED_FIELD; ?></b></nobr></td>
			<td width="79%" class="padding">:&nbsp;<select size="1" class="selectfield" style="width:250px" name="degree" id="degree" onchange="getGroupName();disableOptional();">
			<option value="">Select</option>
			<?php
				require_once(BL_PATH.'/HtmlFunctions.inc.php');
				//echo HtmlFunctions::&nbsp;getInstance()->getConcatenateClassData($REQUEST_DATA['degree']==''? $groupRecordArray[0]['classId'] :&nbsp; $REQUEST_DATA['className'] );
				echo HtmlFunctions::getInstance()->getClassWithStudyPeriod($REQUEST_DATA['degree']==''?$REQUEST_DATA['degree'] : $REQUEST_DATA['degree']);
			?>
			
			</select></td>
		</tr>

		<!--<tr>
			<td class="contenttab_internal_rows"><nobr><b>Batch<?php echo REQUIRED_FIELD; ?></b></nobr></td>
			<td class="padding">:&nbsp;<select size="1" class="selectfield" name="batch" id="batch">
			<option value="">Select</option>
			<?php
				require_once(BL_PATH.'/HtmlFunctions.inc.php');
				echo HtmlFunctions::getInstance()->getBatches($REQUEST_DATA['batch']==''? $groupRecordArray[0]['batchId'] : $REQUEST_DATA['batch'] );
			?>
			</select>
			</td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><nobr><b>Study Period<?php echo REQUIRED_FIELD; ?></b></nobr></td>
			<td class="padding">:&nbsp;<select size="1" class="selectfield" name="studyPeriod" id="studyPeriod">
			<option value="">Select</option>
			<?php
				require_once(BL_PATH.'/HtmlFunctions.inc.php');
				echo HtmlFunctions::getInstance()->getStudyPeriod($REQUEST_DATA['studyPeriod']==''? $groupRecordArray[0]['studyPeriodId'] : $REQUEST_DATA['studyPeriod'] );
			?>
			</select>
			</td>
		</tr>-->
		<tr>
			<td width="21%" class="contenttab_internal_rows"><nobr><b>Name<?php echo REQUIRED_FIELD; ?></b></nobr></td>
			<td width="79%" class="padding">:&nbsp;<input type="text" id="groupName" name="groupName" class="inputbox" style="width:245px" value="" maxlength="20" /></td>
		</tr>
		<tr>
			<td width="21%" class="contenttab_internal_rows"><nobr><b>Short Name<?php echo REQUIRED_FIELD; ?></b></nobr></td>
			<td width="79%" class="padding">:&nbsp;<input type="text" id="groupShort" name="groupShort" class="inputbox" style="width:245px" value="" maxlength="15"/></td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><b>Optional</b></td>
			<!--<td><input type="checkbox" id="optional" name="optional" onchange="checkParentGroup();" /></td>-->
			<td class="padding">:&nbsp;<input type="checkbox" id="optional" name="optional" onClick="checkParentGroup();checkStatus();" style="vertical-align:middle;" /></td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><nobr><b>Subject</b></nobr></td>
			<td class="padding">:&nbsp;<select size="1" class="selectfield" name="optionalSubject" id="optionalSubject" disabled="disabled" style="width:250px">
			</select>
			</td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><nobr><b>Parent Group</b></nobr></td>
			<td class="padding">:&nbsp;<select size="1" class="selectfield" name="parentGroup" id="parentGroup" style="width:250px">
			<option value="">Select</option>
			</select>
			</td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><nobr><b>Group Type Name<?php echo REQUIRED_FIELD; ?></b></nobr></td>
			<td class="padding">:&nbsp;<select size="1" class="selectfield" name="groupTypeName" id="groupTypeName" style="width:250px">
			<option value="">Select</option>
			<?php
				require_once(BL_PATH.'/HtmlFunctions.inc.php');
				echo HtmlFunctions::getInstance()->getGroupTypeData($REQUEST_DATA['groupTypeName']==''? $groupRecordArray[0]['groupTypeId'] : $REQUEST_DATA['groupTypeName'] );
			?>
			</select>
			</td>
		</tr>
		<tr>
			<td height="5px"></td></tr>
			<tr>
			<td align="center" style="padding-right:&nbsp;10px" colspan="2">
			<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;"  />
			<input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:&nbsp;hiddenFloatingDiv('AddGroup');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
			</td>
		</tr>
		<tr>
			<td height="5px"></td>
		</tr>
	</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->

<!--Start Edit Div-->
<?php floatingDiv_Start('EditGroup','Edit Group'); ?>
<form name="editGroup" action="" method="post">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
		<input type="hidden" name="groupId" id="groupId" value="" />
		<input type="hidden" name="classId" id="classId" value="" />
		<input type="hidden" name="optionValue" id="optionValue" value="" />
			<tr>
				<td width="21%" class="contenttab_internal_rows"><nobr><b>Class<?php echo REQUIRED_FIELD; ?></b></nobr></td>
				<td width="79%" class="padding">:&nbsp;<select size="1" class="selectfield" name="degree" id="degree" style="width:250px" onchange="disableEditOptional();getEditGroupName();">
				<option value="">Select</option>
				<?php
					require_once(BL_PATH.'/HtmlFunctions.inc.php');
					//echo HtmlFunctions::getInstance()->getConcatenateClassData($REQUEST_DATA['degree']==''? $groupRecordArray[0]['classId'] : $REQUEST_DATA['className'] );
					echo HtmlFunctions::getInstance()->getClassWithStudyPeriod($REQUEST_DATA['degree']==''?$REQUEST_DATA['degree'] :$REQUEST_DATA['degree']);?>
				?>
				</select></td>
			</tr>
			<!--<tr>
				<td class="contenttab_internal_rows"><nobr><b>Batch<?php echo REQUIRED_FIELD; ?> </b></nobr></td>
				<td class="padding">:<select size="1" class="selectfield" name="batch" id="batch">
				<option value="">Select</option>
				<?php
					require_once(BL_PATH.'/HtmlFunctions.inc.php');
					echo HtmlFunctions::getInstance()->getBatchData($REQUEST_DATA['batch']==''? $groupRecordArray[0]['batchId'] :$REQUEST_DATA['batch'] );
				?>
				</select>
				</td>
			</tr>
			<tr>
				<td class="contenttab_internal_rows"><nobr><b>Study Period <?php echo REQUIRED_FIELD; ?></b></nobr></td>
				<td class="padding">:<select size="1" class="selectfield" name="studyPeriod" id="studyPeriod">
				<option value="">Select</option>
				<?php
					require_once(BL_PATH.'/HtmlFunctions.inc.php');
					echo HtmlFunctions::getInstance()->getStudyPeriod($REQUEST_DATA['studyPeriod']==''? $groupRecordArray[0]['studyPeriodId'] : $REQUEST_DATA['studyPeriod'] );
				?>
				</select>
				</td>
			</tr>-->
			<tr>
				<td width="21%" class="contenttab_internal_rows"><nobr><b>Name<?php echo REQUIRED_FIELD; ?></b></nobr></td>
				<td width="79%" class="padding">:&nbsp;<input type="text" id="groupName" name="groupName" class="inputbox" style="width:245px" maxlength="20" value="" /></td>
			</tr>
			<tr>
				<td width="21%" class="contenttab_internal_rows"><nobr><b>Short Name<?php echo REQUIRED_FIELD; ?> </b></nobr></td>
				<td width="79%" class="padding">:&nbsp;<input type="text" id="groupShort" name="groupShort" class="inputbox" style="width:245px" maxlength="15" value="" /></td>
			</tr>
			<tr>
				<td class="contenttab_internal_rows"><b>Optional</b></td>
				<!--<td><input type="checkbox" id="editOptional" name="editOptional" onchange="checkEditParentGroup();" /></td>-->
				<td class="padding">:&nbsp;<input type="checkbox" id="editOptional" name="editOptional" onclick="checkEditParentGroup();checkEditStatus();" style="vertical-align:middle;"/></td>
			</tr>
			<tr>
				<td class="contenttab_internal_rows"><nobr><b>Subject</b></nobr></td>
				<td class="padding">:&nbsp;<select size="1" class="selectfield" name="optionalSubject" id="optionalSubject" disabled="disabled" style="width:250px">
				</select>
				</td>
			</tr>
			<tr>
				<td class="contenttab_internal_rows"><nobr><b>Parent Group </b></nobr></td>
				<td class="padding">:&nbsp;<select size="1" class="selectfield" name="parentGroup" id="parentGroup" style="width:250px">
				<option value="">Select</option>
				</select>
				</td>
			</tr>
			<tr>
				<td class="contenttab_internal_rows"><nobr><b>Group Type Name<?php echo REQUIRED_FIELD; ?> </b></nobr></td>
				<td class="padding">:&nbsp;<select size="1" class="selectfield" name="groupTypeName" id="groupTypeName" style="width:250px">
				<option value="">Select</option>
				<?php
					require_once(BL_PATH.'/HtmlFunctions.inc.php');
					echo HtmlFunctions::getInstance()->getGroupTypeData($REQUEST_DATA['groupTypeName']==''? $groupRecordArray[0]['groupTypeId'] : $REQUEST_DATA['groupTypeName'] );
				?>
				</select>
				</td>
			</tr>
			<tr>
				<td height="5px"></td>
			</tr>
			<tr>
				<td align="center" style="padding-right:10px" colspan="2">
				<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
				<input type="image" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"
				onclick="javascript:hiddenFloatingDiv('EditGroup'); return false;" />
				</td>
			</tr>
			<tr>
				<td height="5px"></td>
			</tr>
    </table>
</form>
    <?php floatingDiv_End(); ?>
    <!--End: Div To Edit The Table-->

<?php
    // $History: listGroupContents.php $
//
//*****************  Version 13  *****************
//User: Jaineesh     Date: 4/13/10    Time: 4:45p
//Updated in $/LeapCC/Templates/Group
//fixed error in IE explorer
//
//*****************  Version 12  *****************
//User: Jaineesh     Date: 4/13/10    Time: 4:20p
//Updated in $/LeapCC/Templates/Group
//add field optional subjet for optional group
//
//*****************  Version 11  *****************
//User: Jaineesh     Date: 12/09/09   Time: 10:42a
//Updated in $/LeapCC/Templates/Group
//increase the length of short name
//
//*****************  Version 10  *****************
//User: Dipanjan     Date: 8/26/09    Time: 2:58p
//Updated in $/LeapCC/Templates/Group
//Gurkeerat: resolved issue 1252
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 8/26/09    Time: 12:48p
//Updated in $/LeapCC/Templates/Group
//Gurkeerat: fixed issue 1247, 1248
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 8/17/09    Time: 12:25p
//Updated in $/LeapCC/Templates/Group
//show classes in drop down instead of degree, batch & study period
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 8/04/09    Time: 11:06a
//Updated in $/LeapCC/Templates/Group
//make space between print & export to excel button and sr. no. make left
//align
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 8/03/09    Time: 2:08p
//Updated in $/LeapCC/Templates/Group
//fixed bug no.0000838
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 7/31/09    Time: 6:32p
//Updated in $/LeapCC/Templates/Group
//make another function getBatches() instead of getBatchData()
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 7/28/09    Time: 6:40p
//Updated in $/LeapCC/Templates/Group
//fixed bug nos.0000574, 0000575, 0000576, 0000577, 0000578, 0000579,
//0000580, 0000581
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 6/11/09    Time: 3:52p
//Updated in $/LeapCC/Templates/Group
//added optional field functionality
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 6/10/09    Time: 10:56a
//Updated in $/LeapCC/Templates/Group
//put new field isOptional & check conditions
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Group
//
//*****************  Version 15  *****************
//User: Jaineesh     Date: 9/20/08    Time: 3:02p
//Updated in $/Leap/Source/Templates/Group
//fixed the bug
//
//*****************  Version 14  *****************
//User: Jaineesh     Date: 9/15/08    Time: 6:39p
//Updated in $/Leap/Source/Templates/Group
//modification for validations
//
//*****************  Version 13  *****************
//User: Jaineesh     Date: 8/29/08    Time: 3:42p
//Updated in $/Leap/Source/Templates/Group
//modification in indentation
//
//*****************  Version 12  *****************
//User: Jaineesh     Date: 8/28/08    Time: 1:20p
//Updated in $/Leap/Source/Templates/Group
//modified in indentation
//
//*****************  Version 11  *****************
//User: Jaineesh     Date: 8/27/08    Time: 11:37a
//Updated in $/Leap/Source/Templates/Group
//modified in html
//
//*****************  Version 10  *****************
//User: Jaineesh     Date: 8/19/08    Time: 1:49p
//Updated in $/Leap/Source/Templates/Group
//changed search button
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 8/14/08    Time: 7:20p
//Updated in $/Leap/Source/Templates/Group
//remove width & height of cancel button
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 8/12/08    Time: 10:31a
//Updated in $/Leap/Source/Templates/Group
//modified in bread crump
//
//*****************  Version 7  *****************
//User: Ajinder      Date: 7/29/08    Time: 6:27p
//Updated in $/Leap/Source/Templates/Group
//modified in parent group name
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 7/29/08    Time: 5:04p
//Updated in $/Leap/Source/Templates/Group
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 7/19/08    Time: 6:42p
//Updated in $/Leap/Source/Templates/Group
//modification in sorting field of group name
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 7/17/08    Time: 8:06p
//Updated in $/Leap/Source/Templates/Group
//modified for add & edit
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 7/15/08    Time: 6:04p
//Updated in $/Leap/Source/Templates/Group
//modified for parent group id
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 7/05/08    Time: 11:16a
//Updated in $/Leap/Source/Templates/Group
//modified with group template
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 7/03/08    Time: 7:07p
//Created in $/Leap/Source/Templates/Group
//containing template for group
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 7/01/08    Time: 9:31a
//Updated in $/Leap/Source/Templates/Periods
//modification with cancel image
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 6/30/08    Time: 1:14p
//Updated in $/Leap/Source/Templates/Periods
//modification with ajax functions
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 6/25/08    Time: 5:27p
//Updated in $/Leap/Source/Templates/Periods
//giving title delete
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 6/25/08    Time: 1:24p
//Updated in $/Leap/Source/Templates/Periods
//modified in design & coding for delete function
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 6/19/08    Time: 3:25p
//Updated in $/Leap/Source/Templates/Periods
?>