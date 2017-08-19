<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR discipline LISTING 
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
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
								<td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddDiscipline',320,250);blankValues();return false;   " />&nbsp;</td></tr>
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

<?php floatingDiv_Start('AddDiscipline','Add Discipline Record'); ?>
    <form name="AddDiscipline" action="" method="post">  
    <input type="hidden" name="studentId" id="studentId" value="" />
    <input type="hidden" name="classId" id="classId" value="" />
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Roll No.<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding"><b>:</b></td>
        <td width="79%" class="padding">
         <input type="text" id="studentRollNo" name="studentRollNo" class="inputbox" onchange="getStudent(this.value,'Add');" autocomplete='off' onkeydown="return sendKeys(1,'studentRollNo',event);" />
        </td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Name </b></nobr></td>
        <td class="padding"><b>:</b></td>
        <td width="79%" class="padding">
          <div name="studentName1" id="studentName1" style="display:inline;padding:5px" />
        </td>
   </tr>
   <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Class</b></nobr></td>
        <td class="padding"><b>:</b></td>
        <td width="79%" class="padding">
          <div name="className1" id="className1" style="display:inline;padding:5px" />
        </td>
   </tr>
   <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Offense<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding"><b>:</b></td>
        <td width="79%" class="padding">
         <select size="1" class="selectfield" name="offenseId" id="offenseId">
         <option value="">Select</option>
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getOffenceData();
              ?>
        </select>
    </td>
 </tr>
 <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Date<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding"><b>:</b></td>
        <td width="79%" class="padding">
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->datePicker('disciplineDate1',date('Y-m-d'));
              ?>
      </td>
 </tr>
 <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Remarks<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding"><b>:</b></td>
        <td width="79%" class="padding">
             <textarea name="remarksTxt" id="remarksTxt" cols="20" rows="3" maxlength="250" onkeyup="return ismaxlength(this)"></textarea>
      </td>
 </tr>
  <tr>
	<td width="21%" class="contenttab_internal_rows"><nobr><b>Reported By<?php echo REQUIRED_FIELD; ?></b></nobr></td>
	<td class="padding"><b>:</b></td>
    <td width="79%" class="padding">
	  <input type="text" name="reportedBy" id="reportedBy" class="inputbox" maxlength="50" onkeydown="return sendKeys(1,'reportedBy',event);"/>
	</td>
</tr>
<tr><td height="5px" colspan="3"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="3">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
       <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddDiscipline');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
        </td>
</tr>
<tr><td height="5px" colspan="3"></td></tr>
</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->

<!--Start Edit Div-->
<?php floatingDiv_Start('EditDiscipline','Edit Discipline Record '); ?>
 <form name="EditDiscipline" action="" method="post">  
    <input type="hidden" name="disciplineId" id="disciplineId" value="" />
    <input type="hidden" name="studentId" id="studentId" value="" />
    <input type="hidden" name="classId" id="classId" value="" />
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Roll No.<?php echo REQUIRED_FIELD; ?>: </b></nobr></td>
        <td class="padding"><b>:</b></td>
        <td width="79%" class="padding">
        <input type="text" id="studentRollNo" name="studentRollNo" class="inputbox" readonly="readonly" onkeydown="return sendKeys(2,'studentRollNo',event);"/></td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Name</b></nobr></td>
        <td class="padding"><b>:</b></td>
        <td width="79%" class="padding">
          <div name="studentName2" id="studentName2" style="display:inline:padding-5px;"></div>
        </td>
   </tr>
   <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Class</b></nobr></td>
        <td class="padding"><b>:</b></td>
        <td width="79%" class="padding">
          <div name="className2" id="className2" style="display:inline:padding-5px;"></div>
        </td>
   </tr>
   <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Offense<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding"><b>:</b></td>
        <td width="79%" class="padding">
         <select size="1" class="selectfield" name="offenseId" id="offenseId">
         <option value="">Select</option>
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getOffenceData();
              ?>
        </select>
    </td>
 </tr>
 <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Date<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding"><b>:</b></td>
        <td width="79%" class="padding">
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->datePicker('disciplineDate2',date('Y-m-d'));
              ?>
      </td>
 </tr>
 <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Remarks<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding"><b>:</b></td>
        <td width="79%" class="padding">
             <textarea name="remarksTxt" id="remarksTxt" cols="20" rows="3" maxlength="250" onkeyup="return ismaxlength(this)"></textarea>
      </td>
 </tr>
  <tr>
	<td width="21%" class="contenttab_internal_rows"><nobr><b>Reported By<?php echo REQUIRED_FIELD; ?></b></nobr></td>
	<td class="padding"><b>:</b></td>
    <td width="79%" class="padding">
	  <input type="text" name="reportedBy" id="reportedBy" class="inputbox" onkeydown="return sendKeys(2,'reportedBy',event);" />
	</td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="3">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
       <input type="image" name="editCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('EditDiscipline');return false;" />
        </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
</table>
</form>
    <?php floatingDiv_End(); ?>
    <!--End: Div To Edit The Table-->


<?php
// $History: listDisciplineContents.php $
//
//*****************  Version 10  *****************
//User: Gurkeerat    Date: 10/29/09   Time: 6:13p
//Updated in $/LeapCC/Templates/Discipline
//added code for autosuggest functionality
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 21/10/09   Time: 11:42
//Updated in $/LeapCC/Templates/Discipline
//Done bug fixing.
//bug ids---
//00001796,00001794,00001786,00001630
//
//*****************  Version 8  *****************
//User: Gurkeerat    Date: 9/29/09    Time: 1:41p
//Updated in $/LeapCC/Templates/Discipline
//resolved issue 1627 and solved calender problem
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 27/08/09   Time: 11:34
//Updated in $/LeapCC/Templates/Discipline
//Done bug fixing.
//bug ids---
//00001283,00001294,00001297
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 25/08/09   Time: 17:29
//Updated in $/LeapCC/Templates/Discipline
//Corrected msg display in teacher dashboard
//and discipline module
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 25/06/09   Time: 12:51
//Updated in $/LeapCC/Templates/Discipline
//Added max length restriction for remarks field
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 25/06/09   Time: 12:01
//Updated in $/LeapCC/Templates/Discipline
//Done bug fixing.
//bug ids---
//00000287 to 00000293,00000295
//
//*****************  Version 3  *****************
//User: Administrator Date: 12/06/09   Time: 19:25
//Updated in $/LeapCC/Templates/Discipline
//Corrected display issues which are detected during user documentation
//preparation
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 1/05/09    Time: 11:34a
//Updated in $/LeapCC/Templates/Discipline
//added reported by in student discipline
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 26/12/08   Time: 15:06
//Created in $/LeapCC/Templates/Discipline
//Created 'Discipline' Module
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 24/12/08   Time: 18:25
//Updated in $/Leap/Source/Templates/Discipline
//Corrected Speling Mistake
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 23/12/08   Time: 12:13
//Updated in $/Leap/Source/Templates/Discipline
//Corrected breadcrumb and added "Discipline Master" link in the menu
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 22/12/08   Time: 18:29
//Created in $/Leap/Source/Templates/Discipline
//Created module 'Discipline'
?>