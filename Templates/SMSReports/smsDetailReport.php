<?php
//-------------------------------------------------------
// Purpose: to design the layout for SMS.
//
// Author : Parveen Sharma
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
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
                <tr>
                    <td valign="top" class="content">
                        <!-- form table starts -->
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2">
                            <tr>
                                <td valign="top" class="contenttab_row1">
                                    <form name="listForm" id="listForm" action="" method="post" onSubmit="return false;">
                                        <table align="center" border="0" cellpadding="0" width="80%">
                                              <tr>
                                                <td class="contenttab_internal_rows"  nowrap>
                                                    <strong>From Date</strong>
                                                </td>
                                                <td class="contenttab_internal_rows"  nowrap ><b>:&nbsp;</b></td>
                                                <td class="contenttab_internal_rows" nowrap align="left">
                                                    <?php  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                           echo HtmlFunctions::getInstance()->datePicker('fromDate',date('Y-m-d'));
                                                    ?>
                                                </td>
                                                <td class="contenttab_internal_rows"  nowrap align="right">
                                                    <strong>&nbsp;&nbsp;To Date&nbsp;:</strong>
                                                </td>
                                                <td class="contenttab_internal_rows"  nowrap align="left">
                                                    <?php  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                           echo HtmlFunctions::getInstance()->datePicker('toDate',date('Y-m-d'));
                                                    ?>
                                                </td>
                                                <td class="contenttab_internal_rows"  nowrap align="right">
                                                    <strong>&nbsp;&nbsp;Message Type&nbsp;:</strong>
                                                </td>
                                                <td class="contenttab_internal_rows"  nowrap align="left">
                                                <select size="1" name="messageType" id="messageType" class="htmlElement" onchange="hideResults();">
                                                        <option value="All">All</option>
                                                        <option value="SMS">SMS</option>
                                                        <option value="Email">Email</option>
                                                        <option value="Dashboard">Dashboard</option>
                                                    </select>
                                                </td>
                                                <td class="contenttab_internal_rows"  nowrap align="right">
                                                    <strong>&nbsp;&nbsp;Receiver Type&nbsp;:</strong>
                                                </td>
                                                <td class="contenttab_internal_rows"  nowrap align="left">
                                              <select size="1" name="receiverType" id="receiverType" class="htmlElement" onChange="hideResults();">
                                                        <option value="All">All</option>
                                                        <option value="Student">Student</option>
                                                        <option value="Employee">Employee</option>
                                                        <option value="Parent">Parent</option>
                                                    </select>
                                                </td>
                                                
                                            </tr>
	  				    <tr>
						<td class="contenttab_internal_rows"  valign='top' nowrap>
                                                    <strong>Search</strong>
                                                </td>
                                                <td class="contenttab_internal_rows"  valign='top' nowrap ><b>:&nbsp;</b></td>
						<td class="contenttab_internal_rows" nowrap colspan="10">
	                                           <table border="0" cellspacing="0" cellpadding="0" width="10%">
						     <tr>	
							    <td class="contenttab_internal_rows" nowrap>
							        <input type="text" style="width:550px" name="txtSearch" id="txtSearch" value="" class="inputbox"/>
						            <br><span style="font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 9px; color: red;"> 
						            Enter Keyword according to which you want to search (Sender, Subject, Brief Description)
						        </td> 
							<td nowrap valign='top'> 
 							<input name="searchOrder" id="searchOrder2" value="1"  type="radio" checked="checked"> Match Case
							   <input name="searchOrder" id="searchOrder1" value="2"  type="radio">Find Whole Words Only

							</td>
							   
							    <td nowrap style='padding-left:20px'>
                                    <input type="hidden" name="listMessage" value="1">
                                    <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateAddForm(this.form);return false; document.getElementById('saveDiv').style.display='';document.getElementById('showTitle').style.display='';document.getElementById('showData').style.display='';"/>
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
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr id='nameRow' style='display:none;'>
                                <td class="" height="20">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="contenttab_border">
                                        <tr>
                                            <td colspan="1" class="content_title">Messages List Detail :</td>
                                            <td colspan="2" class="content_title" align="right">
            <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();return false;"/>&nbsp;
            <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printReportCSV();return false;"/>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr id='resultRow' style='display:none;'>
                                <td colspan='1' class='contenttab_row'>
                                    <div id = 'resultsDiv'></div>
                                </td>
                            </tr>
                            <tr id='nameRow2' style='display:none;'>
                                <td class="" height="20">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="">
                                        <tr>
                                            <td colspan="2" class="content_title" align="right">
            <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();return false;"/>&nbsp;
            <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printReportCSV();return false;"/>
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
<!--Start Notice  Div-->
<?php floatingDiv_Start('divMessage','Brief Description','',''); ?>
<form name="MessageForm" action="" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td height="5px"></td></tr>
    <tr>
    <tr>
        <td width="89%" style="padding-left:5px">
            <div id="scroll2" style="overflow:auto; width:350px; height:200px; vertical-align:top;">
                <div id="message" style="width:98%; vertical-align:top;"></div>
            </div>
        </td>
    </tr>
</table>
</form>
<?php floatingDiv_End(); ?>
<?php
// $History: smsDetailReport.php $
//
//*****************  Version 9  *****************
//User: Parveen      Date: 11/25/09   Time: 11:41a
//Updated in $/LeapCC/Templates/SMSReports
//nowrap tag added table format
//
//*****************  Version 8  *****************
//User: Parveen      Date: 11/16/09   Time: 3:55p
//Updated in $/LeapCC/Templates/SMSReports
//date format check updated
//
//*****************  Version 7  *****************
//User: Parveen      Date: 8/21/09    Time: 3:58p
//Updated in $/LeapCC/Templates/SMSReports
//role permission & removePHPJS  function updated
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 8/19/09    Time: 11:23a
//Updated in $/LeapCC/Templates/SMSReports
//Gurkeerat: fixed issue 1135
//
//*****************  Version 5  *****************
//User: Parveen      Date: 8/18/09    Time: 6:40p
//Updated in $/LeapCC/Templates/SMSReports
//1136, 1137 show list message updated
//
//*****************  Version 4  *****************
//User: Parveen      Date: 7/20/09    Time: 4:14p
//Updated in $/LeapCC/Templates/SMSReports
//new enhancement added Action button perform Berif Description added
//
//*****************  Version 3  *****************
//User: Parveen      Date: 5/19/09    Time: 2:36p
//Updated in $/LeapCC/Templates/SMSReports
//code update search for & condition update
//
//*****************  Version 2  *****************
//User: Parveen      Date: 3/31/09    Time: 5:46p
//Updated in $/LeapCC/Templates/SMSReports
//formatting settings
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/SMSReports
//
//*****************  Version 7  *****************
//User: Parveen      Date: 11/28/08   Time: 5:36p
//Updated in $/Leap/Source/Templates/SMSReports
//list and report formatting
//
//*****************  Version 6  *****************
//User: Parveen      Date: 11/28/08   Time: 3:55p
//Updated in $/Leap/Source/Templates/SMSReports
//
//*****************  Version 5  *****************
//User: Parveen      Date: 11/28/08   Time: 3:08p
//Updated in $/Leap/Source/Templates/SMSReports
//list alignment setting
//
//*****************  Version 4  *****************
//User: Parveen      Date: 11/28/08   Time: 10:45a
//Updated in $/Leap/Source/Templates/SMSReports
//changed lists view format
//
//*****************  Version 3  *****************
//User: Parveen      Date: 11/27/08   Time: 5:22p
//Updated in $/Leap/Source/Templates/SMSReports
//add fields messages
//
//*****************  Version 2  *****************
//User: Parveen      Date: 11/26/08   Time: 5:06p
//Updated in $/Leap/Source/Templates/SMSReports
//sms details report added
//



?>