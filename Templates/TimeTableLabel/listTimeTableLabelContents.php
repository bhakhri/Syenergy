<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR TimeTableLabel LISTING 
//
// Author :Dipanjan Bhattacharjee 
// Created on : (30.09.2008)
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
                                <td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" title ="Add"  onClick="displayWindow('AddTimeTableLabel',300,300);blankValues();return false;" />&nbsp;</td></tr>
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

<?php floatingDiv_Start('AddTimeTableLabel','Add Time Table Label'); ?>
<form name="AddTimeTableLabel" action="" method="post">  
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Name<?php echo REQUIRED_FIELD;?></b></nobr></td>
        <td width="79%" class="padding"><nobr>:&nbsp;&nbsp;<input type="text" id="labelName" name="labelName" class="inputbox" maxlength="100" /></nobr></td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><strong>From Date<?php echo REQUIRED_FIELD;?></strong>&nbsp;</td>
        <td width="79%" class="padding"><nobr>:&nbsp;                                    
        <?php 
        require_once(BL_PATH.'/HtmlFunctions.inc.php'); 
        echo HtmlFunctions::getInstance()->datePicker('fromDate');
        ?></td>
    </tr>    
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><strong>To Date<?php echo REQUIRED_FIELD;?></strong>&nbsp;</td>
        <td width="79%" class="padding"><nobr>:&nbsp;
        <?php 
        require_once(BL_PATH.'/HtmlFunctions.inc.php'); 
        echo HtmlFunctions::getInstance()->datePicker('toDate');
        ?>
        </td>
    </tr>  
    <tr>    
        <td  class="contenttab_internal_rows"><nobr><b>Active</b></nobr></td>
        <td  class="padding" align="left"><nobr>:&nbsp;
         <input type="radio" id="isActive" name="isActive1" value="1" />Yes&nbsp;
         <input type="radio" id="isActive" name="isActive1" value="1" />No&nbsp;</nobr>
        </td>
    </tr>
	<tr>    
        <td  class="contenttab_internal_rows"><nobr><b>Type</b></nobr></td>
        <td  class="padding" align="left"><nobr>:&nbsp;
         <input type="radio" id="timeTableType" name="timeTableType1" value="1" />Weekly&nbsp;
         <input type="radio" id="timeTableType" name="timeTableType1" value="2" />Daily&nbsp;</nobr>
        </td>
    </tr>
   <tr>
     <td height="5px" colspan="2"></td>
    </tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
      <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddTimeTableLabel');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
    </td>
</tr>
<tr>
    <td height="5px" colspan="2"></td></tr>
<tr>
</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->

<!--Start Edit Div-->
<?php floatingDiv_Start('EditTimeTableLabel','Edit Time Table Label'); ?>
<form name="EditTimeTableLabel" action="" method="post">  
    <input type="hidden" name="labelId" id="labelId" value="" />
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Name<?php echo REQUIRED_FIELD;?></b></nobr></td>
        <td width="79%" class="padding"><nobr>:&nbsp;&nbsp;<input type="text" id="labelName" name="labelName" class="inputbox" maxlength="100" /></nobr></td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><strong>From Date<?php echo REQUIRED_FIELD;?></strong>&nbsp;</td>
        <td width="79%" class="padding"><nobr>:&nbsp;                                    
        <?php 
        require_once(BL_PATH.'/HtmlFunctions.inc.php'); 
        echo HtmlFunctions::getInstance()->datePicker('fromDate1');
        ?></td>
     </tr>    
     <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><strong>To Date<?php echo REQUIRED_FIELD;?></strong>&nbsp;</td>
        <td width="79%" class="padding"><nobr>:&nbsp;
        <?php 
        require_once(BL_PATH.'/HtmlFunctions.inc.php'); 
        echo HtmlFunctions::getInstance()->datePicker('toDate1');
        ?>
        </td>
    </tr>   
    <tr>    
        <td  class="contenttab_internal_rows"><nobr><b>Active</b></nobr></td>
        <td  class="padding" align="left"><nobr>:</b>&nbsp;
         <input type="radio" id="isActive" name="isActive1" value="1" />Yes&nbsp;
         <input type="radio" id="isActive" name="isActive1" value="1" />No&nbsp;</nobr>
        </td>
    </tr>
	<tr>    
        <td  class="contenttab_internal_rows"><nobr><b>Type</b></nobr></td>
        <td  class="padding" align="left"><nobr>:</b>&nbsp;
         <input type="radio" id="timeTableType" name="timeTableType1" value="1" />Weekly&nbsp;
         <input type="radio" id="timeTableType" name="timeTableType1" value="2" />Daily&nbsp;</nobr>
        </td>
    </tr>
   <tr>
     <td height="5px" colspan="2"></td>
    </tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
      <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('EditTimeTableLabel');return false;" />
    </td>
</tr>
<tr>
    <td height="5px" colspan="2"></td></tr>
<tr>
</table>
</form>
    <?php floatingDiv_End(); ?>
    <!--End: Div To Edit The Table-->


<?php
// $History: listTimeTableLabelContents.php $
//
//*****************  Version 10  *****************
//User: Ajinder      Date: 4/21/10    Time: 4:34p
//Updated in $/LeapCC/Templates/TimeTableLabel
//done changes as per FCNS No. 1625
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 9/03/09    Time: 1:55p
//Updated in $/LeapCC/Templates/TimeTableLabel
//Gurkeerat: resolved issue 1390
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 8/12/09    Time: 6:51p
//Updated in $/LeapCC/Templates/TimeTableLabel
//modified in breadcrumb
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 8/12/09    Time: 6:35p
//Updated in $/LeapCC/Templates/TimeTableLabel
//change the breadcrumb
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 24/07/09   Time: 14:58
//Updated in $/LeapCC/Templates/TimeTableLabel
//Done bug fixing.
//Bug ids----0000648,0000650,0000667,0000651,0000676,0000649,0000652
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 21/07/09   Time: 12:08
//Updated in $/LeapCC/Templates/TimeTableLabel
//Done bug fixing.
//Bug ids ----0000627,0000632,0000633,0000640
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 20/07/09   Time: 19:08
//Updated in $/LeapCC/Templates/TimeTableLabel
//Done bug fixing.
//bug ids ---0000629 to 0000631
//
//*****************  Version 3  *****************
//User: Administrator Date: 12/06/09   Time: 19:25
//Updated in $/LeapCC/Templates/TimeTableLabel
//Corrected display issues which are detected during user documentation
//preparation
//
//*****************  Version 2  *****************
//User: Parveen      Date: 3/10/09    Time: 2:35p
//Updated in $/LeapCC/Templates/TimeTableLabel
//start and end date for fields added
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/TimeTableLabel
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/30/08    Time: 3:34p
//Created in $/Leap/Source/Templates/TimeTableLabel
//Created TimeTable Labels
?>