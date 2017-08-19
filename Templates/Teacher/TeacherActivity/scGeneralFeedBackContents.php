<?php
//this file contains the template of attendace
//
// Author :Jaineesh
// Created on : 22.07.08
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<form action="" method="POST" name="feedBackForm" id="feedBackForm">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
            <tr>
                <td height="10"></td>
            </tr>
           <tr>
			  <td valign="top" class="title">
			 <?php require_once(TEMPLATES_PATH . "/breadCrumb.php");   ?>
			  </td>
		   </tr>
            </table>
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
                        <td class="content_title">General Survey Detail :</td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" >

					<table width="100%" border="0" cellspacing="1" cellpadding="1">
						<tr class="row0">
							<td valign="top" colspan="3" width="100%">

									<table border="0" cellspacing="1" cellpadding="1" width="100%">
										<tr>

											<td valign="top" align="left" width="70%"><b>Feed Back Survey  </b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b>:</b>&nbsp;<select size="1" class="inputbox" name="feedBackSurvey" id="feedBackSurvey" onchange="getSueveyLabel()" >
											<option value="0">Select</option>
											<?php
												require_once(BL_PATH.'/HtmlFunctions.inc.php');
												global $sessionHandler;
												$employeeId = $sessionHandler->getSessionVariable('EmployeeId');
												echo HtmlFunctions::getInstance()->getFeedBackGeneralLabelData('','AND isActive=1 AND surveyType=1 AND svu.userType="E" AND svu.targetIds='.$employeeId);
											?>
												</select></td>
										</tr>
										<tr>
											<td valign="top" align="left" width="100%" colspan="2" ></td>
										</tr>
									</table>

							</td>
						</tr>
						<tr>
							<td valign="top"><div id="results">
							</div>
					</td></tr></table>

             </td>
          </tr>


          </table>

        </td>
    </tr>

    </table>
   </form>

<?php
//$History: scGeneralFeedBackContents.php $
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 14/01/09   Time: 16:19
//Updated in $/Leap/Source/Templates/Teacher/ScTeacherActivity
//Modified default align to : Left
//Added mandatory field indicator
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/01/09    Time: 17:44
//Updated in $/Leap/Source/Templates/Teacher/ScTeacherActivity
//Corrected breadcrumb
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 1/06/09    Time: 4:45p
//Updated in $/Leap/Source/Templates/ScStudent
//modified in code to send session student id
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 1/06/09    Time: 4:05p
//Created in $/Leap/Source/Templates/ScStudent
//template file general feedback
//
?>