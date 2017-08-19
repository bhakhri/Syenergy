<?php 
//-------------------------------------------------------
//  This File outputs test time period Form
//
// Author :Arvind Singh Rawat
// Created on : 22-Oct-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td valign="top" class="title">
			 <?php require_once(TEMPLATES_PATH . "/breadCrumb.php"); ?>   
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
									<form name="studentAttendanceForm" action="" method="post" onSubmit="return false;">
                                        <table width="70%" align="center" border="0" cellpadding="0px" cellspacing="0" >
                                            <tr>
                                               <td class="padding" align="left" nowrap>
                                                    <strong>From Date</strong>  
                                                </td>
                                                <td class="padding" align="left" nowrap><strong>:</strong></td>
                                                <td class="padding" align="left" nowrap>
                                                    <?php 
                                                        require_once(BL_PATH.'/HtmlFunctions.inc.php');        
                                                        echo HtmlFunctions::getInstance()->datePicker('fromDate',date('Y-m-d'));
                                                    ?>&nbsp;&nbsp;&nbsp;&nbsp;
                                                </td>    
                                                <td class="padding" align="left" nowrap>         
                                                    <strong>To Date</strong>
                                                </td>
                                                <td class="padding" align="left" nowrap><strong>:</strong></td>
                                                <td class="padding" align="left" nowrap>
                                                    <?php 
                                                        require_once(BL_PATH.'/HtmlFunctions.inc.php'); 
                                                        echo HtmlFunctions::getInstance()->datePicker('toDate',date('Y-m-d'));
                                                    ?>&nbsp;&nbsp;&nbsp;&nbsp;
                                                </td>
                                                <td class="padding" align="left" nowrap>
                                                    <strong>Degree</strong>
                                                </td>
                                                <td class="padding" align="left" nowrap><strong>:</strong></td>
                                                <td align="left" class="padding" nowrap colspan="2">
<select size="1" class="htmlElement" name="degreeId" id="degreeId" style="width:218px;" onchange="hideResults(); getSubjectData(); return false;" >
                                                   <option value="all" selected="selected">All</option>      
                                                   <?php 
                                                        require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                        echo HtmlFunctions::getInstance()->getClassWithStudyPeriod($REQUEST_DATA['degreeId']==''?$REQUEST_DATA['degreeId'] : $REQUEST_DATA['degreeId']);?>
                                                   ?>
                                                </select>
                                               </td>
                                               </tr>
                                               <tr>  
                                                <td class="padding" align="left" nowrap> 
                                                    <strong>Subject</strong>
                                                </td>
                                                <td class="padding" align="left" nowrap><strong>:</strong></td>
                                                <td class="padding" align="left" nowrap>
                                                    <select size="1" class="htmlElement" name="subjectId" id="subjectId" style="width:90px;"  onchange="getSubjectGroups();">
                                                        <option value="all">Select</option>
                                                    </select>
                                                </td>    
                                                <td class="padding" align="left" nowrap> 
                                                    <strong>Group</strong>&nbsp;
                                                 </td>
                                                <td class="padding" align="left" nowrap><strong>:</strong></td>
                                                <td align="left" class="padding" nowrap>   
                                                    <select name="groupId"  class="htmlElement" id="groupId" style="width:90px;" >
                                                       <option value="all">Select</option>
                                                    </select>
                                                    <!-- 
                                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                                        <nobr><b>Teacher: </b></nobr>
                                                        <select size="1" class="selectfield" name="teacherId" id="teacherId"  class="inputbox1">
                                                        <option value=''>Select</option>
                                                            <?php
                                                                //require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                                //echo HtmlFunctions::getInstance()->getTeacher($REQUEST_DATA['teacher']);
                                                            ?>
                                                        </select>
                                                    -->
                                                    <td class="padding" align="left" nowrap> 
                                                    <strong>Test Type Category</strong>&nbsp;
                                                    <td class="padding" align="left" nowrap><strong>:</strong></td>
                                                    <td align="left" class="padding" nowrap>   
                                                    <select name="testTypeCategoryId" id="testTypeCategoryId" style="width:218px;" class="htmlElement">
                                                    <option value="all" selected="selected">All</option>
                                                    <?php
                                                        require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                        echo HtmlFunctions::getInstance()->getTestTypeCategory('',$REQUEST_DATA['testTypeCategoryId']);
                                                    ?>
                                                    </select>
                                                 </td>
                                                 <td align="left" class="padding" nowrap>
                                                    &nbsp;&nbsp;
                                                     <input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateAddForm(this.form);return false;" />
                                                    
                                                </td>
                                              </tr>
                                            <tr>
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
											<td colspan="1" class="content_title">Date Wise Test Report :</td>
											<td class="content_title" align="right">
                  <input type="image" name="print" value="print" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;
                  <input type="image" name="printCSV" value="printCSV" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" onClick="printReportCSV()" />&nbsp;
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
              <input type="image" name="print" value="print" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;
              <input type="image" name="printCSV" value="printCSV" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" onClick="printReportCSV()" />&nbsp;
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
//$History: datewiseTestReportContents.php $
//
//*****************  Version 7  *****************
//User: Parveen      Date: 11/24/09   Time: 3:16p
//Updated in $/LeapCC/Templates/StudentReports
//report heading name udpated
//
//*****************  Version 6  *****************
//User: Parveen      Date: 11/24/09   Time: 3:07p
//Updated in $/LeapCC/Templates/StudentReports
//print & excel button added in top  
//
//*****************  Version 5  *****************
//User: Parveen      Date: 11/23/09   Time: 5:36p
//Updated in $/LeapCC/Templates/StudentReports
//look & feel updated
//
//*****************  Version 4  *****************
//User: Parveen      Date: 11/14/09   Time: 3:25p
//Updated in $/LeapCC/Templates/StudentReports
//class base format updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 5/25/09    Time: 4:43p
//Updated in $/LeapCC/Templates/StudentReports
//report format update 
//
//*****************  Version 1  *****************
//User: Parveen      Date: 5/19/09    Time: 5:22p
//Created in $/LeapCC/Templates/StudentReports
//file added
//
//*****************  Version 3  *****************
//User: Arvind       Date: 10/23/08   Time: 11:46a
//Updated in $/Leap/Source/Templates/ScStudentReports
//removed onBLUr event on section
//
//*****************  Version 2  *****************
//User: Arvind       Date: 10/22/08   Time: 6:36p
//Updated in $/Leap/Source/Templates/ScStudentReports
//modified the name of report
//
//*****************  Version 1  *****************
//User: Arvind       Date: 10/22/08   Time: 5:41p
//Created in $/Leap/Source/Templates/ScStudentReports
//initial checjkin
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 9/19/08    Time: 6:46p
//Updated in $/Leap/Source/Templates/ScStudentReports
//changed the flow, and picked classes on section selection.
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 9/18/08    Time: 5:21p
//Created in $/Leap/Source/Templates/ScStudentReports
//file added for student attendance report - sc
//
?>
