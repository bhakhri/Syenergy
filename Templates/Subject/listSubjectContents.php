<?php
//This file creates Html Form output in Subjects Module 
//
// Author :Arvind Singh Rawat
// Created on : 14-June-2008
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
                                <td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddSubject',315,250);blankValues();return false;" />&nbsp;</td></tr>
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

<?php floatingDiv_Start('AddSubject','Add Subject'); ?> 
<form name="addSubject" action="" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
<tr>
    <td width="19%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Subject Name<?php echo REQUIRED_FIELD ?></b></nobr></td>
    <td width="81%" class="padding">:&nbsp;<input type="text" id="subjectName" name="subjectName" class="inputbox" maxlength="255" /></td>
</tr>
<tr>    
    <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Subject Code<?php echo REQUIRED_FIELD ?></b></nobr></td>
    <td class="padding">:&nbsp;<input type="text" id="subjectCode" name="subjectCode" class="inputbox" maxlength="20"/></td>
</tr>
<tr>    
    <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Abbr.</b></nobr></td>
    <td class="padding">:&nbsp;<input type="text" id="subjectAbbreviation" name="subjectAbbreviation" class="inputbox" maxlength="20"/></td>
</tr>
<tr>
    <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Subject Type<?php echo REQUIRED_FIELD ?></b></nobr></td>
    <td class="padding">:&nbsp;<select size="1" class="selectfield" name="subjectTypeId" id="subjectTypeId" style="width:185px" onchange="getMarkAttendanceShow(this.value,'Add');" >
              <option value="">Select</option>  
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getSubjectTypeData2($REQUEST_DATA['subjectTypeId']==''? $subjectRecordArray[0]['subjectTypeId'] : $REQUEST_DATA['subjectTypeId'] );
              ?>
        </select>
    </td>
</tr>
<tr>
    <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Subject Category<?php echo REQUIRED_FIELD ?></b></nobr></td>
    <td class="padding">:
        <select size="1" class="selectfield" name="subjectCategoryId" id="subjectCategoryId" style="width:185px">
              <option value="">Select</option>  
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getParentSubjectCategoryData($REQUEST_DATA['subjectCategoryId']==''? $subjectRecordArray[0]['subjectCategoryId'] : $REQUEST_DATA['subjectCategoryId']);
              ?>
        </select>
    </td>
</tr>  
<tr>
    <td width="19%" class="contenttab_internal_rows" colspan="3">
      <b>&nbsp;&nbsp;Alternate Course Name</b>
    </td>
</tr>    
<tr>
    <td width="19%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Course Name</b></nobr></td>
    <td width="81%" class="padding">:&nbsp;<input type="text" id="alternateSubjectName" name="alternateSubjectName" class="inputbox"  /></td>
</tr>
<tr>    
    <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Course Code</b></nobr></td>
    <td class="padding">:&nbsp;<input type="text" id="alternateSubjectCode" name="alternateSubjectCode" class="inputbox" /></td>
</tr>
                    
<tr>
    <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Attendance</b></nobr></td>
    <td class="padding">:
        <select size="1" class="selectfield" name="hasAttendance" id="hasAttendance" style="width:100px">
              <option value="1">Yes</option>  
              <option value="0">No</option>  
        </select>
    </td>
</tr>                      
<tr>
    <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Marks</b></nobr></td>
    <td class="padding">:
        <select size="1" class="selectfield" name="hasMarks" id="hasMarks" style="width:100px">
              <option value="1">Yes</option>  
              <option value="0">No</option>  
        </select>
    </td>
</tr>                   
<tr><td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
      <input type="image" name="AddCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddSubject');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
        </td>
</tr>
<tr>
    <td height="5px"></td>
</tr>

</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->

<!--Start edit Div-->
<?php floatingDiv_Start('EditSubject','Edit Subject '); ?>
<form name="editSubject" action="" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">

<input type="hidden" name="subjectId" id="subjectId" value="" />
<tr>
    <td width="19%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Subject Name<?php echo REQUIRED_FIELD ?></b></nobr></td>
    <td width="81%" class="padding">:&nbsp;<input type="text" id="subjectName" name="subjectName" class="inputbox" maxlength="255" /></td>
</tr>
<tr>    
    <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Subject Code<?php echo REQUIRED_FIELD ?></b></nobr></td>
    <td class="padding">:&nbsp;<input type="text" id="subjectCode" name="subjectCode" class="inputbox" maxlength="20"/></td>
</tr>
<tr>    
    <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Abbr.</b></nobr></td>
    <td class="padding">:&nbsp;<input type="text" id="subjectAbbreviation" name="subjectAbbreviation" class="inputbox" maxlength="20"/></td>
</tr>
<tr>
    <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Subject Type<?php echo REQUIRED_FIELD ?></b></nobr></td>
    <td class="padding">:&nbsp;<select size="1" class="selectfield" name="subjectTypeId" id="subjectTypeId" style="width:185px" onchange="getMarkAttendanceShow(this.value,'Edit');" >
              <option value="">Select</option> 
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getSubjectTypeData2($REQUEST_DATA['subjectTypeId']==''? $subjectRecordArray[0]['subjectTypeId'] : $REQUEST_DATA['subjectTypeId'] );
              ?>
        </select>
    </td>
</tr>
<tr>
    <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Subject Category<?php echo REQUIRED_FIELD ?></b></nobr></td>
    <td class="padding">:
        <select size="1" class="selectfield" name="subjectCategoryId" id="subjectCategoryId" style="width:185px">
              <option value="">Select</option>  
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getParentSubjectCategoryData($REQUEST_DATA['subjectCategoryId']==''? $subjectRecordArray[0]['subjectCategoryId'] : $REQUEST_DATA['subjectCategoryId']);
              ?>
        </select>
    </td>
</tr>
<tr><td height="5px"></td></tr>
<tr>
    <td width="19%" class="contenttab_internal_rows" colspan="3">
      <b>&nbsp;&nbsp;Alternate Course Name</b>
    </td>
</tr>    
<tr>
    <td width="19%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Course Name</b></nobr></td>
    <td width="81%" class="padding">:&nbsp;<input type="text" id="alternateSubjectName" name="alternateSubjectName" class="inputbox"  /></td>
</tr>
<tr>    
    <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Course Code</b></nobr></td>
    <td class="padding">:&nbsp;<input type="text" id="alternateSubjectCode" name="alternateSubjectCode" class="inputbox" /></td>
</tr>
<tr>
    <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Attendance</b></nobr></td>
    <td class="padding">:
        <select size="1" class="selectfield" name="hasAttendance" id="hasAttendance" style="width:100px">
            <option value="1">Yes</option>  
            <option value="0">No</option>  
        </select>
    </td>
</tr>                      
<tr>
    <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Marks</b></nobr></td>
    <td class="padding">:
        <select size="1" class="selectfield" name="hasMarks" id="hasMarks" style="width:100px">
            <option value="1">Yes</option>  
            <option value="0">No</option>  
        </select>
    </td>
</tr>
<tr>
    <td height="5px"></td>
</tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
                    <input type="image" name="EditCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('EditSubject');return false;" />
        </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>

</table></form>
    <?php floatingDiv_End();
// $History: listSubjectContents.php $	
//
//*****************  Version 13  *****************
//User: Ajinder      Date: 3/30/10    Time: 1:33p
//Updated in $/LeapCC/Templates/Subject
//bugs fixed. FCNS No.1490
//
//*****************  Version 12  *****************
//User: Parveen      Date: 10/20/09   Time: 3:17p
//Updated in $/LeapCC/Templates/Subject
//spelling correct (Attendance)
//
//*****************  Version 11  *****************
//User: Parveen      Date: 9/23/09    Time: 3:27p
//Updated in $/LeapCC/Templates/Subject
//hasAttendance, hasMarks filed added
//
//*****************  Version 10  *****************
//User: Parveen      Date: 8/28/09    Time: 3:15p
//Updated in $/LeapCC/Templates/Subject
//condition & formating updated 
//
//*****************  Version 9  *****************
//User: Parveen      Date: 8/20/09    Time: 3:38p
//Updated in $/LeapCC/Templates/Subject
//subjectCode, Abbr text box limit increase
//
//*****************  Version 8  *****************
//User: Parveen      Date: 8/11/09    Time: 2:30p
//Updated in $/LeapCC/Templates/Subject
// issue fix 1012, 1011
//validation updated
//
//*****************  Version 7  *****************
//User: Parveen      Date: 8/06/09    Time: 5:26p
//Updated in $/LeapCC/Templates/Subject
//duplicate values & Dependency checks, formatting & conditions updated 
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 8/05/09    Time: 7:00p
//Updated in $/LeapCC/Templates/Subject
//fixed bug nos.0000903, 0000800, 0000802, 0000801, 0000776, 0000775,
//0000776, 0000801, 0000778, 0000777, 0000896, 0000796, 0000720, 0000717,
//0000910, 0000443, 0000442, 0000399, 0000390, 0000373
//
//*****************  Version 5  *****************
//User: Parveen      Date: 7/20/09    Time: 1:55p
//Updated in $/LeapCC/Templates/Subject
//new enhancement categoryId (link subject_category table) new field
//added 
//
//*****************  Version 4  *****************
//User: Parveen      Date: 6/17/09    Time: 11:04a
//Updated in $/LeapCC/Templates/Subject
//validation, formatting, themes base css templates changes
//
//*****************  Version 3  *****************
//User: Parveen      Date: 5/07/09    Time: 2:40p
//Updated in $/LeapCC/Templates/Subject
//issue fix subject code space allow, sorting format setting
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 11/12/08   Time: 16:58
//Updated in $/LeapCC/Templates/Subject
//Added "Print" and "Export to excell" in subject and subjectType modules
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Subject
//
//*****************  Version 12  *****************
//User: Arvind       Date: 9/05/08    Time: 5:43p
//Updated in $/Leap/Source/Templates/Subject
//removed unsortable class
//
//*****************  Version 11  *****************
//User: Arvind       Date: 8/27/08    Time: 12:41p
//Updated in $/Leap/Source/Templates/Subject
//html validated
//
//*****************  Version 10  *****************
//User: Arvind       Date: 8/19/08    Time: 3:10p
//Updated in $/Leap/Source/Templates/Subject
//replaced search button
//
//*****************  Version 9  *****************
//User: Arvind       Date: 8/14/08    Time: 7:18p
//Updated in $/Leap/Source/Templates/Subject
//modified the bread crum
//
//*****************  Version 8  *****************
//User: Arvind       Date: 8/14/08    Time: 6:59p
//Updated in $/Leap/Source/Templates/Subject
//modified the bread crum
//
//*****************  Version 7  *****************
//User: Arvind       Date: 8/11/08    Time: 3:22p
//Updated in $/Leap/Source/Templates/Subject
//modified the editsubject div
//
//*****************  Version 6  *****************
//User: Arvind       Date: 7/21/08    Time: 5:28p
//Updated in $/Leap/Source/Templates/Subject
//added maxlength in all fields
//
//*****************  Version 5  *****************
//User: Arvind       Date: 7/11/08    Time: 3:13p
//Updated in $/Leap/Source/Templates/Subject
//removed javascript sorting class from the table
//
//*****************  Version 4  *****************
//User: Arvind       Date: 7/10/08    Time: 12:40p
//Updated in $/Leap/Source/Templates/Subject
//Added SELECT option in dropdown
//
//*****************  Version 3  *****************
//User: Arvind       Date: 6/30/08    Time: 7:30p
//Updated in $/Leap/Source/Templates/Subject
//modify image button cancel to input type image button
//
//*****************  Version 2  *****************
//User: Arvind       Date: 6/17/08    Time: 2:59p
//Updated in $/Leap/Source/Templates/Subject
//modified added new ajax function
//
//*****************  Version 1  *****************
//User: Arvind       Date: 6/14/08    Time: 6:26p
//Created in $/Leap/Source/Templates/Subject
//new files added
?>
    <!--End: Div To Edit The Table-->


