<?php 
//This file creates Html Form output "ListStudentReports " Module 
//
// Author :Arvind Singh Rawat
// Created on : 8-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(BL_PATH.'/HtmlFunctions.inc.php');
$htmlFunctions = HtmlFunctions::getInstance();
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
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
             <tr>
                <td class="contenttab_border" height="20">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                        <td width="40%" nowrap class="content_title">Student Lists Report : </td>
                        <td width="60%" nowrap class="content_title" align="right"></td>
                    </tr>
                    </table>
                </td>
             </tr>
     <tr>
        <td class="contenttab_row" valign="top" >&nbsp;
             <table width="100%" border="0" cellspacing="0" cellpadding="0" class="" >
                <tr><a id="lk1" class="set_default_values">Set Default Values for Report Parameters</a>
                    <td valign="top" class="content">
                        <!-- form table starts -->
                       <table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2">
                            <tr>
                                <td valign="top" class="contenttab_row1" align="center">
                                    <form name="allDetailsForm" action="" method="post" onSubmit="return false;">
                                        <table border='0' width='100%' cellspacing='0'>
                                            <?php echo $htmlFunctions->makeStudentDefaultSearch(); ?>
                                            <tr height='5'></tr>
                                            <?php echo $htmlFunctions->makeStudentAcademicSearch(); ?>
                                            <tr height='5'></tr>
                                            <?php echo $htmlFunctions->makeStudentAddressSearch(); ?>
                                            <tr height='5'></tr>
                                            <?php echo $htmlFunctions->makeStudentMiscSearch(); ?>
											<tr height='5'></tr>
                                            <tr>
												<td valign="top" colspan="10" align='left'>
												<table border='0px' cellspacing='0px' cellpadding="0px">
												<tr>
                                                    <td  valign="middle" height="35px" nowrap align="left"><B>Total No. of Students:</B>&nbsp;</td>
                                                    <td  valign="middle" nowrap colspan="10">
                                                        <div id='totalStudents'>
                                                        <?php
                                                          $condition='';
                                                         // $condition = " AND b.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."' AND b.sessionId= '".$sessionHandler->getSessionVariable('SessionId')."'";
                                                $totalRecord = CommonQueryManager::getInstance()->getTotalStudentsList($condition);
                                                echo $totalRecord[0]['cnt'];
                                                echo "&nbsp;&nbsp;<b>(Active:</b>&nbsp;&nbsp;".$totalRecord[0]['active'];
                                                echo "&nbsp;&nbsp;<b>InActive:</b>&nbsp;&nbsp;".$totalRecord[0]['unactive']."<b>
						&nbsp;&nbsp;<b>Alumni:</b>&nbsp;&nbsp;<b>".$totalRecord[0]['alumni'].")&nbsp;&nbsp;";
                                                        ?>
                                                        </div>
                                                    </td>
                                                 </tr>
                                                 <tr>   
                                                    <td valign="middle" nowrap><B>Starting Record No.:</B>&nbsp;</td>
                                                    <td valign="middle"  nowrap>
     <input type="text" id="startingRecord" nameid="startingRecord" class="inputbox1" maxlength="5" value="1" style="width:50px">
                                                    </td>
                                                    <td valign="middle" style="padding-left:10px" nowrap>
                                                        <B>Show No. of Records in Report:</B>&nbsp;</td>
                                                    <td valign="middle"  nowrap>
     <input  id="totalRecords" name="totalRecords" type="text" class="inputbox1" maxlength="5" value="500" style="width:50px">
                                                    </td>
													
                                                    <td valign="middle" style="padding-left:10px" nowrap>
                                                        <B>Student Status:</B>&nbsp;
                                                    </td>
                               <td valign="middle" nowrap>                                                                                                      
           <input class="inputbox1" type="radio" id="studentStatusId" name="studentStatusId" checked="checked" value="1" />Active&nbsp;
           <input class="inputbox1" type="radio" id="studentStatusId" name="studentStatusId" value="2" />InActive
           <input class="inputbox1" type="radio" id="studentStatusId" name="studentStatusId"  value="3" />Alumni&nbsp;
                               </td>
                                                </tr> 	
												</table>
												</td>
											</tr>
                                            <tr>
                                                <td valign='top' colspan='8' class='' align='center'>
                                      <!--              <input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateAddForm();return false;" /> -->
                                                </td>
                                            </tr>
                                        </table>
                                    </form>
                                </td>
                            </tr>
                        </table>
    <!-- form table ends --></td>
    </tr>
    <tr>
<td valign="top" >      
  <form name="studentListReport" action="" method="post">
<table width="100%" align="center" border="0" > 
    <tr>
    <td colspan="6">&nbsp;&nbsp;&nbsp;&nbsp;</td>
    </tr>
  </tr>
  <tr class="rowheading">
    <td colspan="6">
      <table width="100%" align="center" border="0" > 
        <tr>
           <th scope="col" align="left" width="20%" nowrap class="searchhead_text"><nobr>Include in Report</nobr></th>
           <th scope="col" align="left" width="20%" nowrap class="searchhead_text">
             <input type="checkbox" id="incAll" name="incAll" value="1" >&nbsp;<b>Show Students with Empty Data</b>  
           </th>
           <th scope="col" align="left" width="60%" nowrap class="searchhead_text" style="padding-left:20px">
             <input type="checkbox" id="incAllInsitute" name="incAllInsitute" value="1" >&nbsp;<b>Search for all institutes</b>  
           </th>
        </tr>
      </table>      
    </td>
  </tr>
 
   <tr >
   <td colspan="6" class="row0" align="left">
 <table align="center" width="100%" >
 <tr class="row1">
     <td ><input type="checkbox" name="check[]" id="universityRollNo" value="1">
    Univ Roll No. </td>
    <td><input type="checkbox"  name="check[]" id="rollNoForm" value="1">
      College Roll No. </td>
    <td ><input type="checkbox" name="check[]" id="regNoForm" value="1">
      Reg. No.</td>
    <td ><input type="checkbox" name="check[]" id="classNameForm" value="1">
      Class Name</td>
 </tr>
 <tr class="row1">
    <td ><input type="checkbox" name="check[]" id="firstName" value="1">
      Name</td>
    <td><input type="checkbox" name= "check[]" id="fatherName" value="1">
      Father's Name </td>
    <td><input type="checkbox" name= "check[]" id="motherName" value="1"> 
      Mothers Name </td>
    <td><input type="checkbox" name= "check[]" id="guardianName" value="1">
      Guardian's Name </td>
  </tr>    
   <tr class="row1">
    <td><input type="checkbox" name= "check[]" id="bloodGroupForm" value="1"> 
      Blood Group </td>
    <td><input type="checkbox" name= "check[]" id="studentEmail" value="1">
      Email</td>
    <td><input type="checkbox" name= "check[]" id="dateOfBirth" value="1"> 
      D.O.B </td>
    <td><input type="checkbox" name= "check[]" id="studentGender" value="1">
      Gender</td>
    </tr>
      <tr class="row1">
    <td><input type="checkbox" name= "check[]" id="stateName" value="1">
      Domicile</td>
    <td><input type="checkbox" name= "check[]" id="quotaName" value="1">
      Quota</td>
    <td><input type="checkbox" name= "check[]" id="isLeet" value="1">
      Leet</td>
    <td><input type="checkbox" name= "check[]" id="corrCityId" value="1">
      City</td>
    </tr>
     <tr class="row1">
    <td><input type="checkbox" name= "check[]" id="countryName" value="1">
      Nationality</td>
    <td colspan="1"><input type="checkbox" name= "check[]" id="managementReference" value="1">
      Management Reference </td>
    <td><input type="checkbox" name= "check[]" id="studentRemarks" value="1">
Remarks </td>
    <td colspan="1"><input type="checkbox" name= "check[]" id="studentPhoto" value="1">
      Student Photo</td>
    </tr>
    <tr class="row1">
      <td><input type="checkbox" name= "check[]" id="corrAddress1" value="1">
      Correspondance</td>
    <td colspan="1"><input type="checkbox" name= "check[]" id="permAddress1" value="1">
      Permanent</td>
    <td colspan="1"><input type="checkbox" name= "check[]" id="studentInActive" value="1">
      Student Status</td>
    <td><input type="checkbox" name= "check[]" id="feeReceiptNo" value="1">
      Fee Receipt No.</td>  
    </tr>
	<tr class="row1">
		<td><input type="checkbox" name= "check[]" id="dateOfAdmission" value="1"> Date of Admission</td>
		 <td><input type="checkbox" name= "check[]" id="groupIdForm" value="1">Group </td>
		<td><input type="checkbox" name= "check[]" id="instituteName" value="1"> Institute Name</td>
		<td></td>
	</tr>
<tr class="row1" >
    <td colspan="2" class="searchhead_text">Phone No's </td>
    <td class="searchhead_text">Email Id's</td>
    <td class="searchhead_text">Address</td>
 </tr>
 <tr class="row1">
    <td colspan="2"><input type="checkbox" name= "check[]" id="studentMobileNo" value="1"> 
      Student &nbsp;&nbsp;&nbsp;<input type="checkbox" name= "check[]" id="fatherMobileNo" value="1">
      Father &nbsp;&nbsp;&nbsp;<input type="checkbox" name= "check[]" id="motherMobileNo" value="1">
      Mother &nbsp;&nbsp;&nbsp;<input type="checkbox" name= "check[]" id="guardianMobileNo" value="1">
      Guardian</td>
     <td><input type="checkbox" name= "check[]" id="studentEmailId" value="1"> 
      Student &nbsp;&nbsp;&nbsp;<input type="checkbox" name= "check[]" id="fatherEmailId" value="1">
      Father &nbsp;&nbsp;&nbsp;<input type="checkbox" name= "check[]" id="motherEmailId" value="1">
      Mother &nbsp;&nbsp;&nbsp;<input type="checkbox" name= "check[]" id="guardianEmailId" value="1">
      Guardian</td>  
     <td><input type="checkbox" name= "check[]" id="fatherAddress1" value="1">
     Father &nbsp;&nbsp;&nbsp;<input type="checkbox" name= "check[]" id="motherAddress1" value="1">
      Mother&nbsp;&nbsp;&nbsp;<input type="checkbox" name= "check[]" id="guardianAddress1" value="1">
      Guardian</td>
    </tr>
 <tr class="row1">
    <td colspan="2" class="searchhead_text">User Name</td>
    <td colspan="2" class="searchhead_text">Using Facility </td>
    </tr>
<tr class="row1">
   
	<td colspan="2">
		<input type="checkbox" name= "check[]" id="studentUserName" value="1"> Student &nbsp;&nbsp;&nbsp;
		<input type="checkbox" name= "check[]" id="fatherUserName" value="1">Father &nbsp;&nbsp;&nbsp;
		<input type="checkbox" name= "check[]" id="motherUserName" value="1">Mother &nbsp;&nbsp;&nbsp;
		<input type="checkbox" name= "check[]" id="guardianUserName" value="1">Guardian
	</td>
    <td><input type="checkbox" name= "check[]" id="busStopIdForm" value="1">
      Bus Stop &nbsp;&nbsp;&nbsp;<input type="checkbox" name= "check[]" id="busRouteForm" value="1">
      Bus Route</td>
    <td><input type="checkbox" name= "check[]" id="hostelNameForm" value="1">
      Hostel Name&nbsp;&nbsp;&nbsp;<input type="checkbox" name= "check[]" id="hostelRoomNoForm" value="1">
      Hostel Room No</td>
</tr>
 <tr class="row1" >
    <td colspan="4" class="searchhead_text">Pre Admission</td>
 </tr>
  <tr class="row1">
    <td colspan="5">
        <?php echo REQUIRED_FIELD ?> Include All Pre Admission Details (i.e. RollNo, Session, Institute, Board/University, %age/CGPA, Stream)
        <input type="radio" name="includePreAdmission" id="includePreAdmission1" checked="checked" value="0"> No&nbsp;&nbsp;
        <input type="radio" name="includePreAdmission" id="includePreAdmission2" value="1"> Yes
    </td>
 </tr>
 <tr class="row1">
    <td><input type="checkbox" name= "check[]" id="mks_1" value="1"> Marks in 10th<?php echo REQUIRED_FIELD ?></td>
    <td><input type="checkbox" name= "check[]" id="mks_2" value="1"> Marks in 12th<?php echo REQUIRED_FIELD ?></td>
    <td><input type="checkbox" name= "check[]" id="mks_3" value="1"> Marks in Graduation<?php echo REQUIRED_FIELD ?></td>
    <td><input type="checkbox" name= "check[]" id="mks_4" value="1"> PG (if any)<?php echo REQUIRED_FIELD ?>
 </tr>
 <tr class="row1">
    <td><input type="checkbox" name= "check[]" id="mks_5" value="1"> Any Diploma<?php echo REQUIRED_FIELD ?></td>   
    <td><input type="checkbox" name= "check[]" id="compRollNo" value="1"> Comp. Exam. Roll No.</td>
    <td><input type="checkbox" name= "check[]" id="compExamBy" value="1"> Comp. Exam. By</td>
    <td><input type="checkbox" name= "check[]" id="compRank" value="1"> Rank</td>
 <tr>   

 
 <tr class="row0">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
</tr>   
 </table>
</td>
</tr>
<tr >
    <td align="center" colspan="6" ><span style="padding-right:10px">
     <input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="return validateAddForm(this.value);return false;" />
      <a id='generateCSV2' href='javascript:void(0);' ><input type="image"  name="studentPrintSubmit" onClick="return validateAddForm(this.value);return false;" value="studentPrintCSV" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" /></a>
      </td>
    </tr>
</table>           
 </form>            </td>
          </tr>
          
          </table>
        </td>
    </tr>
</table>


    
    </table>

<?php 
//$History: listStudentListsContents.php $
//
//*****************  Version 17  *****************
//User: Parveen      Date: 12/24/09   Time: 10:51a
//Updated in $/LeapCC/Templates/StudentReports
//look & feel updated
//
//*****************  Version 16  *****************
//User: Parveen      Date: 12/23/09   Time: 6:39p
//Updated in $/LeapCC/Templates/StudentReports
//role permission check & format updated
//
//*****************  Version 15  *****************
//User: Gurkeerat    Date: 12/17/09   Time: 10:19a
//Updated in $/LeapCC/Templates/StudentReports
//Updated breadcrumb according to the new menu structure
//
//*****************  Version 14  *****************
//User: Parveen      Date: 12/02/09   Time: 3:18p
//Updated in $/LeapCC/Templates/StudentReports
//radio button add (studentStatus) 
//
//*****************  Version 13  *****************
//User: Parveen      Date: 12/02/09   Time: 1:20p
//Updated in $/LeapCC/Templates/StudentReports
//student Status field checks updated
//
//*****************  Version 12  *****************
//User: Parveen      Date: 12/02/09   Time: 11:17a
//Updated in $/LeapCC/Templates/StudentReports
//look and feel updated
//
//*****************  Version 11  *****************
//User: Parveen      Date: 12/02/09   Time: 11:04a
//Updated in $/LeapCC/Templates/StudentReports
//format updated (active/inactive student label added)
//
//*****************  Version 10  *****************
//User: Parveen      Date: 11/14/09   Time: 5:47p
//Updated in $/LeapCC/Templates/StudentReports
//link ref. updated
//
//*****************  Version 9  *****************
//User: Parveen      Date: 7/13/09    Time: 11:22a
//Updated in $/LeapCC/Templates/StudentReports
//new enhancements Fee receipt number & alignment updated
//
//*****************  Version 8  *****************
//User: Parveen      Date: 7/11/09    Time: 12:30p
//Updated in $/LeapCC/Templates/StudentReports
//template layout updated
//
//*****************  Version 7  *****************
//User: Parveen      Date: 7/11/09    Time: 12:13p
//Updated in $/LeapCC/Templates/StudentReports
//new enhacments added (bloodGroup, regNo, className added)
//
//*****************  Version 6  *****************
//User: Parveen      Date: 6/11/09    Time: 2:18p
//Updated in $/LeapCC/Templates/StudentReports
//table formatting & alignment settings update
//
//*****************  Version 5  *****************
//User: Parveen      Date: 6/11/09    Time: 1:59p
//Updated in $/LeapCC/Templates/StudentReports
//added search parameter for students to fetch number of student to be
//shown in print report
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 5/27/09    Time: 4:58p
//Updated in $/LeapCC/Templates/StudentReports
//added search parameter for students to fetch number of student to be
//shown in print report
//
//*****************  Version 3  *****************
//User: Parveen      Date: 2/23/09    Time: 6:14p
//Updated in $/LeapCC/Templates/StudentReports
//code update
//
//*****************  Version 2  *****************
//User: Parveen      Date: 2/21/09    Time: 2:43p
//Updated in $/LeapCC/Templates/StudentReports
//update formatting
//

?>
