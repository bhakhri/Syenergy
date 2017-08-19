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
                                    <form name="allDetailsForm" id="allDetailsForm" action="" method="post" onSubmit="return false;">
                                        <table align="left" border="0" cellspacing="5px" cellpadding="5px" width="80%">  
                                          <tr>
                                            <td class="contenttab_internal_rows" colspan="5">
                                              <table align="left" border="0" cellspacing="0px" cellpadding="0px">  
                                                <tr>
                                                    <td class="contenttab_internal_rows" ><nobr><strong>Date</strong></nobr></td> 
                                                    <td class="contenttab_internal_rows"><nobr><strong>&nbsp;:&nbsp;</strong></nobr></td>
                                                    <td class="contenttab_internal_rows"><nobr><strong>
                                                        <?php 
                                                             require_once(BL_PATH.'/HtmlFunctions.inc.php'); 
                                                             echo HtmlFunctions::getInstance()->datePicker('allocationDate',date('Y-m-d'));
                                                        ?>
                                                    </td>
                                                    <td class="contenttab_internal_rows" style="padding-left:35px" ><nobr><strong>Show</strong></nobr></td> 
                                                    <td class="contenttab_internal_rows"><nobr><strong>&nbsp;:&nbsp;</strong></nobr></td>
                                                    <td class="contenttab_internal_rows" valign="top"><nobr>
                                                        <input type="checkbox" checked="checked" id="alotSeat" name="alotSeat" value="1">Allotted Seats
                                                    </nobr></td>    
                                                    <td class="contenttab_internal_rows" style="padding-left:5px" valign="top"><nobr>    
                                                        &nbsp;<input type="checkbox" checked="checked" id="rptSeat" name="rptSeat" value="1">Reported Seats
                                                    </nobr></td>    
                                                    <td class="contenttab_internal_rows" style="padding-left:5px" valign="top"><nobr>    
                                                        &nbsp;<input type="checkbox" checked="checked" id="vcnSeat" name="vcnSeat" value="1">Vacant Seats
                                                    </nobr></td>
                                                 </tr>     
                                              </table>  
                                            </td>
                                          </tr>      
                                          <tr>
                                            <td width="16%" class="contenttab_internal_rows" valign="top"><nobr><b>Class </nobr></b></td>
                                            <td width="16%" class="contenttab_internal_rows" valign="top"><nobr><b>Quota </b></nobr></td>
                                            <td width="16%" class="contenttab_internal_rows" valign="top"><nobr><b>Round </b></nobr></td>
                                          </tr>
                                          <tr>
                                            <td width="10%" class="contenttab_internal_rows"><nobr>
                                                <select multiple name='classId[]' id='classId' size='5' class='inputbox1' style='width:280px'>
                                                <?php
                                                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                  echo HtmlFunctions::getInstance()->getCounsellingClassData();
                                                ?>
                                                </select><br>
                                                <div align="left">
                                                Select &nbsp;
                                                <a class="allReportLink" href="javascript:makeSelection('classId[]','All','allDetailsForm');">All</a> / 
                                                <a class="allReportLink" href="javascript:makeSelection('classId[]','None','allDetailsForm');">None</a>
                                                </div></nobr>
                                            </td>
                                             <td width="10%" class="contenttab_internal_rows"><nobr>
                                                <select multiple name='quotaId[]' id='quotaId' size='5' class='inputbox1' style='width:350px'>
                                                <?php
                                                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                  echo HtmlFunctions::getInstance()->getCurrentCategories($configsRecordArray[$i]['value'],' WHERE parentQuotaId=0 ',$showParentCat='1');
                                                ?>
                                                </select><br>
                                                <div align="left">
                                                Select &nbsp;
                                                <a class="allReportLink" href="javascript:makeSelection('quotaId[]','All','allDetailsForm');">All</a> / 
                                                <a class="allReportLink" href="javascript:makeSelection('quotaId[]','None','allDetailsForm');">None</a>
                                                </div></nobr>
                                            </td>
                                            <td width="10%" class="contenttab_internal_rows"><nobr>
                                                <select multiple name='roundId[]' id='roundId' size='5' class='inputbox1' style='width:150px' >
                                                <?php
                                                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                  echo HtmlFunctions::getInstance()->getCounsellingRoundsData();
                                                ?>
                                                </select><br>
                                                <div align="left">
                                                Select &nbsp;
                                                <a class="allReportLink" href="javascript:makeSelection('roundId[]','All','allDetailsForm');">All</a> / 
                                                <a class="allReportLink" href="javascript:makeSelection('roundId[]','None','allDetailsForm');">None</a>
                                                </div></nobr>
                                            </td>
                                            <td class="contenttab_internal_rows" valign="bottom" align="right" style="padding-left:20px" >
                                                <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateAddForm(this.form);"/>
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
                                            <td colspan="1" class="content_title">Seat Allocation Details :</td>
                                            <td colspan="2" class="content_title" align="right">
            <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();return false;"/>&nbsp;
            <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printReportCSV();return false;"/> 
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr id='resultRow' style='display:none;'>
                                <td valign='top'  colspan='1' class='contenttab_row'>
                                    <div id="scroll2" style="overflow:auto; width:1050px; height:520px; vertical-align:top;">
                                       <div id="resultsDiv" style="width:100%; vertical-align:top;"></div>
                                    </div>
                                </td>
                            </tr>
                            <tr id='pageRow' style='display:none;'>    
                                <td valign='top' colspan='1'  class=''>
                                  <table width="98%" valign='top' border="0" class='' cellspacing="0" cellpadding="0" >
                                   <tr>
                                     <td valign='top' colspan='1'  class='' align='left'>    
                                        <span id = 'pagingDiv1' class='contenttab_row1' align='left'></span>
                                     </td>
                                     <td valign='top' colspan='1'  class='' align='right'>   
                                        <span id = 'pagingDiv' align='right'></span> 
                                     </td>
                                   </tr>
                                  </table>      
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