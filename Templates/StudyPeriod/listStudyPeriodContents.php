<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR CITY LISTING
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
                                <td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddStudyPeriod',315,250);blankValues();return false;" />&nbsp;</td></tr>
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

<?php floatingDiv_Start('AddStudyPeriod','Add Study Period'); ?>
<form name="AddStudyPeriod" action="" method="post">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Period Value<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td width="79%" class="padding">: <input type="text" id="periodValue" name="periodValue" class="inputbox" maxlength="10" onchange="createPeriodName('Add');" /></td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Periodicity<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td width="79%" class="padding">: <select size="1" class="selectfield" name="periodicityName" id="periodicityName" onchange="createPeriodName('Add');">
        <option value="" selected="selected">Select</option>
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getPeriodicityData($REQUEST_DATA['periodicityName']==''? $studyPeriodRecordArray[0]['periodicityId'] : $REQUEST_DATA['periodicityName'] );
              ?>
        </select>
    </td>
 </tr>
 <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Period Name<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td width="79%" class="padding">: <input readonly type="text" id="periodName" name="periodName" class="inputbox" maxlength="100" /></td>
  </tr>
<tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddStudyPeriod');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
        </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->

<!--Start Edit Div-->
<?php floatingDiv_Start('EditStudyPeriod','Edit Study Period '); ?>
<form name="EditStudyPeriod" action="" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <input type="hidden" name="studyPeriodId" id="studyPeriodId" value="" />
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Period Value<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td width="79%" class="padding">: <input type="text" id="periodValue" name="periodValue" class="inputbox" maxlength="10" onchange="createPeriodName('Edit');" /></td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Periodicity<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td width="79%" class="padding">: <select size="1" class="selectfield" name="periodicityName" id="periodicityName" onchange="createPeriodName('Edit');">
        <option value="">Select</option>
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getPeriodicityData($REQUEST_DATA['periodicityName']==''? $studyPeriodRecordArray[0]['periodicityId'] : $REQUEST_DATA['periodicityName'] );
              ?>
        </select>
    </td>
  </tr>
  <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Period Name<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td width="79%" class="padding">: <input readonly type="text" id="periodName" name="periodName" class="inputbox" maxlength="100" /></td>
  </tr>
<tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
       <input type="image" name="editCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('EditStudyPeriod');return false;" />
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
// $History: listStudyPeriodContents.php $
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 3/08/09    Time: 14:28
//Updated in $/LeapCC/Templates/StudyPeriod
//Done bug fixing.
//bug ids---
//0000825,0000826,0000833,0000834,0000835,0000836,0000837
//
//*****************  Version 5  *****************
//User: Administrator Date: 8/07/09    Time: 10:30
//Updated in $/LeapCC/Templates/StudyPeriod
//Corrected javascript code
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 7/07/09    Time: 16:21
//Updated in $/LeapCC/Templates/StudyPeriod
//modified study period module
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 6/07/09    Time: 19:29
//Updated in $/LeapCC/Templates/StudyPeriod
//Done bug fixing.
//Bug ids---
//0000483,0000484,0000487,000489,0000485,0000486,0000488,
//0000490,0000491,0000492
//
//*****************  Version 2  *****************
//User: Administrator Date: 12/06/09   Time: 19:25
//Updated in $/LeapCC/Templates/StudyPeriod
//Corrected display issues which are detected during user documentation
//preparation
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/StudyPeriod
//
//*****************  Version 10  *****************
//User: Dipanjan     Date: 10/23/08   Time: 6:33p
//Updated in $/Leap/Source/Templates/StudyPeriod
//Added functionality for study period report print
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 9/01/08    Time: 11:34a
//Updated in $/Leap/Source/Templates/StudyPeriod
//Corrected the problem of header row of tables(which displays the list )
//which is taking much
//vertical space in IE.
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 8/26/08    Time: 6:41p
//Updated in $/Leap/Source/Templates/StudyPeriod
//Removed HTML error by readjusting <form> tags
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 8/18/08    Time: 6:48p
//Updated in $/Leap/Source/Templates/StudyPeriod
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 8/14/08    Time: 6:22p
//Updated in $/Leap/Source/Templates/StudyPeriod
//corrected breadcrumb and reset button height and width
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 8/14/08    Time: 5:59p
//Updated in $/Leap/Source/Templates/StudyPeriod
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 7/11/08    Time: 3:59p
//Updated in $/Leap/Source/Templates/StudyPeriod
//Added the functionality : create  periodName from periodValue and
//periodicityName
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 7/09/08    Time: 6:24p
//Updated in $/Leap/Source/Templates/StudyPeriod
//Added javascript validations and restructure add and edit divs to
//have period value->periodicity->period name
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/02/08    Time: 6:49p
//Updated in $/Leap/Source/Templates/StudyPeriod
//Created "StudyPeriod Master"  Module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/02/08    Time: 4:02p
//Created in $/Leap/Source/Templates/StudyPeriod
//Initial Checkin
//
//*****************  Version 11  *****************
//User: Dipanjan     Date: 6/30/08    Time: 7:42p
//Updated in $/Leap/Source/Templates/City
//Solved TabOrder Problem
//
//*****************  Version 10  *****************
//User: Dipanjan     Date: 6/28/08    Time: 2:35p
//Updated in $/Leap/Source/Templates/City
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 6/28/08    Time: 12:57p
//Updated in $/Leap/Source/Templates/City
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 6/28/08    Time: 11:23a
//Updated in $/Leap/Source/Templates/City
//Added AjaxList Functionality
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 6/25/08    Time: 11:37a
//Updated in $/Leap/Source/Templates/City
//Added AjaxEnabled Delete Functionality
//Modified delete button
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 6/18/08    Time: 1:05p
//Updated in $/Leap/Source/Templates/City
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 6/18/08    Time: 11:52a
//Updated in $/Leap/Source/Templates/City
//adding constraints done
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 6/13/08    Time: 11:02a
//Updated in $/Leap/Source/Templates/City
//Modifying Comments Complete
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 6/12/08    Time: 7:58p
//Updated in $/Leap/Source/Templates/City
//Comment Insertion Complete
?>