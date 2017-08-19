<?php
//-------------------------------------------------------
// Purpose: to design the layout for assign to subject.
//
// Author : Rajeev Aggarwal
// Created on : (02.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php

    require_once(BL_PATH.'/helpMessage.inc.php');
?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
              <?php require_once(TEMPLATES_PATH . "/breadCrumb.php");        ?> 
        </td>
    </tr></table>
 
        <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
		<tr>
		 <td valign="top" class="content"> 
		 <table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2">
		 <tr>
			<td valign="top" class="contenttab_row1" align="center">
			<form name="allDetailsForm" action="" method="post" onSubmit="return false;">
				<table border='00' width='100%' cellspacing='0'>
					<?php echo $htmlFunctions->makeStudentDefaultSearch(1); ?>
                    <tr height='5'></tr>
                    <tr height='5'>
                    <td colspan ='9' align="left">Choose the following search criteria for advanced search options <?php 
                        require_once(BL_PATH.'/HtmlFunctions.inc.php');   
                        echo HtmlFunctions::getInstance()->getHelpLink('Subject',HELP_ADVANCED_FILTER);
                    ?></td></tr>
                    <tr height='5'></tr>
					<?php echo $htmlFunctions->makeStudentAcademicSearch(false); ?>
					<tr height='5'></tr>
					<?php echo $htmlFunctions->makeStudentAddressSearch(); ?>
					<tr height='5'></tr>
					<?php echo $htmlFunctions->makeStudentMiscSearch(); ?>
                    <tr>
                     <td class="" align="left"><b>Search from list of<?php require_once(BL_PATH.'/HtmlFunctions.inc.php');   
                        echo HtmlFunctions::getInstance()->getHelpLink('FindStudent',HELP_FIND_STUDENT);?></b></td>
                     <td class=""><b>:</b></td>
                     <td class="" colspan="7" align="left">
                      <input type="radio" name="alumniStudent" value="1" checked="checked" onclick="vanishData();" /><b>Active Students</b> &nbsp;
                      <input type="radio" name="alumniStudent" value="2" onclick="vanishData();" /><b>Alumni Students</b> &nbsp;
                      <input type="radio" name="alumniStudent" value="3" onclick="vanishData();" /><b>All Students</b> &nbsp;
                     </td>
                    </tr>
					<tr>
						<td valign='top' colspan='11' class='' align='center'>
						<input type="hidden" name="degs" value="">
						<input type="hidden" name="degsText" value="">
						<input type="hidden" name="brans" value="">
						<input type="hidden" name="branText" value="">
						
						<input type="hidden" name="subjectId" value="">
						<input type="hidden" name="periods" value="">
						<input type="hidden" name="periodsText" value="">

						<input type="hidden" name="course" value="">
						<input type="hidden" name="courseText" value="">

						<input type="hidden" name="grps" value="">
						<input type="hidden" name="grpsText" value="">
						
						<input type="hidden" name="univs" value="">
						<input type="hidden" name="univsText" value="">

						<input type="hidden" name="citys" value="">
						<input type="hidden" name="citysText" value="">

						<input type="hidden" name="states" value="">
						<input type="hidden" name="statesText" value="">
						
						<input type="hidden" name="cnts" value="">
						<input type="hidden" name="cntsText" value="">

						<input type="hidden" name="hostels" value="">
						<input type="hidden" name="hostelsText" value="">

						<input type="hidden" name="buss" value="">
						<input type="hidden" name="bussText" value="">

						<input type="hidden" name="routs" value="">
						<input type="hidden" name="routsText" value="">
						<input type="hidden" name="quotaText" value="">
						<input type="hidden" name="bloodGroupText" value="">

						
							<input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateAddForm();return false;" />
						</td>
					</tr>
				</table>
				</form> 
			</td>
			</tr>
			<tr id='nameRow' style='display:<?php echo $showData?>'>
                <td class="contenttab_border" height="20">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                        <td class="content_title">Student Detail: </td>
						<td align="right" valign="middle"><input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport()"/>&nbsp;<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printStudentCSV()"/></td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr id='resultRow' style='display:<?php echo $showTitle?>'>
                <td class="contenttab_row1" valign="top" >
                    <div id="scroll2" style="overflow:auto; width:1000px; height:410px; vertical-align:top;">
                       <div id="results" style="width:98%; vertical-align:top;"></div>
                    </div>         
               </td>
          </tr>
		 
          </table>
        </td>
    </tr>
	 <tr id='nameRow2' style='display:<?php echo $showPrint?>'><td colspan='1' align='right' height="35" valign="bottom"><input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport()"/>&nbsp;<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printStudentCSV()"/></td></tr>
    </table>
    </td>
    </tr>
    </table>
<!--Daily Attendance Help  Details  Div-->
<?php  floatingDiv_Start('divHelpInfo','Help','12','','','1'); ?>
<div id="helpInfoDiv" style="overflow:auto; WIDTH:390px; vertical-align:top;"> 
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="">
        <tr>
            <td height="5px"></td></tr>
        <tr>
        <tr>    
            <td width="89%">
                <div id="helpInfo" style="vertical-align:top;" ></div> 
            </td>
        </tr>
    </table>
</div>       
<?php floatingDiv_End(); ?> 
<!-- help icon!-->
<?php  floatingDiv_Start('divHelpInfo','Help','12','','','1'); ?>
<div id="helpInfoDiv" style="overflow:auto; WIDTH:390px; vertical-align:top;"> 
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="">
        <tr>
            <td height="5px"></td></tr>
        <tr>
        <tr>    
            <td width="89%">
                <div id="helpInfo" style="vertical-align:top;" ></div> 
            </td>
        </tr>
    </table>
</div>       
<?php floatingDiv_End(); ?> 
<!--Daily Attendance Help  Details  End -->    
<?php 
// $History: listStudentContents.php $
//
//*****************  Version 10  *****************
//User: Gurkeerat    Date: 12/17/09   Time: 10:19a
//Updated in $/LeapCC/Templates/Student
//Updated breadcrumb according to the new menu structure
//
//*****************  Version 9  *****************
//User: Gurkeerat    Date: 12/05/09   Time: 3:45p
//Updated in $/LeapCC/Templates/Student
//set the alignment of text
//
//*****************  Version 7  *****************
//User: Rajeev       Date: 6/15/09    Time: 7:22p
//Updated in $/LeapCC/Templates/Student
//Enhanced "Admin Student" module as mailed by Puspender Sir.
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 6/02/09    Time: 11:39a
//Updated in $/LeapCC/Templates/Student
//Fixed bugs  1104-1110  and enhanced with student previous academics
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 5/28/09    Time: 3:30p
//Updated in $/LeapCC/Templates/Student
//Added blood group, reference name, sports activity, student previous
//academic, in print report as well as find student tab
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 1/12/09    Time: 5:30p
//Updated in $/LeapCC/Templates/Student
//Updated with Required field, centralized message, left align
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 12/10/08   Time: 5:50p
//Updated in $/LeapCC/Templates/Student
//updated functionality as per CC
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 12/10/08   Time: 10:19a
//Updated in $/LeapCC/Templates/Student
//modified as per CC functionality
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Student
//
//*****************  Version 16  *****************
//User: Rajeev       Date: 9/17/08    Time: 12:01p
//Updated in $/Leap/Source/Templates/Student
//updated back button with class
//
//*****************  Version 15  *****************
//User: Rajeev       Date: 9/17/08    Time: 11:00a
//Updated in $/Leap/Source/Templates/Student
//updated formatting
//
//*****************  Version 14  *****************
//User: Rajeev       Date: 9/03/08    Time: 3:10p
//Updated in $/Leap/Source/Templates/Student
//updated formatting and spacing
//
//*****************  Version 13  *****************
//User: Rajeev       Date: 9/01/08    Time: 4:02p
//Updated in $/Leap/Source/Templates/Student
//updated with default display of student attendance, student print
//report
//
//*****************  Version 12  *****************
//User: Rajeev       Date: 8/22/08    Time: 5:48p
//Updated in $/Leap/Source/Templates/Student
//updated print reports
//
//*****************  Version 11  *****************
//User: Rajeev       Date: 8/21/08    Time: 2:03p
//Updated in $/Leap/Source/Templates/Student
//updated formatting and print reports
//
//*****************  Version 10  *****************
//User: Rajeev       Date: 8/14/08    Time: 3:40p
//Updated in $/Leap/Source/Templates/Student
//added print report function
//
//*****************  Version 9  *****************
//User: Rajeev       Date: 8/13/08    Time: 12:38p
//Updated in $/Leap/Source/Templates/Student
//updated the formatting of student list
//
//*****************  Version 8  *****************
//User: Rajeev       Date: 8/12/08    Time: 12:40p
//Updated in $/Leap/Source/Templates/Student
//updated server side query
//
//*****************  Version 7  *****************
//User: Rajeev       Date: 8/11/08    Time: 10:59a
//Updated in $/Leap/Source/Templates/Student
//updated the formatting and other issues
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 8/06/08    Time: 6:15p
//Updated in $/Leap/Source/Templates/Student
//updated javascript error
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 8/05/08    Time: 6:29p
//Updated in $/Leap/Source/Templates/Student
//remove all the demo issues
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 8/05/08    Time: 12:33p
//Updated in $/Leap/Source/Templates/Student
//updated the label
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 7/12/08    Time: 5:24p
//Updated in $/Leap/Source/Templates/Student
//updated ajax based
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 7/11/08    Time: 6:46p
//Updated in $/Leap/Source/Templates/Student
//updated student photo module
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 7/08/08    Time: 11:19a
//Created in $/Leap/Source/Templates/Student
//intial checkin
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 7/07/08    Time: 12:55p
//Created in $/Leap/Source/Templates/SubjectToClass
//intial checkin
?>
 
    


