<?php
//-------------------------------------------------------
// Purpose: to design the layout for assign to subject.
//
// Author : Dipanjan Bhattacharjee
// Created on : (14.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
 
 
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
            <tr>
                <td height="10"></td>
            </tr>
            <tr>
                <td valign="top">Student Activity &nbsp;&raquo;&nbsp; Student Attendance</td>
                 <td align="right" style="padding-right:10px">
                  <a href="searchStudent.php?class=<?php echo $REQUEST_DATA['class']?>&subject=<?php echo $REQUEST_DATA['subject']?>&group=<?php echo $REQUEST_DATA['group']?>&studentRollNo=<?php echo $REQUEST_DATA['studentRollNo']?>" ><U>Back</U></a>
				 </td>
            </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" >
            <tr>
             <td valign="top" class="content">

             <table width="100%" border="0" cellspacing="0" cellpadding="0" height="400">
			 
             <tr>
                <td class="contenttab_border" height="20">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                        <td class="contenttab_row1"><span class="content_title">Student Detail:</span>
						&nbsp;<B><U>Name:</U></B>&nbsp;<?php echo parseOutput($studentDataArr[0]['firstName'])." ".parseOutput($studentDataArr[0]['lastName']);?>
						&nbsp;&nbsp;<B><U>University:</U></B>&nbsp;<?php echo parseOutput($studentCourseDataArr[0]['universityName']) ; ?>
						&nbsp;&nbsp;<B><U>Degree:</U></B>&nbsp;<?php echo parseOutput($studentCourseDataArr[0]['degreeName']); ?>
						&nbsp;&nbsp;<B><U>Branch:</U></B>&nbsp;<?php echo parseOutput($studentCourseDataArr[0]['branchName']) ; ?>
						&nbsp;&nbsp;<B><U>Batch:</U></B>&nbsp;<?php echo parseOutput($studentCourseDataArr[0]['batchName']); ?>
						&nbsp;&nbsp;<B><U>Admission Date:</U></B>&nbsp;<?php echo parseOutput((UtilityManager::formatDate($studentDataArr[0]['dateOfAdmission'])));?></td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" >
               <!--Student  Attendance Part-->
                 <table width="100%" border="0" cellspacing="1" cellpadding="1">
                        <tr class="row0">
                            <td valign="top">
                            <?php 
                             //get current date
                             $thisDate=date('Y')."-".date('m')."-".date('d');
                            ?>
                            <table width="100%" border="0" cellspacing="1" cellpadding="1">
                            <tr>
                                <td valign="top"><b>From Date</b></td>
                                <td valign="top">
                                <?php
                                       require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                       echo HtmlFunctions::getInstance()->datePicker('startDate',$thisDate);
                                ?>
                                </td>
                                <td valign="top"><b>To Date</b></td>
                                <td valign="top">
                                 <?php
                                       require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                       echo HtmlFunctions::getInstance()->datePicker('endDate',$thisDate);
                                 ?>
                                </td>    
                                <td valign="top"><img src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="getAttendance(<?php echo $studentDataArr[0]['classId']?>,<?php echo $REQUEST_DATA['id']?>,document.getElementById('startDate').value,document.getElementById('endDate').value);return false;"/></td>
                            </tr>
                            </table>
                            <td>
                        </tr>
                        <tr>
                         <td valign="top">
                         <div id="results">
                         </div>
                            </td>
                        </tr>
                         
                        </table>
                   <!--Student  Attendance Part Ends-->                             
                   </td>
              </tr>

				
          </table>
  
        </td>
    </tr>
    
    </table>
    </td>
    </tr>
    </table>
 </form>
<?php 
// $History: scStudentDetailAttendanceContents.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Teacher/ScStudentActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/10/08    Time: 6:37p
//Created in $/Leap/Source/Templates/Teacher/ScStudentActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/09/08    Time: 5:19p
//Created in $/Leap/Source/Templates/Teacher/StudentActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/31/08    Time: 7:27p
//Created in $/Leap/Source/Templates/Teacher/StudentActivity
?>
 
    


