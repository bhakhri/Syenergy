<?php 
//this file contains the template of attendace
//
// Author :Jaineesh
// Created on : 22.07.08
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<!--<form method="post" id="frmAttendance" name="frmAttendance">-->
<form name="attendance" id="attendance" onsubmit="return false;">   
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
                <tr>
                    <?php require_once(TEMPLATES_PATH . "/breadCrumb.php");        ?> 
                </tr>
                <tr>
                    <td class="contenttab_internal_rows1" align="right"><nobr><b>Study Period : </b></nobr>
                        <select size="1" class="selectfield" name="semesterDetail" id="semesterDetail" onChange="getAttendance(this.value)">
                            <option value="0" selected="selected">All</option>
                            <?php
                                $studentId = $sessionHandler->getSessionVariable('StudentId');
                                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                echo HtmlFunctions::getInstance()->getStudyPeriodName($studentId,$classId);
                            ?>
                        </select>
                </td>
            </tr>
            </table>
        </td>
    </tr><tr></tr>
    <tr>
        <td valign="top" >
		
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
             <td valign="top" class="content">
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
           
             <tr >
                <td class="contenttab_border2"  valign="top" ><table width="100%" border="0" cellspacing="1" cellpadding="1">
                
						<tr class="row0"  >
							<td valign="top">
									<table border="0" cellspacing="1" cellpadding="0" align="center">
                                        <tr>
                                            <td valign="middle" align="left" class="contenttab_internal_rows" style="padding-right:5px;">
                                             <div id="consolidatedDiv" title="Consolidated View" style="text-decoration:underline;cursor:pointer;" onclick="toggleAttendanceDataFormat(document.getElementById('startDate2').value);">
                                              <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/consolidated.gif" />
                                              </div>
                                            </td>
                                            <td class="contenttab_internal_rows1" valign="middle"><b>Show Attendance Upto </b></td>
                                            <td class="contenttab_internal_rows" valign="middle"><b>:</b></td>
                                            <td>    
                                            <?php
                                               require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                               echo HtmlFunctions::getInstance()->datePicker('startDate2',date('Y-m-d'));
                                            ?></td>
                                            <td width="5"></td>
                                            <td>
                                                <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/showlist.gif" onClick="getAttendance(document.getElementById('startDate2').value);return false;"/></td>
                                        </tr>
                                        </table>
                                        <td>
                                    </tr>
                                    <tr>
		                <td class="contenttab_internal_rows1">
							<font color="red"><b><u>Please Note:</u>&nbsp;</b></font><br>
							<font color="red">1. Medical Leaves are ONLY applicable in the Consolidated View.</font><br/>
							<font color="red">2. Medical Leaves are counted in the Aggregate ONLY if (Total Attendance + Duty Leaves) lie between <?php echo $sessionHandler->getSessionVariable('MEDICAL_LEAVE_CALCULATION_LIMIT'); ?>% and <?php echo $sessionHandler->getSessionVariable('ATTENDANCE_THRESHOLD');?>% </font>
		                </td>
                		</tr>
                                    <tr>
                                        <td  style="padding-right:10px"><div style="overflow:auto;HEIGHT:510px" id="attendanceResultDiv"></div></td>
                                    </tr>
                                    <tr id = 'printDiv2' style='display:none'>
                                        <td colspan='1' align='right' valign="middle">
                                          <!--  <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();return false;"/>&nbsp;
                                            <input type="image"  name="printAttendanceCSV" id='generateCSV' onClick="printCSV();return false;" value="printAttendanceCSV" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" /> -->
                                        </td>
                                    </tr>
                                    
                                    </table>
							<td>
						</tr>
						
						<tr>
							<td valign="top">
                                <div  id="results"></div>
					        </td>
                        </tr>
                </table>          
             </td>
          </tr>
		  <tr height=10></tr> 
			<tr>
				<td class="content_title" title="Print" align="right" style="padding-right:20px">
                    <input type="image" name="print" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport();" />&nbsp;
                    <input type="image" name="printStudentAttendanceSubmit" id='generateCSV' onClick='printCSV();return false' src="<?php echo IMG_HTTP_PATH;?>/excel.gif" value="printStudentAttendanceSubmit" />
                </td>
			</tr>
          </table>
			
        </td>
    </tr>
    
    </table>
   <!--</form>-->
</form>
	
<?php floatingDiv_Start('divDutyLeave','Duty Leaves','',''); ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td height="5px"></td></tr>
    <tr>
        <td width="89%" style="padding-left:5px">
            <div id="scrolLeave1" style="overflow:auto; width:550px; height:300px; vertical-align:top;">
                <div id="div_dutyLeave" style="width:98%; vertical-align:top;"></div>
            </div>
        </td>
    </tr>
</table>
<?php floatingDiv_End(); ?>

<?php floatingDiv_Start('divMedicalLeave','Medical Leaves','',''); ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td height="5px"></td></tr>
    <tr>
        <td width="89%" style="padding-left:5px">
            <div id="scrolLeave211" style="overflow:auto; width:550px; height:300px; vertical-align:top;">
                <div id="div_medicalLeave" style="width:98%; vertical-align:top;"></div>
            </div>
        </td>
    </tr>
</table>
<?php floatingDiv_End(); ?>
	
	
<?php 
//$History: attendanceContents.php $
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 7/10/09    Time: 15:52
//Updated in $/LeapCC/Templates/Student
//Added Detailed(group wise) and Consolidated view(irrespective of groups
//of a subject) of attendance records in student tabs .Now user can
//choose whether to view complete or just consolidated attendance of a
//student.This is also done in print & export to excel version of these
//reports as applicable.
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 9/22/09    Time: 6:43p
//Updated in $/LeapCC/Templates/Student
//change breadcrumb & put department in employee
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 7/31/09    Time: 1:25p
//Updated in $/LeapCC/Templates/Student
//fixed the bugs during self testing
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 12/09/08   Time: 5:45p
//Updated in $/LeapCC/Templates/Student
//modification in code for cc
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Student
//
//*****************  Version 16  *****************
//User: Jaineesh     Date: 9/24/08    Time: 7:08p
//Updated in $/Leap/Source/Templates/Student
//modified for csv
//
//*****************  Version 15  *****************
//User: Jaineesh     Date: 9/16/08    Time: 7:10p
//Updated in $/Leap/Source/Templates/Student
//fix bug
//
//*****************  Version 14  *****************
//User: Jaineesh     Date: 9/13/08    Time: 12:02p
//Updated in $/Leap/Source/Templates/Student
//modify for date
//
//*****************  Version 13  *****************
//User: Jaineesh     Date: 9/09/08    Time: 11:38a
//Updated in $/Leap/Source/Templates/Student
//fixed the bugs
//
//*****************  Version 12  *****************
//User: Jaineesh     Date: 9/06/08    Time: 6:43p
//Updated in $/Leap/Source/Templates/Student
//fixation bugs
//
//*****************  Version 11  *****************
//User: Administrator Date: 9/05/08    Time: 7:25p
//Updated in $/Leap/Source/Templates/Student
//bugs fixation
//
//*****************  Version 10  *****************
//User: Jaineesh     Date: 8/29/08    Time: 10:42a
//Updated in $/Leap/Source/Templates/Student
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 8/28/08    Time: 6:34p
//Updated in $/Leap/Source/Templates/Student
//modified in design template
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 8/26/08    Time: 12:15p
//Updated in $/Leap/Source/Templates/Student
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 8/22/08    Time: 3:18p
//Updated in $/Leap/Source/Templates/Student
//modified for print button
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 8/20/08    Time: 12:19p
//Updated in $/Leap/Source/Templates/Student
//modified in template
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 8/18/08    Time: 5:34p
//Updated in $/Leap/Source/Templates/Student
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 8/14/08    Time: 3:53p
//Updated in $/Leap/Source/Templates/Student
//modified for print
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 8/04/08    Time: 6:17p
//Updated in $/Leap/Source/Templates/Student
//modification for alignment
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 8/01/08    Time: 12:38p
//Updated in $/Leap/Source/Templates/Student
//modified to calculate percentage
//
//*****************  Version 1  *****************
//User: Administrator Date: 7/28/08    Time: 7:10p
//Created in $/Leap/Source/Templates/Student
//show the template of attendance
//

?>
