<?php 
//it contain the template of teacher load time table 
//
// Author :Parveen Sharma
// Created on : 19-01-2009
// Copyright 2009-2010: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
              <?php require_once(TEMPLATES_PATH . "/breadCrumb.php");        ?> 
        </td>
    </tr>
    <tr>
        <td valign="top">
           <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
              <tr>
                 <td valign="top" class="content">
                     <!-- form table starts -->
                     <table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2">
                        <tr>
                            <td valign="top" class="contenttab_row1">
                                <form name="teacherLoadTimeTable" action="" method="post" onSubmit="return false;">
                                    <select class="selectfield" name="lectureGroupType" id="lectureGroupType" style="display:none">
                                         <?php
                                              require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                              echo HtmlFunctions::getInstance()->getGroupTypeData();
                                         ?>
                                    </select>
                                  <table align="center" border="0" cellpadding="0" cellspacing="0" >
                                    <tr>
                                      <td class="contenttab_internal_rows" nowrap><nobr><b>Time Table: </b></nobr></td>
                                      <td class="contenttab_internal_rows" nowrap>
                                      <select size="1" class="inputbox1" name="labelId" id="labelId" onChange="hideResults();" style="width:200px"> 
                                            <option value="">Select</option>
                                             <?php
                                               require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                               echo HtmlFunctions::getInstance()->getTimeTableLabelData('','');
                                             ?>
                                            </select></td>
                                        <td class="contenttab_internal_rows" nowrap><nobr><b>&nbsp;Branch: </b></nobr></td>
                                        <td class="contenttab_internal_rows" nowrap>
                                           <select size="1" class="selectfield" name="branchId" id="branchId" onChange="getEmployee();"  style="width:180px">
                                              <option value="">All</option>
                                              <?php
                                                 require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                 echo HtmlFunctions::getInstance()->getBranchData();
                                              ?>
                                           </select>
                                        </td>    
                                        <td class="contenttab_internal_rows" nowrap><nobr><b>&nbsp;Teacher: </b></nobr></td>
                                        <td class="contenttab_internal_rows" nowrap>
                                            <select size="1" class="selectfield" name="teacherId" id="teacherId" onBlur="hideResults();"  style="width:320px">
                                             </select>
                                        </td>
                                        <td class="contenttab_internal_rows" nowrap colspan="4" >&nbsp;&nbsp;
<input type="image" name="teacherListSubmit" value="teacherListSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateAddForm(this.form);return false;" />
                                                </td>
                                            </tr>
                                        </table>
                                    </form>
                                </td>
                            </tr>
                        </table>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr id='nameRow' style='display:none;'>
                                <td class="" height="20">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="contenttab_border">
                                        <tr>
                                            <td colspan="1" class="content_title">Teacher Load Details :</td>
                                            <td colspan="1" class="content_title" align="right">
                                            <input type="image" name="teacherPrintSubmit" value="teacherPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;&nbsp;
                                            <input type="image" href='javascript:void(0);' onClick='javascript:printReportCSV();' src="<?php echo IMG_HTTP_PATH ?>/excel.gif" border="0">
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr id='resultRow' style='display:none;'>
                                <td colspan='1' class='contenttab_row'>
                                    <div id = 'resultsDiv'></div>
                                </td>
                            </tr>
                            <tr id='nameRow2' style='display:none;'>
                                <td class="" height="20">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="">
                                        <tr>
                                            <td colspan="2" class="content_title" align="right">
                                                <input type="image" name="teacherPrintSubmit" value="teacherPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;&nbsp;
                                                <input type="image" href='javascript:void(0);' onClick='javascript:printReportCSV();' src="<?php echo IMG_HTTP_PATH ?>/excel.gif" border="0">
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        <!-- form table ends -->
                    </td>
                </tr>
            </table>
        </table>
<?php
//$History: listLoadTimeTableContents.php $
//
//*****************  Version 6  *****************
//User: Parveen      Date: 10/26/09   Time: 1:09p
//Updated in $/LeapCC/Templates/TimeTable
//report format updated (dynmically grouptype created)
//
//*****************  Version 5  *****************
//User: Parveen      Date: 10/24/09   Time: 11:24a
//Updated in $/LeapCC/Templates/TimeTable
//active timetablelabelId check added
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 9/03/09    Time: 11:31a
//Updated in $/LeapCC/Templates/TimeTable
//Gurkeerat: resolved issue 1416
//
//*****************  Version 3  *****************
//User: Parveen      Date: 9/02/09    Time: 4:45p
//Updated in $/LeapCC/Templates/TimeTable
//button theme base code updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 2/25/09    Time: 11:48a
//Updated in $/LeapCC/Templates/TimeTable
//branchId update
//
//*****************  Version 1  *****************
//User: Parveen      Date: 1/19/09    Time: 6:29p
//Created in $/LeapCC/Templates/TimeTable
//file added
//
//*****************  Version 3  *****************
//User: Parveen      Date: 1/19/09    Time: 4:18p
//Updated in $/Leap/Source/Templates/ScTimeTable
//heading name update
//
//*****************  Version 2  *****************
//User: Parveen      Date: 1/19/09    Time: 4:01p
//Updated in $/Leap/Source/Templates/ScTimeTable
//formating settings
//
//*****************  Version 1  *****************
//User: Parveen      Date: 1/19/09    Time: 2:59p
//Created in $/Leap/Source/Templates/ScTimeTable
//teacher load file added 
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 11/17/08   Time: 1:23p
//Updated in $/Leap/Source/Templates/ScTimeTable
//added time table label functionality to see time table for other labels
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 10/08/08   Time: 3:54p
//Updated in $/Leap/Source/Templates/ScTimeTable
//updated getteacher function
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 9/25/08    Time: 3:25p
//Updated in $/Leap/Source/Templates/ScTimeTable
//updated files
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 9/17/08    Time: 3:50p
//Created in $/Leap/Source/Templates/ScTimeTable
//intial checkin
?>