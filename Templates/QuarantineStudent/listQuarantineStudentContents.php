<?php
//----------------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR QUARANTINE STUDENTS 
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (8.7.2008)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------
require_once(BL_PATH.'/HtmlFunctions.inc.php');
$htmlFunctions = HtmlFunctions::getInstance();
                                                                     
require_once(TEMPLATES_PATH . "/breadCrumb.php"); 
?>
    <tr>
        <td valign="top" colspan=2>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
             <td valign="top" >
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
             <tr>
             <td valign="top" " style="border-bottom:none" >
              <table cellpadding="0" cellspacing="0" border="0" width="100%" >
              <tr>
              <td colspan="2" style="padding:5px" valign="top" >
               <!--Add Student Filter-->
                 <table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2" >
                            <tr>
                                <td valign="top" class="contenttab_row1" align="center">
                                    <form name="allDetailsForm" action="" method="post" onSubmit="return false;">
                                        <table border='0' width='100%' cellspacing='0'>
                                            <?php echo $htmlFunctions->makeStudentDefaultSearch(); ?>
                                            <tr height='5'></tr>
                                            <?php echo $htmlFunctions->makeStudentAcademicSearch(false); ?>
                                            <tr height='5'></tr>
                                            <?php echo $htmlFunctions->makeStudentAddressSearch(); ?>
                                            <tr height='5'></tr>
                                            <?php echo $htmlFunctions->makeStudentMiscSearch(); ?>
                                            <tr>
                                             <td colspan="9" class="contenttab_internal_rows">
                                             <b><font color="#E60000">Note : Groups assigned to selected students for deletion will also be deleted along with students</font></b>
                                             </td>
                                            </tr> 
                                            <tr>
                                                <td valign='top' colspan='10' class='' align='center'>
                                                    <input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateAddForm();return false;" />
                                                </td>
                                            </tr>
                                        </table>
                                    </form>
                                </td>
                            </tr>
                        </table>
               <!--Add Student Filter-->
              </td>

             </tr>
              </table>  
             </td>
             </tr>
             
             <tr>
                <td valign="top" >&nbsp;
                <div id="showList" style="display:none">
                
                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                <tr>
                <td class="contenttab_border" height="20">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                        <td class="content_title">Student List: </td>
                        <td class="content_title" >&nbsp;</td>
                    </tr>
                    </table>
                </td>
             </tr>
                <tr>
                 <td class="contenttab_row">
                <form name="listFrm" id="listFrm">
                <!--Do not delete-->
                 <input type="hidden" name="students" id="students" />
                 <input type="hidden" name="students" id="students" />  
                 <!--Do not delete-->
                 
                 <div id="results">
                </div>
                </form>           
                </td>
               </tr>
               <tr><td height="5px"></td></tr>
               <tr> 
                <td align="center">
                <div id="divButton" style="display:none">
                  <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/delete_big.gif" onClick="return validateForm();return false;" />
                 <input type="image" name="editCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="hide_div('showList',2);resetForm();return false;" />
                </div> 
                 </td>
               </tr>
               <tr><td height="5px"></td></tr>
              </table> 
              </div>
             </td>
          </tr>
          <tr><td height="10px"></td></tr>
           <tr>
           <td align="right" id="printTr" style="display:none">
             <INPUT type="image" src="<?php echo IMG_HTTP_PATH ?>/print.gif" border="0" onClick="printReport()">&nbsp;
             <INPUT type="image" src="<?php echo IMG_HTTP_PATH ?>/excel.gif" border="0" onClick="javascript:printCSV();">
          </td></tr>
          </table>
        </td>
    </tr>
    
    </table>
    </td>
    </tr>
    </table>

<?php
// $History: listQuarantineStudentContents.php $
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 6/08/09    Time: 12:16
//Updated in $/LeapCC/Templates/QuarantineStudent
//corrected look and feel issues
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 5/08/09    Time: 12:39
//Updated in $/LeapCC/Templates/QuarantineStudent
//Done bug fixing.
//bug ids---
//0000887 to 0000895,
//0000906 to 0000909
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 4/08/09    Time: 16:01
//Updated in $/LeapCC/Templates/QuarantineStudent
//Done bug fixing.
//bug ids--
//0000861 to 0000877
//
//*****************  Version 3  *****************
//User: Administrator Date: 13/06/09   Time: 18:59
//Updated in $/LeapCC/Templates/QuarantineStudent
//Corredted issues which are detected during user documentation
//preparation
//
//*****************  Version 2  *****************
//User: Administrator Date: 30/05/09   Time: 10:40
//Updated in $/LeapCC/Templates/QuarantineStudent
//Added BloodGroup wise search in messaging and delete/restore student
//modules
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 12/03/08   Time: 6:49p
//Created in $/LeapCC/Templates/QuarantineStudent
//Created quarantine/delete student module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 11/06/08   Time: 5:14p
//Created in $/Leap/Source/Templates/ScQuarantineStudent
//Created Quarantine(delete) Student Module
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 9/29/08    Time: 3:37p
//Updated in $/Leap/Source/Templates/ScAdminMessage
//Added Student Search Filter
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/17/08    Time: 4:14p
//Created in $/Leap/Source/Templates/ScAdminMessage
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 8/29/08    Time: 11:37a
//Updated in $/Leap/Source/Templates/AdminMessage
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 8/28/08    Time: 6:08p
//Updated in $/Leap/Source/Templates/AdminMessage
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 8/21/08    Time: 4:09p
//Updated in $/Leap/Source/Templates/AdminMessage
//Changed Image Name of submit button
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/18/08    Time: 11:21a
//Updated in $/Leap/Source/Templates/AdminMessage
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/16/08    Time: 5:30p
//Updated in $/Leap/Source/Templates/AdminMessage
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/14/08    Time: 6:21p
//Updated in $/Leap/Source/Templates/AdminMessage
//corrected breadcrumb and reset button height and width
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 8/11/08    Time: 3:04p
//Created in $/Leap/Source/Templates/AdminMessage
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/06/08    Time: 6:50p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//Done modifications as discussed in the demo session
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/21/08    Time: 6:53p
//Created in $/Leap/Source/Templates/Teacher/TeacherActivity
//Initial Checkin
?>