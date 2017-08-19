<?php
//-------------------------------------------------------
// Purpose: to design the layout for payment history.
//
// Author : Rajeev Aggarwal
// Created on : (02.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
            <?php require_once(TEMPLATES_PATH . "/breadCrumb.php"); ?>   
        </td>
    </tr>
    <tr>
        <td valign="top">
			<form action="" method="POST" name="allDetailsForm" id="allDetailsForm">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
             <td valign="top" class="content">
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr><a id="lk1" class="set_default_values">Set Default Values for Report Parameters</a>
		  <td class="contenttab_border2" align="center">
		  <div style="height:15px"></div>  
		  <?php require_once(TEMPLATES_PATH . "/listFeeFineReportContents.php");?>   
		</td>
		</tr>
            <tr id="showTitle" style="display:none">
                <td class="contenttab_border" height="20">
                 <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                 <tr>
                    <td class="content_title">Fine Cancelled History: </td>
					<td colspan='1' align='right'><input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();return false;"/>&nbsp;<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printPaymentHistoryCSV();return false;"/></td>
                 </tr>
                 </table>
             </td>
          </tr>
          <tr id="showData" style="display:none">
                <td class="contenttab_row" valign="top"><div id="results"></div></td>
		  </tr>
		  <tr>
			<td height="10"></td>
		  </tr>
         <tr><td colspan='1' align='right'><div id = 'saveDiv' style="display:none"><input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();return false;"/>&nbsp;<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printPaymentHistoryCSV();return false;"/></div></td></tr>
          </table>
        </td>
    </tr>
    </table>
	</form>
    </td>
    </tr>
    </table>
<?php 
// $History: fineHistoryContents.php $
//
//*****************  Version 7  *****************
//User: Gurkeerat    Date: 12/15/09   Time: 4:38p
//Updated in $/LeapCC/Templates/Fine
//resolved issues0002238, 0002239, 0002241, 0002242, 0002243, 0002240,
//0002271
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 11/12/09   Time: 15:37
//Updated in $/LeapCC/Templates/Fine
//Modified "Fine History Report" and  added the option whether to view
//student who have paid or not
//
//*****************  Version 5  *****************
//User: Gurkeerat    Date: 12/09/09   Time: 11:25a
//Updated in $/LeapCC/Templates/Fine
//resolved issues 0001970,0002228,0002229,0002230,0002231,0002233
//
//*****************  Version 4  *****************
//User: Gurkeerat    Date: 12/08/09   Time: 3:32p
//Updated in $/LeapCC/Templates/Fine
//resolved issue 0002216,0002211,0002214,0002215,0002217,0002220,0002221,
//0002222,0002223,0002224,0002225,0002226,0002227,0002218
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 10/29/09   Time: 6:13p
//Updated in $/LeapCC/Templates/Fine
//added code for autosuggest functionality
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 09-09-03   Time: 12:40p
//Updated in $/LeapCC/Templates/Fine
//fixed 0001421,0001422,0001428,0001430,0001434,0001435
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 7/07/09    Time: 6:46p
//Created in $/LeapCC/Templates/Fine
//intial checkin
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 5/18/09    Time: 1:51p
//Updated in $/LeapCC/Templates/Student
//Updated report formatting so that "outstanding" field stand Out
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 1/12/09    Time: 5:30p
//Updated in $/LeapCC/Templates/Student
//Updated with Required field, centralized message, left align
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 12/23/08   Time: 12:57p
//Updated in $/LeapCC/Templates/Student
//updated as per CC functionality
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Student
//
//*****************  Version 10  *****************
//User: Rajeev       Date: 9/11/08    Time: 1:14p
//Updated in $/Leap/Source/Templates/Student
//updated gaps
//
//*****************  Version 9  *****************
//User: Rajeev       Date: 9/11/08    Time: 1:09p
//Updated in $/Leap/Source/Templates/Student
//updated formatting and comments added
//
//*****************  Version 8  *****************
//User: Rajeev       Date: 9/09/08    Time: 12:27p
//Updated in $/Leap/Source/Templates/Student
//updated with payment instrument search criteria
//
//*****************  Version 7  *****************
//User: Rajeev       Date: 9/02/08    Time: 7:32p
//Updated in $/Leap/Source/Templates/Student
//updated with html validator
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 9/01/08    Time: 4:02p
//Updated in $/Leap/Source/Templates/Student
//updated list fields with outstanding amount and other parameters
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 8/28/08    Time: 1:09p
//Updated in $/Leap/Source/Templates/Student
//updated fee receipt payment report
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 8/21/08    Time: 2:39p
//Updated in $/Leap/Source/Templates/Student
//updated show list button
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 8/21/08    Time: 2:03p
//Updated in $/Leap/Source/Templates/Student
//updated formatting and print reports
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 8/14/08    Time: 3:15p
//Updated in $/Leap/Source/Templates/Student
//added print report function
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 8/07/08    Time: 3:00p
//Created in $/Leap/Source/Templates/Student
//intial checkin
?>
