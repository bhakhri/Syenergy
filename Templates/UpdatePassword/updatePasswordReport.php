<?php 
//This file creates Html Form output for update password report
//
// Author :Jaineesh
// Created on : 20-oct-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
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
                        <form name="allDetailsForm" id="allDetailsForm" action="" method="post" onsubmit="return false;">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2">
                            <tr>
                                <td valign="top" class="contenttab_row1" style="padding-left:10px">
                                        <table align="left" border="0" width="100%" cellspacing="1px" cellpadding="1px">
                                            <tr>
                                                <td class="contenttab_internal_rows1" align="left" width="5%">
                                                    <nobr><strong>Degree</strong></nobr>
                                                </td>
                                                <td class="contenttab_internal_rows1"  width="2%" nowrap><b>:</b></td>
                                                <td class="contenttab_internal_rows1"  width="20%" nowrap>
                                       <select size="1" class="selectfield" name="degree" id="degree" style="width:220px" onchange="getGroups(); return false;">
                                                        <option value="">Select</option>
                                                        <?php 
                                                            require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                            echo HtmlFunctions::getInstance()->getSessionClasses();?>
                                                    </select>
                                                </td>
                                                <td class="contenttab_internal_rows1" align="left" width="10%" style="padding-left:20px">
                                                   <nobr><strong>Group</strong>&nbsp;</nobr>
                                                </td>
                                                 <td width="1%" class="contenttab_internal_rows1"><b>:</b></td>
                                                <td  width="50%" class="contenttab_internal_rows1" nowrap>
                                                    <select name="groupId"  class="selectfield" id="groupId" style="width:220px" onchange="hideResults(); return false;">
                                                        <option value="">Select</option>
                                                    </select>
                                                </td> 
                                             </tr>
                                            <tr>
                                                <td class='contenttab_internal_rows1' style='text-align:left'  nowrap><b>Roll No.&nbsp; </b></td>
                                                <td class="contenttab_internal_rows1"  nowrap><b>:</b></td> 
                                                <td class='contenttab_internal_rows1' align='left' nowrap><input type='text' class='selectfield' autocomplete='off' name='rollNo' id='rollNo' style='width:218px'  /></td>
                                                <td class="contenttab_internal_rows1" align="left" width="10%" style="padding-left:20px">
                                                   <nobr><strong>Student Name</strong>&nbsp;</nobr>
                                                </td>
                                                <td class="contenttab_internal_rows1"  nowrap><b>:</b></td> 
                                                <td colspan="4" class='contenttab_internal_rows1' style='text-align:left' nowrap>
                                                    <input type='text' class='selectfield' name='studentName' id='studentName' style='width:220px' />
                                                </td>
                                            </tr>
            <tr>
                <td valign="top" width="100%"  colspan="9" >
                  <!--  <?php // echo $htmlFunctions->makeStudentAcademicSearch(); ?> -->
                </td> 
            </tr>
            <tr>
                 <td colspan="9">
                     <input type="hidden" name="result" id="result" value=""> 
                 </td>
            </tr>
            <tr>
                <td class="contenttab_internal_rows1" colspan="9" height="10px"></td>
             <tr>
              <tr>
                  <td class="contenttab_internal_rows1" width="2%"><nobr><b>Username</b></nobr></td>
                  <td class="contenttab_internal_rows1" width="2%"><nobr><b>&nbsp;:</b></nobr></td> 
                  <td class="contenttab_internal_rows1" width="96%" colspan="9"><nobr>
                     <select name="userNameFormat" id="userNameFormat" style="width:162px" class="selectfield" onchange="document.getElementById('userNameSet1').innerHTML=document.getElementById('userNameFormat').value;" >
                        <option value="Reg. No.">Reg. No.</option>
                        <option value="Univ. Roll No.">Univ. Roll No.</option>
                        <option value="Roll No.">Roll No.</option>   
                        <option value="Email">Email</option>
                        <option value="Student Name + Batch">Student Name + Batch</option>   
                     </select>   
                     </nobr>
                  </td>
              </tr> 
              <tr>
                <td class="contenttab_internal_rows1" colspan="9" valign="top">
                    &raquo;&nbsp;To create or update Student logins. The username would be a prefix <b>&lt;<label id='userNameSet1'>Reg. No.</label>&gt;</b> for student<br>
                </td>    
              </tr>
              <tr><td height="5px"></td></tr>
              <tr>
                <td class="contenttab_internal_rows1" colspan="9" valign="top">
                    <input type="checkbox" name="changeUserName" id="changeUserName" value="1" checked="checked">&nbsp;Do not change the username for existing users. 
                </td>
              </tr>      
              <tr>
                 <td height="5px"></td>
              </tr>
              <tr>
                  <td class="contenttab_internal_rows1" width="2%"><nobr><b>Password</b></nobr></td>
                  <td class="contenttab_internal_rows1" width="2%"><nobr><b>&nbsp;:</b></nobr></td> 
               </tr> 
                       
             <tr>
                <td class="contenttab_internal_rows1" colspan="9">
                <input type="radio" name="password" id="firstNamePassword" checked="checked">Make Password as first name followed by birth year</td>
             </tr>
             <tr>
                <td class="contenttab_internal_rows1" colspan="9">
                <input type="radio" name="password" id="commonPassword" >Enter common password <input type="password" name="newPassword" class='selectfield' id="newPassword">
                </td>
             </tr>
             <tr>
                <td class="contenttab_internal_rows1" colspan="9">
                <input type="radio" name="password" id="randomPassword" >Generate random password 
                </td>
             </tr>
             <tr>
                 <td height="10px"></td>
             </tr>
             <tr>
                <td class="contenttab_internal_rows1" width="2%" colspan="9">
                    <nobr><b>Note&nbsp;:</b>&nbsp;'---' indicates username does not exists for respective student</nobr>
                </td>
             </tr>
             <tr>
                <td align="center" colspan="9">
                    <span style="padding-right:10px" >
                    <input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="getStudentList(); return false;" />
                </td>
            </tr>
        </table>
      </td>
    </tr>
  </table>

    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr id='nameRow' style='display:none;'>
            <td class="" height="20">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="contenttab_border">
                    <tr>
                        <td colspan="1" class="content_title">Update Student Login Detail :</td>
                        <td align="right" width="50%">
                            <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/generate_logins_export.gif" onclick="return validateAddForm();return false;"/></td>
                        <!--
                        <td colspan="1" class="content_title" align="right"><input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printCSV();return false;"/></td>-->
                    </tr>
                </table>
            </td>
        </tr>
        <tr id='resultRow' style='display:none;'>
            <td colspan='1' class='contenttab_row'>
                <div id = 'resultsDiv'></div>
                <div id = 'pagingDiv' align='right'></div>
            </td>
        </tr>
        <tr>
            <td colspan="7" align="right">
                
            </td>
        </tr>
        <tr id='nameRow2' style='display:none;'>
            <td>
                  <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20" >
                    <tr>
                      <td align="right" width="50%">
                            <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/generate_logins_export.gif" onclick="return validateAddForm();return false;"/>
                    <!--
                    <td colspan="2" class="content_title" align="right"><input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printCSV();return false;"/></td>-->
                      </td>
                    </tr>
                  </table>
            </td>
        </tr>
    </td>
</tr>
</table>
</form>
<!-- form table ends -->
</td>
</tr>
</table>
</table>

<?php 

////$History: updatePasswordReport.php $
//
//*****************  Version 9  *****************
//User: Gurkeerat    Date: 12/17/09   Time: 10:19a
//Updated in $/LeapCC/Templates/UpdatePassword
//Updated breadcrumb according to the new menu structure
//
//*****************  Version 8  *****************
//User: Gurkeerat    Date: 11/13/09   Time: 5:57p
//Updated in $/LeapCC/Templates/UpdatePassword
//Updated code to add new field in 'Generate Student Login' 
//
//*****************  Version 7  *****************
//User: Parveen      Date: 11/12/09   Time: 12:19p
//Updated in $/LeapCC/Templates/UpdatePassword
//password format added
//
//*****************  Version 6  *****************
//User: Gurkeerat    Date: 11/12/09   Time: 12:12p
//Updated in $/LeapCC/Templates/UpdatePassword
//updated code to resolve issues
//
//*****************  Version 5  *****************
//User: Gurkeerat    Date: 11/11/09   Time: 6:29p
//Updated in $/LeapCC/Templates/UpdatePassword
//resolved issues: 1967, 1968, 1969, 1971, 1972, 1980, 1981
//
//*****************  Version 4  *****************
//User: Gurkeerat    Date: 11/06/09   Time: 1:46p
//Updated in $/LeapCC/Templates/UpdatePassword
//Updated code to modify 'Generate student login'
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 7/29/09    Time: 10:49a
//Updated in $/LeapCC/Templates/UpdatePassword
//modification in breadcrumb & make link Generate Student Login instead
//of create users
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 6/01/09    Time: 3:26p
//Updated in $/LeapCC/Templates/UpdatePassword
//changes as per leap cc
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 6/01/09    Time: 1:16p
//Created in $/LeapCC/Templates/UpdatePassword
//copy from sc
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 5/30/09    Time: 3:43p
//Created in $/Leap/Source/Templates/ScUpdatePassword
//new template file to show student password & user name
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 3/27/09    Time: 11:25a
//Updated in $/Leap/Source/Templates/ScStudentReports
//changed image source to input type
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 11/15/08   Time: 4:26p
//Updated in $/Leap/Source/Templates/ScStudentReports
//added code for time table labels
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 10/22/08   Time: 12:03p
//Updated in $/Leap/Source/Templates/ScStudentReports
//added code for print and csv version
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 10/21/08   Time: 1:59p
//Created in $/Leap/Source/Templates/ScStudentReports
//file added for marks Transferred report

//
?>
