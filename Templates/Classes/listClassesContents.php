<?php
//
//This file creates Html Form output in "Classes" Module
//
// Author :Ajinder Singh
// Created on : 19-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
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
                                <td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayFloatingDiv('AddClasses','', 600, 100, 250, 0);blankValues();document.getElementById('AddClasses').style.top='100px';return false;" title="Add" alt="Add"/>&nbsp;</td></tr>
                            <tr>
                                <td class="contenttab_row" colspan="2" valign="top" ><div id="results"></div></td>
                            </tr>
             <tr>
                                <td align="right" colspan="2">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                                            <td class="content_title" valign="middle" align="right" width="20%">
                                                <input type="image" title="Print"  src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();" >&nbsp;
                                                <input type="image" title="Export To Excel" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printCSV();" >
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

<?php floatingDiv_Start('AddClasses','Add Classes'); ?>
<form name="addClasses" action="" method="post" onSubmit="return false;">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
	<tr>
		<td class="contenttab_internal_rows" width="20%"><strong>Session<?php echo REQUIRED_FIELD; ?></strong></td>
		<td class="padding" width="30%">:&nbsp;
			<select name="sessionId" id="sessionId" style="width:142px" class="selectfield" >
				<option value="">select</option>
				<?php
				require_once(BL_PATH.'/HtmlFunctions.inc.php');
				echo HtmlFunctions::getInstance()->getSessionData();
				?>
			</select>
		</td>
		<td class="contenttab_internal_rows" width="20%"><strong>Batch<?php echo REQUIRED_FIELD; ?></strong></td>
		<td class="padding" width="30%">:&nbsp;
			<select name="batchId" id="batchId" style="width:142px" class="selectfield" >
				<option value="">select</option>
				<?php
				require_once(BL_PATH.'/HtmlFunctions.inc.php');
				echo HtmlFunctions::getInstance()->getBatches();
				?>
			</select>
		</td>
	</tr>
	<tr>
		<td class="contenttab_internal_rows"><strong>University<?php echo REQUIRED_FIELD; ?></strong></td>
		<td class="padding">:&nbsp;
			<select name="universityId" id="universityId" style="width:142px" class="selectfield" >
				<option value="">select</option>
				<?php
				require_once(BL_PATH.'/HtmlFunctions.inc.php');
				echo HtmlFunctions::getInstance()->getUniversityData('','universityCode');
				?>
			</select>
		</td>
		<td class="contenttab_internal_rows"><strong>Degree<?php echo REQUIRED_FIELD; ?></strong></td>
		<td  class="padding">:&nbsp;
			<select name="degreeId" id="degreeId" style="width:142px" class="selectfield" >
				<option value="">select</option>
				<?php
				require_once(BL_PATH.'/HtmlFunctions.inc.php');
				echo HtmlFunctions::getInstance()->getDegreeData('','degreeCode');
				?>
			</select>
		</td>
	</tr>
	<tr>
		<td class="contenttab_internal_rows"><strong>Branch<?php echo REQUIRED_FIELD; ?></strong></td>
		<td class="padding">:&nbsp;
			<select name="branchId" id="branchId" style="width:142px" class="selectfield" >
				<option value="">select</option>
				<?php
				require_once(BL_PATH.'/HtmlFunctions.inc.php');
				echo HtmlFunctions::getInstance()->getBranchData();
				?>
			</select>
		</td>
        <!-- Class having non integer duration(3 Sems for MPhil) -->
		<td class="contenttab_internal_rows"><strong>Degree Duration<?php echo REQUIRED_FIELD; ?></strong></td>
		<td  class="padding">:&nbsp;
			<select name="degreeDurationId" id="degreeDurationId" style="width:142px" class="selectfield" onBlur="hideClassDetails('add');">
				<option value="">select</option>
				<option value="1">1 year</option>
				<option value="2">2 year</option>
				<option value="3">3 year</option>
				<option value="4">4 year</option>
				<option value="5">5 year</option>
				<option value="6">6 year</option>
                <option value="1.5">1.5 year</option>
                <option value="2.5">2.5 year</option>
                <option value="3.5">3.5 year</option>
			</select>
		</td>
	</tr>
	<tr>
		<td class="contenttab_internal_rows"><strong>Periodicity<?php echo REQUIRED_FIELD; ?></strong></td>
		<td class="padding">:&nbsp;
			<select name="periodicityId" id="periodicityId" style="width:142px" class="selectfield" onBlur="hideClassDetails('add');">
				<option value="">select</option>
				<?php
				require_once(BL_PATH.'/HtmlFunctions.inc.php');
				echo HtmlFunctions::getInstance()->getPeriodicityData();
				?>
			</select></td>
		<td class="contenttab_internal_rows" colspan="2"><input type="image" name="spButton" src="<?php echo IMG_HTTP_PATH;?>/get_study_periods.gif" onClick="getClassDetails('add');" /></td>
	</tr>
	<tr>
		<td colspan='4' class='contenttab_internal_rows'>
			<div id="classDetailsDiv"></div>
		</td>
	</tr>
	<tr>
		<td valign="top" class="contenttab_internal_rows"><strong>Description</strong></td>
		<td colspan="3" class="padding">:&nbsp;
			<textarea name="description" id="description" rows="2" class="selectfield" style="width:440px;vertical-align:top"></textarea>
		</td>
	</tr>


	<tr>
		<td height="5px"></td></tr>
		<tr>
		<td align="center" style="padding-right:10px" colspan="4">
		<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
		<input type="image" name="Cancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddClasses');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
		</td>
	</tr>
	<tr>
		<td height="5px"></td>
	</tr>
</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->

<!--Start Add Div-->
<?php floatingDiv_Start('EditClasses','Edit Classes'); ?>
<form name="editClasses" action="" method="post" onSubmit="return false;">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
	<input type="hidden" name="classId" id="classId" value="" />
	<tr>
		<td class="contenttab_internal_rows" width="20%"><strong>Session<?php echo REQUIRED_FIELD; ?></strong></td>
		<td class="padding" width="30%">:&nbsp;
			<select name="sessionId" id="sessionId" style="width:142px" disabled>
				<option value="">select</option>
				<?php
				require_once(BL_PATH.'/HtmlFunctions.inc.php');
				echo HtmlFunctions::getInstance()->getSessionData();
				?>
			</select>
		</td>
		<td class="contenttab_internal_rows" width="20%"><strong>Batch<?php echo REQUIRED_FIELD; ?></strong></td>
		<td class="padding" width="30%">:&nbsp;
			<select name="batchId" id="batchId" style="width:142px" disabled>
				<option value="">select</option>
				<?php
				require_once(BL_PATH.'/HtmlFunctions.inc.php');
				echo HtmlFunctions::getInstance()->getBatches();
				?>
			</select>
		</td>
	</tr>
	<tr>
		<td class="contenttab_internal_rows"><strong>University<?php echo REQUIRED_FIELD; ?></strong></td>
		<td class="padding">:&nbsp;
			<select name="universityId" id="universityId" style="width:142px" disabled>
				<option value="">select</option>
				<?php
				require_once(BL_PATH.'/HtmlFunctions.inc.php');
				echo HtmlFunctions::getInstance()->getUniversityData('','universityCode');
				?>
			</select>
		</td>
		<td class="contenttab_internal_rows"><strong>Degree<?php echo REQUIRED_FIELD; ?></strong></td>
		<td  class="padding">:&nbsp;
			<select name="degreeId" id="degreeId" style="width:142px" disabled>
				<option value="">select</option>
				<?php
				require_once(BL_PATH.'/HtmlFunctions.inc.php');
				echo HtmlFunctions::getInstance()->getDegreeData('','degreeCode');
				?>
			</select>
		</td>
	</tr>
	<tr>
		<td class="contenttab_internal_rows"><strong>Branch<?php echo REQUIRED_FIELD; ?></strong></td>
		<td class="padding">:&nbsp;
			<select name="branchId" id="branchId" style="width:142px" disabled>
				<option value="">select</option>
				<?php
				require_once(BL_PATH.'/HtmlFunctions.inc.php');
				echo HtmlFunctions::getInstance()->getBranchData();
				?>
			</select>
		</td>
		<td class="contenttab_internal_rows"><strong>Degree Duration<?php echo REQUIRED_FIELD; ?></strong></td>
		<td  class="padding">:&nbsp;
			<select name="degreeDurationId" id="degreeDurationId" style="width:142px" onBlur="hideClassDetails('edit');"  disabled>
				<option value="">select</option>
				<option value="1">1 year</option>
				<option value="2">2 year</option>
				<option value="3">3 year</option>
				<option value="4">4 year</option>
				<option value="5">5 year</option>
				<option value="6">6 year</option>
                <option value="1.5">1.5 year</option>
                <option value="2.5">2.5 year</option>
                <option value="3.5">3.5 year</option>
			</select>
		</td>
	</tr>
	<tr>
		<td class="contenttab_internal_rows"><strong>Periodicity<?php echo REQUIRED_FIELD; ?></strong></td>
		<td  class="padding">:&nbsp;
			<select name="periodicityId" id="periodicityId" style="width:142px" onBlur="hideClassDetails('edit');"  disabled>
				<option value="">select</option>
				<?php
				require_once(BL_PATH.'/HtmlFunctions.inc.php');
				echo HtmlFunctions::getInstance()->getPeriodicityData();
				?>
			</select>
			</td>
			<!--<td class="contenttab_internal_rows" colspan="2"><input type="image" name="spButton" src="<?php echo IMG_HTTP_PATH;?>/get_study_periods.gif" onClick="getClassDetails('edit');"  disabled/></td>-->
	</tr>
	<tr>
		<td colspan='4' class=''>
			&nbsp;<div id="classDetailsDivEdit" ></div>
		</td>
	</tr>
	<tr>
		<td valign="top" class="contenttab_internal_rows"><strong>Description</strong></td>
		<td colspan="3" class="padding">:&nbsp;
			<textarea name="description" id="description" rows="2" class="selectfield" style="width:440px;vertical-align:top"></textarea>
		</td>
	</tr>
	<tr>
		<td height="5px"></td></tr>
		<tr>
		<td align="center" style="padding-right:10px" colspan="4">
		<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
		<input type="image" name="Cancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('EditClasses');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
		</td>
	</tr>
	<tr>
		<td height="5px"></td>
	</tr>
</table>
</form>
    <?php floatingDiv_End();
// $History: listClassesContents.php $
//
//*****************  Version 13  *****************
//User: Gurkeerat    Date: 12/17/09   Time: 10:19a
//Updated in $/LeapCC/Templates/Classes
//Updated breadcrumb according to the new menu structure
//
//*****************  Version 12  *****************
//User: Ajinder      Date: 8/14/09    Time: 11:21a
//Updated in $/LeapCC/Templates/Classes
//fixed bug no.1091
//
//*****************  Version 11  *****************
//User: Ajinder      Date: 8/05/09    Time: 3:49p
//Updated in $/LeapCC/Templates/Classes
//fixed bugs 901, 902
//
//*****************  Version 10  *****************
//User: Ajinder      Date: 8/05/09    Time: 3:19p
//Updated in $/LeapCC/Templates/Classes
//changed text 'Edit Class' to 'Edit Classes'
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 8/04/09    Time: 12:41p
//Updated in $/LeapCC/Templates/Classes
//make align left of sr. no. & give space between print & export to excel
//button
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 8/03/09    Time: 5:41p
//Updated in $/LeapCC/Templates/Classes
//fixed bug nos.0000602, 0000832, 0000831, 0000830
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 8/03/09    Time: 3:56p
//Updated in $/LeapCC/Templates/Classes
//fixed bug no.0000602
//
//*****************  Version 6  *****************
//User: Ajinder      Date: 7/29/09    Time: 3:43p
//Updated in $/LeapCC/Templates/Classes
//done the changes to fix bug no.s 754, 751
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 7/23/09    Time: 3:46p
//Updated in $/LeapCC/Templates/Classes
//done the changes to fix following bug no.s:
//1. 642
//2. 625
//3. 601
//4. 573
//5. 572
//6. 570
//7. 569
//8. 301
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 6/25/09    Time: 12:10p
//Updated in $/LeapCC/Templates/Classes
//fixed bug no.0000298
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 6/25/09    Time: 12:02p
//Updated in $/LeapCC/Templates/Classes
//fixed bugs nos.0000299, 000030, 000295
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 12/15/08   Time: 6:08p
//Updated in $/LeapCC/Templates/Classes
//done changes for class updation.
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Classes
//
//*****************  Version 7  *****************
//User: Ajinder      Date: 8/28/08    Time: 4:53p
//Updated in $/Leap/Source/Templates/Classes
//fixed html errors
//
//*****************  Version 6  *****************
//User: Ajinder      Date: 8/25/08    Time: 12:56p
//Updated in $/Leap/Source/Templates/Classes
//fixed:
//1. class initial sorting
//2. messageBox coming when editing class
//3. messageBox not coming while redundant data
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 8/20/08    Time: 2:34p
//Updated in $/Leap/Source/Templates/Classes
//changed search button
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 8/14/08    Time: 6:45p
//Updated in $/Leap/Source/Templates/Classes
//file modified for fixing bugs reported by pushpender sir
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 8/14/08    Time: 6:17p
//Updated in $/Leap/Source/Templates/Classes
//Removed Width and Height on cancel button
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 8/08/08    Time: 6:37p
//Updated in $/Leap/Source/Templates/Classes
//done the form formatting
//
//*****************  Version 1  *****************
//User: Admin        Date: 8/05/08    Time: 6:36p
//Created in $/Leap/Source/Templates/Classes
//file added for classes module
//

	?>
    <!--End: Div To Edit The Table-->