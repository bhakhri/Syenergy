<?php 
//-------------------------------------------------------
//  This File contains html form for all Student Internal Reappear Contents
//
//
// Author :PArveen Sharma
// Created on : 13-Sep-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(BL_PATH.'/HtmlFunctions.inc.php');
$htmlFunctions = HtmlFunctions::getInstance();

$studentName1 = $sessionHandler->getSessionVariable('StudentName'); 
?>

<form action="" method="POST" name="allDetailsForm" id="allDetailsForm" onSubmit="return false;">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
                <tr>
                    <td height="10"></td>
                </tr>
                <tr>
                    <td valign="top" colspan="2">Student Internal Re-appear Form</td>
                </tr>
            </table>
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
                               <td class="contenttab_row" align="center" width="100%">
                                 <nobr><b>REGISTRATION FORM FOR RE-APPEAR (INTERNAL)</b></nobr><br><br> 
                                 <table width="100%" border="0" cellspacing="0px" cellpadding="0px" class="" align="center"> 
                                 <tr>
                                   <td  class="contenttab_internal_rows" width="9%" nowrap><nobr><b>Date of Registration</b></nobr></td>
                                   <td  class="padding" nowrap width="1%"><nobr><b>:</b></nobr></td>
                                   <td  class="contenttab_internal_rows" width="40%" colspan="4" nowrap><nobr>&nbsp;
                                   <?php
                                        $dt = date('Y-m-d'); 
                                        echo UtilityManager::formatDate($dt); 
                                   ?></nobr></td> 
                                  </tr>
                                  <tr>
                                   <td  class="contenttab_internal_rows" width="9%" nowrap><nobr><b>Name of Student</b></nobr></td>
                                   <td  class="padding" nowrap width="1%"><nobr><b>:</b></nobr></td>
                                   <td  class="contenttab_internal_rows" width="40%" nowrap><nobr>&nbsp;
                                   <?php echo $studentName1; ?></nobr></td> 
                                   <td  class="contenttab_internal_rows" width="9%" nowrap><nobr><b>Institute Roll No.</b></nobr></td>
                                   <td  class="padding" nowrap width="1%"><nobr><b>:</b></nobr></td>
                                   <td  class="contenttab_internal_rows" width="40%" nowrap><nobr>&nbsp;
                                   <?php echo parseOutput($studentInformationArray[0]['rollNo']); ?></nobr></td> 
                                  </tr>
                                  <tr>
                                       <td  class="contenttab_internal_rows" nowrap><nobr><b>Univ. Roll No.</b></nobr></td>
                                       <td  class="padding" nowrap><nobr><b>:</b></nobr></td>
                                       <td  class="contenttab_internal_rows" nowrap><nobr>&nbsp;
                                       <?php echo parseOutput($studentInformationArray[0]['universityRollNo']); ?></nobr></td> 
                                       <td  class="contenttab_internal_rows" nowrap><nobr><b>Course / Branch</b></nobr></td>
                                       <td  class="padding" nowrap><nobr><b>:</b></nobr></td>
                                       <td  class="contenttab_internal_rows" nowrap><nobr>&nbsp;
                                       <?php 
                                            if($studentInformationArray[0]['degreeName']!='') {
                                                echo parseOutput(strtoupper($studentInformationArray[0]['degreeName']));
                                             }
                                             
                                             if($studentInformationArray[0]['branchName']!='') {  
                                                echo " / ".strtoupper($studentInformationArray[0]['branchName']);
                                             }
                                       ?></nobr></td> 
                                  </tr>
                                  <tr>
                                       <td  class="contenttab_internal_rows" nowrap><nobr><b>Current Semester / Session</b></nobr></td>
                                       <td  class="padding" nowrap><nobr><b>:</b></nobr></td>
                                       <td  class="contenttab_internal_rows" nowrap colspan="4"><nobr>&nbsp;
                                       <?php 
                                            echo parseOutput($studentInformationArray[0]['periodName']); 
                                            if($studentInformationArray[0]['branchName']!='') {  
                                                echo " / ".$sessionHandler->getSessionVariable('SessionName');
                                            }
                                       ?></nobr>
                                       </td> 
                                  </tr>
                             <tr>   
                                <td valign="top" class="contenttab_row1" align="center" colspan="6" width="100%">
                                   <table border="0" cellspacing="0" cellpadding="0px" width="30%">
                                    <tr>    
                                    <td  class="contenttab_internal_rows" nowrap><nobr><b>
                                    Choose class from  which subjects to re-appear<?php echo REQUIRED_FIELD ?></b></nobr></td>
                                    <td  class="padding" nowrap><nobr><b>:</b></nobr></td>           
                                    <td  class="contenttab_internal_rows" nowrap >  
                                    <select size="1" class="selectfield" name="classId" id="classId" onChange="hideResults(); return false;" style="width:250px">
                                      <option value="" selected="selected">Select</option>
                                        <?php
                                           require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                           echo HtmlFunctions::getInstance()->getPastClassData();
                                        ?>
                                    </select>
                                    <td class="padding" colspan="3" nowrap><nobr>  
                                    &nbsp;&nbsp;
                                    <input type="image" name="reSubmit" value="reSubmit" src="<?php echo IMG_HTTP_PATH;?>/show_subject_list.gif" onClick="return validateAddForm(); return false;" />  
                                    </nobr>
                                    <td>
                                    </tr>
                      <tr id='nameRow4' style='display:none;'>
                          <td  class="contenttab_internal_rows" nowrap><nobr><b>
                            Cause of Detention / Re-appear<?php echo REQUIRED_FIELD ?>&nbsp;</b></nobr>
                            </td>
                            <td  class="padding" nowrap><nobr><b>:</b></nobr></td>           
                            <td  class="contenttab_internal_rows" nowrap colspan="4">
                            <nobr><input class="inputbox1" type="radio" id="assignmentChk"  name="radioCause" value="1">&nbsp;Assignment Work 
                      &nbsp;&nbsp;<input class="inputbox1" type="radio" id="midSemesterChk" name="radioCause" value="1">&nbsp;Mid Semester Tests
                      &nbsp;&nbsp;<input class="inputbox1" type="radio" id="attendanceChk"  name="radioCause" value="1">Attendance
                     <!-- 
                      <input class="inputbox1" type="checkbox" id="assignmentChk" name="assignmentChk" value="1">&nbsp;Assignment Work 
                      &nbsp;&nbsp;<input class="inputbox1" type="checkbox" id="midSemesterChk" name="midSemesterChk" value="1">&nbsp;Mid Semester Tests
                      &nbsp;&nbsp;<input class="inputbox1" type="checkbox" id="attendanceChk" name="attendanceChk" value="1">Attendance
                     --> 
                                </nobr>
                            </td>
                        </tr>    
                                  </table>  
                               </td>     
                            </tr>
                            <tr id='nameRow' style='display:none;'>
                                <td class="" height="20" colspan="6">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="contenttab_border">
                                        <tr>
                                            <td colspan="1" class="content_title" align="left">Subject Detials :</td>
                                            <td colspan="1" class="content_title" align="right">
                                                <input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/submit.gif" onClick="addReappearSubjects();" />
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr id='resultRow' style='display:none;'>
                                <td class='contenttab_row'  colspan="6">
                                    <div id="resultsDiv"></div>
                                </td>
                            </tr>
                            <tr id='nameRow2' style='display:none;'>
                                <td class="" height="20"  colspan="6">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="">
                                        <tr>
                                            <td colspan="2" class="content_title" align="right">
                                                <input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/submit.gif" onClick="addReappearSubjects();" />
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
            $studentId = $sessionHandler->getSessionVariable('StudentId');  
            $studentCurrentClassId = $sessionHandler->getSessionVariable('ClassId'); 
        ?>        
        <input readonly type="hidden" name="studentId" id="studentId" value="<?php echo $studentId; ?>">
        <input readonly type="hidden" name="currentClassId" id="currentClassId" value="<?php echo $studentCurrentClassId; ?>">
</form>        
<?php 
// $History: listStudentInternalReappearContents.php $
//
//*****************  Version 5  *****************
//User: Parveen      Date: 1/28/10    Time: 5:40p
//Updated in $/LeapCC/Templates/Student
//validation & format update (button & radio button updated)
//
//*****************  Version 4  *****************
//User: Parveen      Date: 1/19/10    Time: 6:27p
//Updated in $/LeapCC/Templates/Student
//function & validation message and format updated
//
//*****************  Version 3  *****************
//User: Parveen      Date: 1/12/10    Time: 6:16p
//Updated in $/LeapCC/Templates/Student
//format validation updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 1/09/10    Time: 5:22p
//Updated in $/LeapCC/Templates/Student
//look & feel updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 1/09/10    Time: 1:05p
//Created in $/LeapCC/Templates/Student
//initial checkin
//

?>
