<?php
////This file creates Html Form output in Attendance Code Module 
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
             <tr>
                <td class="contenttab_border" height="20">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr height="30">
                       <td class="contenttab_border" height="20" style="border-right:0px;">
                                    <?php require_once(TEMPLATES_PATH . "/searchForm.php"); ?>
                                </td>
                        <td class="content_title" title="Add"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" 
                        align="right" onClick="displayWindow('AddAttendanceCode',340,100);blankValues();return false;" />&nbsp;</td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" >
                <div id="results"></div>
        </td>
     </tr>
     <tr>
         <td class="content_title" align="right" >
           <input type="image" name="print" src="<?php  echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport();" />&nbsp;
           <input type="image" name="generateCSV" id='generateCSV' onClick="printCSV();" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" />
         </td>
    </tr>
    </table>
   </td>
    </tr>
    </table> 
    <!--Start Add Div-->
<?php floatingDiv_Start('AddAttendanceCode','Add Attendance Code'); ?>
<form name="addAttendanceCode" action="" method="post"> 
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">

<tr>
    <td width="21%" class="contenttab_internal_rows">&nbsp;&nbsp;<nobr><b>Attendance Name<?php echo REQUIRED_FIELD ?></b></nobr></td>
    <td width="3%" class="padding">:</td>
    <td width="76%" class="padding"><input type="text" id="attendanceCodeName" name="attendanceCodeName" class="inputbox" /></td>
</tr>
<tr>    
    <td class="contenttab_internal_rows">&nbsp;&nbsp;<nobr><b>Attendance Code<?php echo REQUIRED_FIELD ?></b></nobr></td>
    <td class="padding">:</td>
    <td class="padding"><input type="text" id="attendanceCode" name="attendanceCode" class="inputbox" /></td>
</tr>
<tr>    
    <td class="contenttab_internal_rows">&nbsp;&nbsp;<nobr><b>Description</b></nobr></td>
    <td class="padding">:</td>
    <td class="padding"><input type="text" id="attendanceCodeDescription" name="attendanceCodeDescription" class="inputbox" /></td>
</tr>
<tr>    
    <td class="contenttab_internal_rows">&nbsp;&nbsp;<nobr><b>Percentage<?php echo REQUIRED_FIELD ?></b></nobr></td>
    <td class="padding">:</td>
    <td class="padding"><input type="text" id="attendanceCodePercentage" name="attendanceCodePercentage" maxlength="3" class="inputbox" /></td>
</tr>
<tr>    
    <td class="contenttab_internal_rows">&nbsp;&nbsp;<nobr><b>Show in Leave Type<?php echo REQUIRED_FIELD ?></b></nobr></td>
    <td class="padding">:</td>
    <td class="padding">
     <input type="radio" name="showInLeave" id="showInLeave1" value="1">Yes&nbsp;
     <input type="radio" name="showInLeave" id="showInLeave2" value="0" checked="checked">No
    </td>
</tr>
  
<tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" colspan="3">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
                    <input type="image" name="Cancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" 
                   onclick="javascript:hiddenFloatingDiv('AddAttendanceCode');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
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
<?php floatingDiv_Start('EditAttendanceCode','Edit Attendance Code'); ?>
  <form name="editAttendanceCode" action="" method="post">      
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
  
   
    <input type="hidden" name="attendanceCodeId" id="attendanceCodeId" value="" />
    <tr>
        <td width="21%" class="contenttab_internal_rows">&nbsp;&nbsp;<nobr><b>Attendance Name<?php echo REQUIRED_FIELD ?></b></nobr></td>
        <td width="3%" class="padding">:</td>
        <td width="76%" class="padding"><input type="text" id="attendanceCodeName" name="attendanceCodeName" class="inputbox"  /></td>
    </tr>
    <tr>    
        <td class="contenttab_internal_rows">&nbsp;&nbsp;<nobr><b>Attendance Code<?php echo REQUIRED_FIELD ?></b></nobr></td>
        <td class="padding">:</td>
        <td class="padding"><input type="text" id="attendanceCode" name="attendanceCode" class="inputbox" /></td>
    </tr>
    <tr>    
    <td class="contenttab_internal_rows">&nbsp;&nbsp;<nobr><b>Description</b></nobr></td>
    <td class="padding">:</td>
    <td class="padding"><input type="text" id="attendanceCodeDescription" name="attendanceCodeDescription" class="inputbox" /></td>
</tr>
<tr>    
    <td class="contenttab_internal_rows">&nbsp;&nbsp;<nobr><b>Percentage<?php echo REQUIRED_FIELD ?></b></nobr></td>
    <td class="padding">:</td>
    <td class="padding"><input type="text" id="attendanceCodePercentage" name="attendanceCodePercentage" maxlength="3" class="inputbox" />
   </td>
</tr>
<tr>    
    <td class="contenttab_internal_rows">&nbsp;&nbsp;<nobr><b>Show in Leave Type<?php echo REQUIRED_FIELD ?></b></nobr></td>
    <td class="padding">:</td>
    <td class="padding">
     <input type="radio" name="showInLeave" id="showInLeave1" value="1" >Yes&nbsp;
     <input type="radio" name="showInLeave" id="showInLeave2" value="0" checked="checked" >No
    </td>
</tr>   
    <tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" colspan="3">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
                    <input type="image" name="Cancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" 
                    onclick="javascript:hiddenFloatingDiv('EditAttendanceCode');return false;" />
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

//$History: listAttendanceCodeContents.php $
//
//*****************  Version 7  *****************
//User: Parveen      Date: 8/12/09    Time: 5:53p
//Updated in $/LeapCC/Templates/AttendanceCode
//996 issue fix (enter search updated)
//
//*****************  Version 6  *****************
//User: Parveen      Date: 8/08/09    Time: 5:30p
//Updated in $/LeapCC/Templates/AttendanceCode
//bug fix 505, 504, 503, 968, 961, 960, 959, 958, 957, 956, 955, 954,
//953, 952,
//951, 723, 722, 797, 798, 799, 916, 935, 936, 937, 938, 939, 940, 944
//(alignment, condition & formatting updated)
//
//*****************  Version 5  *****************
//User: Parveen      Date: 8/06/09    Time: 5:26p
//Updated in $/LeapCC/Templates/AttendanceCode
//duplicate values & Dependency checks, formatting & conditions updated 
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 8/05/09    Time: 7:00p
//Updated in $/LeapCC/Templates/AttendanceCode
//fixed bug nos.0000903, 0000800, 0000802, 0000801, 0000776, 0000775,
//0000776, 0000801, 0000778, 0000777, 0000896, 0000796, 0000720, 0000717,
//0000910, 0000443, 0000442, 0000399, 0000390, 0000373
//
//*****************  Version 3  *****************
//User: Parveen      Date: 6/11/09    Time: 5:23p
//Updated in $/LeapCC/Templates/AttendanceCode
//conditions, validation & formatting updated
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 20/04/09   Time: 15:22
//Updated in $/LeapCC/Templates/AttendanceCode
//Added "Show in Leave Type" field in Attendance Code Master module
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/AttendanceCode
//
//*****************  Version 16  *****************
//User: Arvind       Date: 10/21/08   Time: 10:53a
//Updated in $/Leap/Source/Templates/AttendanceCode
//modify dispaly
//
//*****************  Version 15  *****************
//User: Arvind       Date: 9/05/08    Time: 5:44p
//Updated in $/Leap/Source/Templates/AttendanceCode
//removed unsortable class
//
//*****************  Version 13  *****************
//User: Arvind       Date: 9/02/08    Time: 7:41p
//Updated in $/Leap/Source/Templates/AttendanceCode
//removed height and width
//
//*****************  Version 12  *****************
//User: Arvind       Date: 8/27/08    Time: 12:44p
//Updated in $/Leap/Source/Templates/AttendanceCode
//html validated
//
//*****************  Version 11  *****************
//User: Arvind       Date: 8/19/08    Time: 2:44p
//Updated in $/Leap/Source/Templates/AttendanceCode
//replaced search button
//
//*****************  Version 10  *****************
//User: Arvind       Date: 8/14/08    Time: 7:01p
//Updated in $/Leap/Source/Templates/AttendanceCode
//modified the bread crum
//
//*****************  Version 9  *****************
//User: Arvind       Date: 8/01/08    Time: 6:54p
//Updated in $/Leap/Source/Templates/AttendanceCode
//modified maxlength of percentage
//
//*****************  Version 8  *****************
//User: Arvind       Date: 7/21/08    Time: 4:08p
//Updated in $/Leap/Source/Templates/AttendanceCode
//removed a field in attendanceCodeAction from add and edit div's and
//added a new field to display  Percentage
//
//*****************  Version 7  *****************
//User: Arvind       Date: 7/18/08    Time: 12:08p
//Updated in $/Leap/Source/Templates/AttendanceCode
//added flag variable 
//
//*****************  Version 6  *****************
//User: Arvind       Date: 7/10/08    Time: 1:14p
//Updated in $/Leap/Source/Templates/AttendanceCode
//increased the width of add div and added maxlength='4' in percentage
//field as per database size 
//
//*****************  Version 5  *****************
//User: Arvind       Date: 6/30/08    Time: 7:28p
//Updated in $/Leap/Source/Templates/AttendanceCode
//modify image button cancel to input type image button
//
//*****************  Version 4  *****************
//User: Arvind       Date: 6/26/08    Time: 4:50p
//Updated in $/Leap/Source/Templates/AttendanceCode
//1) added onclick function for delete button
//2) Added blankvalues() function in onclick function of add
//
//*****************  Version 3  *****************
//User: Arvind       Date: 6/17/08    Time: 4:16p
//Updated in $/Leap/Source/Templates/AttendanceCode
//added new fields
//
//*****************  Version 2  *****************
//User: Arvind       Date: 6/16/08    Time: 10:45a
//Updated in $/Leap/Source/Templates/AttendanceCode
//modifiaction
//
//*****************  Version 1  *****************
//User: Arvind       Date: 6/14/08    Time: 7:21p
//Created in $/Leap/Source/Templates/AttendanceCode
//new filel added

?>
