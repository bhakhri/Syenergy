<?php 
//-------------------------------------------------------
//  This File contains Student grade card template
//
//
// Author :Parveen Sharma
// Created on : 06-03-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(BL_PATH.'/HtmlFunctions.inc.php');
$htmlFunctions = HtmlFunctions::getInstance();
?>

<form action="" method="POST" name="allDetailsForm" id="allDetailsForm" onSubmit="return false;">
<!--
<select name="subject[]" id="subject" style="display:none">
</select>                          
-->
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
		<?php require_once(TEMPLATES_PATH . "/breadCrumb.php"); ?>
		</td>
		</tr>
		<tr>
		<td>
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
                
                <tr>
					<?php if (STUDENT_ICON == true) { ?>
				<!-- <td valign="top" width="5%">
				    	<img src="<?php echo IMG_HTTP_PATH ?>/student.gif" border="0" title="Student">
				    	echo "<img src=\"".IMG_HTTP_PATH."/".student.gif."\" width=\"100\" height=\"100\" border=\"0\"/>";
					  </td> -->
					<?php }?>
                    <!--<td valign="middle" colspan="2"> <?php require_once(TEMPLATES_PATH . "/breadCrumb.php"); ?>   
					Reports &nbsp;&raquo;&nbsp;Attendance&nbsp;&raquo;&nbsp; Student Attendance Short Report</td>
                </tr>
           </table>
-->       </td>
       </tr>
       </table>
      
    <tr>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
                <tr>
                    <td valign="top" class="content" align="center">
                        <!-- form table starts -->
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2"> 
                         <tr>
                         <td>
                        <table width="20%" border="0" cellspacing="0" cellpadding="0" class="" align="left" >
                          <tr height="40">
                            <td class="contenttab_internal_rows" valign="middle"><nobr><b>&nbsp;&nbsp;Time Table<?php echo REQUIRED_FIELD; ?></b></nobr></td>
                            <td class="contenttab_internal_rows"><nobr><strong>:</strong></nobr>&nbsp;</td> 
                            <td class="contenttab_internal_rows"><nobr>
                               <select size="1" class="inputbox1" name="labelId" style="width:140px" id="labelId" onchange="populateClass();">
                                   <option value="">Select</option>
                                    <?php
                                      require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                      echo HtmlFunctions::getInstance()->getTimeTableLabelData();
                                    ?>
                                </select></nobr>
                            </td>  
                            <td class="contenttab_internal_rows" valign="middle"><nobr><b>&nbsp;&nbsp;Class<?php echo REQUIRED_FIELD; ?></b></nobr></td>
                            <td class="contenttab_internal_rows"><nobr><strong>:</strong></nobr>&nbsp;</td> 
                            <td class="contenttab_internal_rows">
                                <select size="1" class="inputbox1" name="classId" id="classId" style="width:250px" >
                                   <option value="">Select</option>                                             
                                </select>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><strong>&nbsp;&nbsp;Roll No.</strong></nobr></td>
                            <td class="contenttab_internal_rows"><nobr><strong>:</strong></nobr>&nbsp;</td>     
                            <td class="contenttab_internal_rows"><nobr>                                                        
                                <input type="text" name="rollno" id="rollno" class="inputbox" maxlength="50" style="width:180px" />
                                </nobr>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><strong>&nbsp;&nbsp;Percentage<?php echo REQUIRED_FIELD; ?></strong></nobr></td>   
                            <td class="contenttab_internal_rows"><nobr><strong>:</strong></nobr>&nbsp;</td>    
                            <td class="contenttab_internal_rows" valign="middle" align="left"><nobr>                                                   
                                 <input type="text" class="inputbox" id="percentage" name="percentage" maxlength="6" style="width:35px" value="<?php 
                                  echo $sessionHandler->getSessionVariable('ATTENDANCE_THRESHOLD');?>" >
                            </td> 
                          </tr>  
                          <tr>
                             <td class="contenttab_internal_rows" valign="top"><nobr><strong>&nbsp;&nbsp;Message</strong></nobr></td>
                             <td class="contenttab_internal_rows" valign="top"><nobr><strong>:</strong></nobr>&nbsp;</td>     
                             <td class="contenttab_internal_rows" colspan="20" colspan="20"><nobr>
                                 <table width="42%" border="0" cellspacing="0" cellpadding="0" align="left">
                                    <tr>
                                     <td class="contenttab_internal_rows" ><nobr><strong>
                                        <?php
                                          global $sessionHandler;
                                          $mm = html_entity_decode(strip_slashes($sessionHandler->getSessionVariable('ATTENDANCE_SHORT_MESSAGE')));
                                          if($mm=='') {
                                            $mm = "Attendance of your ward is falling short than 75% in some of the subjects listed below The attendance which is falling short is shown as <b><u>bold and underlined</b></u>. If he is unable to complete 75% lectures in all the subjects, he will be declared as fail in the subjects in which his attendance is less than 75%.";  
                                          }
                                        ?>
                                        <textarea name="message" id="message" class="inputbox" style="width:880px;height:40px" /><?php echo $mm;?></textarea>
                                        </nobr>
                                     </td>
                                   </tr>
                                 </table> 
                              </td>   
                         </tr>
                        <tr><td height="10px"></td></tr>    
                        <tr>
                             <td class="contenttab_internal_rows" valign="top"><nobr><strong>&nbsp;&nbsp;Heading</strong></nobr></td>
                             <td class="contenttab_internal_rows" valign="top"><nobr><strong>:</strong></nobr>&nbsp;</td>     
                             <td class="contenttab_internal_rows" valign="top" colspan="20"><nobr>
                                <?php
                                  global $sessionHandler;
                                  $mm = html_entity_decode(strip_slashes($sessionHandler->getSessionVariable('ATTENDANCE_SHORT_HEADING')));
                                  if($mm=='') {
                                    $mm = "Shortage of Attendance";  
                                  }
                                ?>
                                <input type="text" name="heading" id="heading" class="inputbox" value="<?php echo $mm; ?>" maxlength="200" style="width:880px" />
                                </nobr>
                             </td>
                         </tr>    
                         <tr><td height="5px"></td></tr>  
                         <tr>
                            <td class="contenttab_internal_rows" valign="top"><nobr><strong>&nbsp;&nbsp;Signature</strong></nobr></td>
                            <td class="contenttab_internal_rows" valign="top"><nobr><strong>:</strong></nobr></td>
                            <td class="contenttab_internal_rows" valign="top">
                                <nobr>
                                <?php
                                  global $sessionHandler;
                                  $mm = html_entity_decode(strip_slashes($sessionHandler->getSessionVariable('ATTENDANCE_SHORT_SIGNATURE')));
                                  if($mm=='') {
                                    $mm = "Controller of Examinations";  
                                  }
                                 ?>
                                 <input type="text" name="signature" id="signature" class="inputbox" value="<?php echo $mm; ?>" maxlength="100" style="width:200px" />
                                </nobr>
                            </td> <a id="lk1"  class="set_default_values">Set Default Values for Report Parameters</a>
                            <td class="contenttab_internal_rows" valign="top" colspan="20"><nobr>    
                              <table width="42%" border="0" cellspacing="0" cellpadding="0" align="left">
                                <tr>
                                  <td class="contenttab_internal_rows" valign="top"><nobr><strong>Include</strong></nobr></td>
                                  <td class="contenttab_internal_rows" valign="top"><nobr><strong>:</strong></nobr></td>
                                  <td class="contenttab_internal_rows" ><nobr><strong>
                                    <input class="inputbox1"  type="checkbox" id="photo" name="photo" value="1">
                                 </td>
                                 <td class="contenttab_internal_rows" ><nobr>Photo</nobr></td>  
                                 <td class="contenttab_internal_rows"><nobr><strong>
                                    <input class="inputbox1" onclick="setAddress();" type="checkbox" id="addressChk" name="addressChk" value="1">
                                 </td>
                                 <td class="contenttab_internal_rows" cellspacing="0" ><nobr>Address</nobr></td>  
                                 <td class="contenttab_internal_rows" id='addressHide' style='display:none'>
                                   <table>
                                     <tr>
                                        <td class="contenttab_internal_rows" ><nobr>
                                            <input class="inputbox1" checked="checked" type="radio" id="address" name="address" value="1">
                                            </nobr>
                                        </td>
                                        <td class="contenttab_internal_rows" ><nobr><B>Correspondence</b></nobr></td>  
                                        <td class="contenttab_internal_rows" ><nobr><strong>
                                            <input class="inputbox1" type="radio" id="address" name="address" value="2">
                                        </td>
                                        <td class="contenttab_internal_rows" ><nobr><b>Permanent</b></nobr></td>  
                                        
                                     </tr>
                                   </table>
                                  </td>
                                   <td class="contenttab_internal_rows" ><nobr><strong>
                                    <input class="inputbox1"  type="checkbox" id="dutyLeave" name="dutyLeave" value="1">
                                 </td>
                                 
                                 <td class="contenttab_internal_rows" ><nobr>
                                    <span style="font-family: Verdana,Arial,Helvetica,sans-serif;  color: red;"><b>Show Duty Leave</nobr>
                                 </td>   
                                 <td class="contenttab_internal_rows" ><nobr><strong>
                                    <input class="inputbox1"  type="checkbox" id="medicalLeave" name="medicalLeave" value="1">
                                 </td>
                                 <td class="contenttab_internal_rows" >
                                   <nobr><span style="font-family: Verdana,Arial,Helvetica,sans-serif;  color: red;"><b>Show Medical Leave</nobr>
                                 </td>
                                </tr>
                                <tr><td height="10px"></td></tr>
                               </table>   
                          </tr>  
                        </td>   
            </tr>
            <tr>
              <td align="center" valign="middle" colspan="20">
                <nobr>
                   <input type="image" name="s1" value="s1" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateAddForm(this.form);return false;" />
                </nobr>
              </td>   
            </tr>
            <tr>
              <td class="contenttab_internal_rows" colspan="20" align="left" style="padding-left:10px;font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 11px; color: red;">
                   <b><u>Format Instrunctions:</u></b><br> 
                   <b>a.</b>&nbsp;If you want to the line break in <b>Heading</b> or <b>Message</b>. please use <b>&lt;br&gt;</b></br>
                   <b>b.</b>&nbsp;If you want to <b>Bold</b> a part of meaasge then put &lt;b&gt; at the starting and &lt;/b&gt; at the end<br>
                   <b>c.</b>&nbsp;If you want to <u>Underline</u> a part of meaasge then put &lt;u&gt; at the starting and &lt;/u&gt; at the end
              </td>  
            </tr>   
            <tr>
                <td style="height:5px"></td>
            </tr>
          </table>     
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr id='nameRow' style='display:none;'>
                        <td class="" height="20">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="contenttab_border">
                                <tr>
                                    <td colspan="1" class="content_title" style="text-align:left">Student Attendance Short Report :</td>
                                    <td colspan="1" class="content_title" style="text-align:right">
                                        <input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr id='resultRow' style='display:none;'>
                        <td colspan='1' class='contenttab_row'>
                            <div id = 'resultsDiv'>
                            </div>
                        </td>
                    </tr>
                    <tr id='nameRow2' style='display:none;'>
                        <td class="" height="20">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="">
                                <tr>
                                    <td colspan="2" class="content_title" align="right">
                                        <input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />
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
</form>        
<?php 
//$History: studentAttendanceShortContents.php $
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 2/25/10    Time: 1:44p
//Updated in $/LeapCC/Templates/StudentReports
//updated breadcrumb
//
//*****************  Version 2  *****************
//User: Parveen      Date: 2/25/10    Time: 12:02p
//Updated in $/LeapCC/Templates/StudentReports
//format & validation updated 
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/23/10    Time: 5:42p
//Created in $/LeapCC/Templates/StudentReports
//initial checkin
//
//*****************  Version 4  *****************
//User: Parveen      Date: 1/11/10    Time: 3:46p
//Updated in $/Leap/Source/Templates/ScStudentReports
//validation format updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 12/28/09   Time: 3:22p
//Updated in $/Leap/Source/Templates/ScStudentReports
//required parameter added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 12/18/09   Time: 2:41p
//Created in $/Leap/Source/Templates/ScStudentReports
//initial checkin
//

?>
