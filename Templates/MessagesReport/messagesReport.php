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
                                                <td class="contenttab_internal_rows"  nowrap >
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
							        <input type="text" style="width:470px" name="txtSearch" id="txtSearch" value="" class="inputbox"/>
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
                                            <td colspan="1" class="content_title">Messages Reports :</td>
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
<?php floatingDiv_Start('divMessage','Undelivered Recipients  ','',''); ?>
<form name="MessageForm" action="" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td height="15px"></td></tr>
    <tr>
    <tr>
        <td width="100%" style="padding-left:5px;vertical-align:top;">
            <div id="scroll2" style="overflow:auto; width:510px; height:300px; vertical-align:top;">
                <div id="message" style="width:100%; vertical-align:top;"></div>
            </div>
			<tr>
				<td colspan="2" class="content_title" align="right">
					<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printUndeliveredReport();return false;"/>&nbsp;
					<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printUndeliveredReportCSV();return false;"/>
				</td>
			</tr>
		</td>
		
		

    </tr>
</table>
</form>
<?php floatingDiv_End(); ?>

<?php floatingDiv_Start('deliverdMessages','Delivered Recipients  ','',''); ?>
<form name="deliverdMessagesForm" action="" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td height="15px"></td></tr>
    <tr>
    <tr>
        <td width="89%" style="padding-left:5px;vertical-align:top;">
            <div id="scroll3" style="overflow:auto; width:510px; height:300px; vertical-align:top;">
                <div id="divDeliveredMessages" style="width:98%; vertical-align:top;"></div>
            </div>
			<tr>
				<td colspan="2" class="content_title" align="right">
					<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printDeliverdReport();return false;"/>&nbsp;
					<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printDeliveredReportCSV();return false;"/>
				</td>
			</tr>
        </td>
    </tr>
</table>
</form>
<?php floatingDiv_End();?>
<?php floatingDiv_Start('briefDescription','Brief Description','',''); ?>
<form name="briefDescriptionForm" action="" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td height="5px"></td></tr>
    <tr>
    <tr>
        <td width="89%" style="padding-left:5px">
            <div id="scroll2" style="overflow:auto; width:350px; height:200px; vertical-align:top;">
                <div id="briefmessage" style="width:98%; vertical-align:top;"></div>
            </div>
        </td>
    </tr>
</table>
</form>
<?php floatingDiv_End(); ?>
