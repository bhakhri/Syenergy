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
                <td valign="middle">Feedback &nbsp;&raquo;&nbsp; General Feed Back</td>
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
												echo HtmlFunctions::getInstance()->getFeedBackGeneralLabelData('','AND isActive=1 AND surveyType=1 AND svu.userType="S" AND svu.targetIds='.$studentId);
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
//$History: generalFeedBackContents.php $
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 9/22/09    Time: 6:43p
//Updated in $/LeapCC/Templates/Student
//change breadcrumb & put department in employee
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 5/27/09    Time: 11:02a
//Updated in $/LeapCC/Templates/Student
//copy from sc and modifications in the files as per requirement of CC
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 5/27/09    Time: 10:00a
//Created in $/LeapCC/Templates/Student
//copy from sc
//
//
?>