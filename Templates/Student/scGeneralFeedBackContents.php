<?php
//this file contains the template of attendace
//
// Author :Jaineesh
// Created on : 22.07.08
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
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
                        <td class="content_title">Feed-Back Detail :</td>
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

											<td valign="top" align="left" width="30%"><b>Feed Back Survey : </b><select size="1" class="inputbox" name="feedBackSurvey" id="feedBackSurvey" onchange="getSueveyLabel()" >
											<option value="0">Select</option>
											<?php
												require_once(BL_PATH.'/HtmlFunctions.inc.php');
												global $sessionHandler;
												$studentId = $sessionHandler->getSessionVariable('StudentId');
												echo HtmlFunctions::getInstance()->getFeedBackGeneralLabelData('','AND isActive=1  AND svu.userType="S" AND svu.targetIds='.$studentId);
											?>
												</select></td>

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
//*****************  Version 5  *****************
//User: Jaineesh     Date: 9/21/09    Time: 7:30p
//Updated in $/Leap/Source/Templates/ScStudent
//fixed bugs during self testing
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 1/15/09    Time: 5:57p
//Updated in $/Leap/Source/Templates/ScStudent
//use student, dashboard, sms, email icons
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 1/10/09    Time: 11:10a
//Updated in $/Leap/Source/Templates/ScStudent
//modified in class of survey label
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