<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR CITY LISTING 
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>

<?php  
  global $sessionHandler;
  $leaveAuthorizersId=$sessionHandler->getSessionVariable('LEAVE_AUTHORIZERS');  
  if($leaveAuthorizersId=='') {
     $leaveAuthorizersId=1;  
  }
  
  $pendingStatus = "";
  if($leaveAuthorizersId==2) {
    $pendingStatus = 'onclick="togglePendingStatus(this.value);"';  
  }

require_once(TEMPLATES_PATH . "/breadCrumb.php");
?>
    <tr>
		<td valign="top" colspan="2">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
					<td valign="top" class="content">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							
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
                        <td class="content_title">Employee Leaves History Report : </td>
                        <td class="content_title"></td>
                    </tr>
                    </table>
                </td>                       
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" >
                <form name="searchForm" method="post" action="" onsubmit="return false;">
                  <table border="0" cellpadding="0" cellspacing="0">
                   <tr>
                     <td class="contenttab_internal_rows"><b>Leave Session</b></td>
                     <td class="padding">:</td>
                     <td class="padding">
                      <select name="leaveSessionId" id="leaveSessionId" class="inputbox" onchange="vanishData();" >
                       <option value="">Select</option>
                       <?php
                           require_once(BL_PATH.'/HtmlFunctions.inc.php');
                           echo HtmlFunctions::getInstance()->getLeaveSessionData();
                        // echo HtmlFunctions::getInstance()->getSessionYearData();
                       ?>
                      </select>
                     </td>
                     <td class="contenttab_internal_rows"><b>Employee</b></td>
                     <td class="padding">:</td>
                     <td class="padding" colspan="5">
                      <select name="employeeDD" id="employeeDD" class="inputbox" onchange="vanishData();" >
                       <option value="">Select</option>
                       <?php
                         require_once(BL_PATH.'/HtmlFunctions.inc.php');
                         echo HtmlFunctions::getInstance()->getTeacher('' ,1);
                       ?>
                      </select>
                     </td>
                   <td class="contenttab_internal_rows"><b>Report Type</b></td>
                   <td class="padding">:</td>
                   <td class="contenttab_internal_rows">
                    <select name="reportType" id="reportType" class="inputbox" style="width:120px;" onchange="vanishData();" />
                     <option value="1">List View</option>
                     <option value="2">Graphical View</option>
                    </select>
                   </td>
                   <td style="padding-left:5px;">
                       <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/showlist.gif" onClick="generateReport();" >&nbsp;
                   </td>
                  </tr> 
                  </table>
                  </form>
                   <div id="results">  </div>           
                </td>
             </tr>
             <tr id="printTrId" style="display:none;">
                <td>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                        <tr>
                            <td class="content_title" valign="middle" align="right" width="20%">
                              <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();" >&nbsp;
                              <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printReportCSV();" >
                            </td>  
                        </tr>
                    </table>
                </td>
             </tr>
          </table>
        </td>
    </tr>
    
    </table>
    </td>
    </tr>
    </table>


<!--Start View Div-->
<?php floatingDiv_Start('ViewApplyLeave','View Leave Details'); ?>
<form name="ViewApplyLeave" action="" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
     <tr>
     <td width="5%" class="contenttab_internal_rows"><nobr>&nbsp;<b>Employee Code</b></nobr></td>
     <td class="padding" width="1%">:</td>
     <td class="contenttab_internal_rows" colspan="4" >&nbsp;
      <div id="emplCodeDiv" style="display:inline;" />
     </td>
   </tr>
   <tr>
     <td width="5%" class="contenttab_internal_rows"><nobr>&nbsp;<b>Employee Name</b></nobr></td>
     <td class="padding" width="1%">:</td>
     <td class="contenttab_internal_rows" colspan="4">&nbsp;
      <div id="emplNameDiv" style="display:inline;" />
     </td>
   </tr>
   <tr>
     <td class="contenttab_internal_rows">&nbsp;<nobr><strong>Leave Type</strong></nobr></td>
     <td class="padding" width="1%">:</td>
     <td width="24%" class="contenttab_internal_rows" colspan="4">&nbsp;
      <div id="emplLeaveTypeDiv" style="display:inline;" />
     </td>
   </tr>
   <tr>
     <td width="5%" class="contenttab_internal_rows"><nobr>&nbsp;<b>Leave From</b></nobr></td>
     <td class="padding">:</td>
     <td class="contenttab_internal_rows" nowrap="nowrap" >&nbsp;
      <div id="emplLeaveFromDiv" style="display:inline;" />
     </td>
     <td width="5%" class="contenttab_internal_rows"><nobr>&nbsp;<b>Leave To</b></nobr></td>
     <td class="padding">:</td>
     <td class="contenttab_internal_rows" nowrap="nowrap" >&nbsp;
      <div id="emplLeaveToDiv" style="display:inline;" />
     </td>
   </tr>
   <tr>
     <td width="5%" class="contenttab_internal_rows" valign="top"><nobr>&nbsp;<b>Reason</b></nobr></td>
     <td class="padding" width="1%" valign="top">:</td>
     <td class="contenttab_internal_rows" colspan="4">&nbsp;
      <div id="emplLeaveReasonDiv" style="display:inline;" />
     </td>
   </tr>
   <tr>
     <td width="5%" class="contenttab_internal_rows"><nobr>&nbsp;<b>Application Date</b></nobr></td>
     <td class="padding" width="1%">:</td>
     <td class="contenttab_internal_rows">&nbsp;
      <div id="emplLeaveApplicationDateDiv" style="display:inline;" />
     </td>
     <td width="5%" class="contenttab_internal_rows"><nobr>&nbsp;<b>Leave Status</b></nobr></td>
     <td class="padding" width="1%">:</td>
     <td class="contenttab_internal_rows">&nbsp;
      <div id="emplLeaveStatusDiv" style="display:inline;" />
     </td>
   </tr>
   <tr>
     <td width="5%" class="contenttab_internal_rows" valign="top"><nobr>&nbsp;<b>Comments(1st Appr.)</b></nobr></td>
     <td class="padding" width="1%" valign="top">:</td>
     <td class="contenttab_internal_rows" colspan="4">&nbsp;
      <div id="firstCommentsDiv" style="display:inline;" />
     </td>
   </tr>
   <tr>
     <td width="5%" class="contenttab_internal_rows" valign="top"><nobr>&nbsp;<b>Comments(2nd Appr.)</b></nobr></td>
     <td class="padding" width="1%" valign="top">:</td>
     <td class="contenttab_internal_rows" colspan="4">&nbsp;
      <div id="secondCommentsDiv" style="display:inline;" />
     </td>
   </tr>
 <tr><td height="5px" colspan="6"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="6">
       <input type="image" name="editCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('ViewApplyLeave');return false;" />
     </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
</table>
</form>   
<?php floatingDiv_End(); ?>
<!--End: Div To View The Table-->    

<?php
// $History: listCityContents.php $
?>