<?php
//-------------------------------------------------------
// Purpose: to design add student.
//
// Author : Rajeev Aggarwal
// Created on : (05.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
        <?php   require_once(TEMPLATES_PATH . "/breadCrumb.php");   ?>
        </td>
    </tr>
    <tr>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
             <td valign="top" class="content">
             <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
			  
             <tr>
                <td class="contenttab_border" height="20">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                        <td class="content_title">Delete Attendance:</td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" >
                <form action="" method="POST" name="timeTableForm" id="timeTableForm">
                <!--********DO NOT DELETE THIS********-->
                 <input type="hidden" name="attendance" id="attendance" value="0">
                 <input type="hidden" name="attendance" id="attendance" value="0">
                <!--********DO NOT DELETE THIS********-->
				<table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
				<tr>
				 <td valign="top" class="content">
				 <table width="100%" border="0" cellspacing="0" cellpadding="0">
				 <tr>
					<td class="contenttab_border1">
					<table width="100%" border="0" cellspacing="0" cellpadding="0" >
					<tr>
						<td height="5" colspan="6"></td>
					</tr>
					
                    <tr>
                        <td class="contenttab_internal_rows" align="left"><nobr><b>Time Table: </b></nobr></td>
                        <td class="padding">
                        <select size="1" name="timeTableLabelId" id="timeTableLabelId" class="inputbox1" onChange="autoPopulateClass(this.value);" style="width:150px;">
                         <option value="">Select</option>
                         <?php
                          require_once(BL_PATH.'/HtmlFunctions.inc.php');
                          echo HtmlFunctions::getInstance()->getTimeTableLabelData();
                         ?>
                        </select></td>
                        <td class="contenttab_internal_rows"><nobr><b>Class: </b></nobr></td>
                        <td class="padding">
                         <select size="1" name="studentClass" id="studentClass" onChange="autoPopulateSubjectGroups(this.value);" class="selectfield" style="width:220px;">
                           <option value="">Select</option>
                          </select>
                         </td>
						<td class="contenttab_internal_rows"><nobr><b>Subject: </b></nobr></td>
						<td class="padding">
                         <select size="1" class="selectfield" name="subject" id="subject" onChange="hideData(3);" class="inputbox1" style="width:220px;">
						    <option value="">Select</option>
						 </select>
                        </td>
                        <!--
						<td class="contenttab_internal_rows"><nobr><b>Group: </b></nobr></td>
						<td class="padding">
                         <select size="1" class="selectfield" name="studentGroup" id="studentGroup" onChange="hideData(3);" class="inputbox1" style="width:150px;">
						   <option value="">Select</option>
						 </select>
                        </td>
                       --> 
						<td  align="right" style="padding-right:5px" >
                          <input type="image" name="imgSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onclick="return validatetTimetableForm();return false;" />
                        </td>
					</tr>
					<tr><td colspan="6" height="5px">&nbsp;</td></tr>
					</table>
				</td>
			</tr>
             <tr>
                <td class="contenttab_row" valign="top" >&nbsp;
                <!--Main Result Div-->
                    <div id="results"></div>
                <!--Main Result Div Ends-->
             </td>
          </tr>
          <tr><td height="5px"></td></tr>
          <tr id="buttonRow" align="center" style="display:none">
           <td>
             <input type="image" name="imgSubmit" src="<?php echo IMG_HTTP_PATH;?>/delete_big.gif" onclick="return validatetInputData();return false;" />  
             <input type="image" name="imgSubmit" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="return hideData(4);return false;" />  
           </td>
          </tr> 
          </table>
        </td>
		</tr>
		</table>
		</form>         
	</td>
	</tr>
    </table>
    </td>
    </tr>
    </table>
    </td>
    </tr>
    </table>
<?php 
// $History: deleteAttendanceContents.php $
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 12/17/09   Time: 10:19a
//Updated in $/LeapCC/Templates/AdminTasks
//Updated breadcrumb according to the new menu structure
//
//*****************  Version 2  *****************
//User: Administrator Date: 5/06/09    Time: 15:12
//Updated in $/LeapCC/Templates/AdminTasks
//Corrected attendance deletion module's logic
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 14/04/09   Time: 17:22
//Created in $/LeapCC/Templates/AdminTasks
//Created Attendance Delete Module
?>