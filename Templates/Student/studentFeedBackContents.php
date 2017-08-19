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
                <td valign="top">Feedback &nbsp;&raquo;&nbsp; Teacher Feed Back</td>
                
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
											<td valign="top" align="left" width="55%"><b>Feed Back Survey : </b><select size="1" class="selectfield1" name="feedBackSurvey" id="feedBackSurvey" onchange="getSueveyLabel()" >
											<option value="0">Select</option>
											<?php
												require_once(BL_PATH.'/HtmlFunctions.inc.php');
												echo HtmlFunctions::getInstance()->getFeedBackLabelData('','WHERE isActive=1 AND surveyType=2 AND (CURRENT_DATE() BETWEEN visibleFrom AND visibleTo)');
											?>
												</select></td>

											<td valign="top" align="left" width="45%" ><b>Teacher Name : </b><select size="1" class="selectfield1" name="teacherName" id="teacherName" onChange="duplicateCheck()" >
											<option value="0">Select</option>
											<?php
												require_once(BL_PATH.'/HtmlFunctions.inc.php');
												echo HtmlFunctions::getInstance()->getPreviousEmployeeData($REQUEST_DATA['employeeName']==''? $employeeRecordArray[0]['employeeId'] : $REQUEST_DATA['employeeName'],$classId);
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
//$History: studentFeedBackContents.php $
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 9/22/09    Time: 6:43p
//Updated in $/LeapCC/Templates/Student
//change breadcrumb & put department in employee
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 5/27/09    Time: 11:02a
//Updated in $/LeapCC/Templates/Student
//copy from sc and modifications in the files as per requirement of CC
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 12/09/08   Time: 5:45p
//Updated in $/LeapCC/Templates/Student
//modification in code for cc
//

?>