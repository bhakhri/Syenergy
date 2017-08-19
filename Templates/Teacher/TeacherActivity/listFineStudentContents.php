<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR Fine Category LISTING 
// Author :Dipanjan Bhattacharjee 
// Created on : (02.07.2009)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
require_once(TEMPLATES_PATH . "/breadCrumb.php");
?>
	
			<table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
				<tr>
					<td valign="top" class="content">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
   <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <!--  <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
             <td valign="top" class="content">
             <table width="100%" border="0" cellspacing="0" cellpadding="0"> -->
			 <tr height="30">
								<td class="contenttab_border" height="20" style="border-right:0px;">
									<?php require_once(TEMPLATES_PATH . "/searchForm.php"); ?>
								</td>
								<td class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" 
                        align="right" onClick="displayWindow('AddFineStudent',360,250);blankValues();return false;" />&nbsp;</td></tr>
             <tr >
                <td class="contenttab_row" valign="top" colspan=2>
                <div id="results">  
                 </div>           
             </td>
          </tr>
            <tr><td height="10px"></td></tr>
          <tr>
           <td align="right">
             <a href="javascript:void(0);" onClick="printReport()"><input type="image" src="<?php echo IMG_HTTP_PATH ?>/print.gif" border="0"></a>&nbsp;<INPUT type="image" src="<?php echo IMG_HTTP_PATH ?>/excel.gif" border="0" onClick="javascript:printCSV();">
          </td></tr> 
          </table>                     
  

<!--Start Add Div-->
<?php floatingDiv_Start('AddFineStudent','Add Student Fine'); ?>
<form name="AddFineStudent" action="" method="post">  
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border" valign="top">
    <tr valign="top">
        <td width="21%" class="contenttab_internal_rows"><input type="hidden" name="studentId" id="studentId" value="" />
    <input type="hidden" name="classId" id="classId" value="" /><nobr><b>Roll No.<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding"><b>:</b></td>
        <td width="79%" class="padding">
         <input type="text" id="studentRollNo" name="studentRollNo" class="inputbox1" autocomplete='off' maxlength="30" onchange="getStudent(this.value,'Add');" size="35"/>
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
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Fine Category<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding"><b>:</b></td>
        <td width="79%" class="padding">
         <select size="1" class="selectfield" name="fineCategoryId" id="fineCategoryId" style="width:226px">
         <option value="">Select</option>
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getRoleFineCategory();
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
                  echo HtmlFunctions::getInstance()->datePicker('fineDate1',date('Y-m-d'));
              ?>
      </td>
 </tr>
 <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Amount<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding"><b>:</b></td>
        <td width="79%" class="padding">
         <input type="text" id="fineAmount" name="fineAmount" class="inputbox1" maxlength="8" size="9"/>
        </td>
    </tr>
 <tr>
        <td width="21%" class="contenttab_internal_rows" valign="top"><nobr><b>Reason<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding" valign="top"><b>:</b></td>
        <td width="79%" class="padding"><textarea name="remarksTxt" id="remarksTxt" cols="32" rows="3" maxlength="25"  class="inputbox1"></textarea></td>
 </tr>
 <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Add to "No Dues"<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding"><b>:</b></td>
        <td width="79%" class="padding"><select size="1" class="selectfield" name="dueStatus" id="dueStatus" style="width:60px">
         <option value="1">Yes</option>
         <option value="0">No</option>
        </select></td>
 </tr> 
<tr><td height="5px" colspan="3"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="3">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
       <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddFineStudent');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
        </td>
</tr>
<tr><td height="5px" colspan="3"></td></tr>
</table>
</form> 
<?php floatingDiv_End(); ?>
<!--End Add Div-->

<!--Start Edit Div-->
<?php floatingDiv_Start('EditFineStudent','Edit Student Fine'); ?>
<form name="EditFineStudent" action="" method="post">  
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td width="21%" class="contenttab_internal_rows"><input type="hidden" name="oldDueStatus" id="oldDueStatus" value="" /><input type="hidden" name="oldDueAmount" id="oldDueAmount" value="" /><input type="hidden" name="fineStudentId" id="fineStudentId" value="" /><input type="hidden" name="studentId" id="studentId" value="" />
    <input type="hidden" name="classId" id="classId" value="" /><nobr><b>Roll No.<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding"><b>:</b></td>
        <td width="79%" class="padding">
         <input type="text" id="studentRollNo" name="studentRollNo" class="inputbox1" maxlength="30" size="35" readonly/>
        </td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Name </b></nobr></td>
        <td class="padding"><b>:</b></td>
        <td width="79%" class="padding">
          <div name="studentName2" id="studentName2" style="display:inline;padding:5px" />
        </td>
   </tr>
   <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Class</b></nobr></td>
        <td class="padding"><b>:</b></td>
        <td width="79%" class="padding">
          <div name="className2" id="className2" style="display:inline;padding:5px" />
        </td>
   </tr>
   <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Fine Category<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding"><b>:</b></td>
        <td width="79%" class="padding">
         <select size="1" class="selectfield" name="fineCategoryId" id="fineCategoryId" style="width:226px">
         <option value="">Select</option>
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getRoleFineCategory();
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
                  echo HtmlFunctions::getInstance()->datePicker('fineDate2',date('Y-m-d'));
              ?>
      </td>
 </tr>
 <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Amount<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding"><b>:</b></td>
        <td width="79%" class="padding">
         <input type="text" id="fineAmount" name="fineAmount" class="inputbox1" maxlength="8" size="9"/>
        </td>
    </tr>
 <tr>
        <td width="21%" class="contenttab_internal_rows" valign="top"><nobr><b>Reason<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding" valign="top"><b>:</b></td>
        <td width="79%" class="padding"><textarea name="remarksTxt" id="remarksTxt" cols="32" rows="3" maxlength="25" class="inputbox1"></textarea></td>
 </tr>
 <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Add to "No Dues"<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding"><b>:</b></td>
        <td width="79%" class="padding"><select size="1" class="selectfield" name="dueStatus" id="dueStatus" style="width:60px">
         <option value="1">Yes</option>
         <option value="0">No</option>
        </select></td>
 </tr> 
<tr><td height="5px" colspan="3"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="3">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
       <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('EditFineStudent');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
        </td>
</tr>
<tr><td height="5px" colspan="3"></td></tr>
</table>
</form> 


<?php floatingDiv_End(); ?>
<!--End: Div To Edit The Table-->


<?php
// $History: listFineStudentContents.php $
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 10/29/09   Time: 6:13p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//added code for autosuggest functionality
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 7/29/09    Time: 4:53p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//fixed bugs 703,704,705,706,707,708,709,733,742,743,744,745,750,
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 7/08/09    Time: 7:22p
//Created in $/LeapCC/Templates/Teacher/TeacherActivity
//intial checkin
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 7/07/09    Time: 4:20p
//Updated in $/LeapCC/Templates/Fine
//removed maxlength from reason textarea
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 7/06/09    Time: 6:36p
//Updated in $/LeapCC/Templates/Fine
//updated validation messages
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 7/03/09    Time: 4:29p
//Created in $/LeapCC/Templates/Fine
//Intial checkin for fine student
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 2/07/09    Time: 16:08
//Created in $/LeapCC/Templates/FineCategory
//Created "Fine Category Master" module
?>