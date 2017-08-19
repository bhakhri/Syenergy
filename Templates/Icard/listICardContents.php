<?php 
//-------------------------------------------------------
//  This File contains html form for all details report
//
//
// Author :Ajinder Singh
// Created on : 13-Sep-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(BL_PATH.'/HtmlFunctions.inc.php');
$htmlFunctions = HtmlFunctions::getInstance();
?>

<form action="" method="POST" name="allDetailsForm" id="allDetailsForm" onSubmit="return false;">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
          <?php require_once(TEMPLATES_PATH . "/breadCrumb.php");   ?>
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
                                      <table border='0' width='100%' cellspacing='0px' cellpadding='1px'>
                                        <tr>
                                          <td valign='middle' colspan='1' class='' style='text-align:left'><nobr><b>Card Format&nbsp;</nobr></b></td>
                                          <td valign='middle' colspan='1' class=''><nobr><b>:&nbsp;&nbsp;</b></nobr></td>
                                          <td valign='middle' colspan='1' class='' align='left'><nobr>
     <select size="1" style="width:200px" class="selectfield" name="cardView" id="cardView" onchange="hideDetails(); getAdmitCard(this.value); return false;">
                                        <option value="">Select </option>
                                        <?php
                                            require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                            echo HtmlFunctions::getInstance()->getICardData();
                                        ?>
                                            </select></nobr>
                                          </td>
                  <td valign='middle' colspan='6' class='' style='text-align:left'> 
                        <span id='iCard' style='display:none;'>
                            <table border='0' width='100%' cellspacing="0px" cellpadding="1px" class="contenttab_row2" >
                            <tr>  
                                <td valign='middle' colspan='1' class='' style='text-align:left;width:103px;'><b>Title&nbsp; </b></td>
                                <td valign='middle' colspan='1' class='' style='text-align:right;width:5px;'><nobr><b>&nbsp;:</b></nobr></td>
                                <td valign='middle' colspan='4' class='' style='text-align:left'><nobr>&nbsp;
                                    <input type='text' class='inputbox1' name='icardTitle' id='icardTitle' maxlength="35" style='width:240px;text-align:left'/>
                                    </nobr>
                                </td>
                                <td valign='middle' colspan='1' class='' style='text-align:right;'><b>Show&nbsp; </b></td>
                                <td valign='middle' colspan='1' class='' style='text-align:left;'><nobr><b>&nbsp;:</b></nobr></td>
                                <td valign='middle' colspan='1' class='' style='text-align:left;'><nobr>
                                    <input class="inputbox1" checked="checked" type="radio" id="chkOption" name="chkOption" value="1">&nbsp;Roll. No.</nobr>
                                </td>    
                                <td valign='middle' colspan='1' class='' style='text-align:left;'><nobr>
                                    <input class="inputbox1" type="radio" id="chkOption" name="chkOption" value="2">&nbsp;Univ. No.</nobr>
                                </td>   
                                <td valign='middle' colspan='1' class='' style='text-align:left;'><nobr>
                                    <input class="inputbox1"  type="radio" id="chkOption" name="chkOption" value="3">&nbsp;Reg. No.</nobr>
                                </td>    
                            </tr>
                            </table>
                        </span>
                        <span id='adminCard' style='display:none;'>
                           <table border='0' width='100%' cellspacing="0px" cellpadding="1px" class="contenttab_row2" >
                           <tr>  
                                <td valign='middle' colspan='1' class='' style='width:102px;text-align:left;'>
                                    <nobr><B>Mid Exam.&nbsp;</B></nobr>
                                </td>
                                <td valign='middle' colspan='1' class='' style='text-align:left;'>
                                    <nobr><b>&nbsp;:</b></nobr>
                                </td>
                                <td valign='middle' colspan='1' class=''>
                              <nobr>&nbsp;<input size="38" type="text" id="heading1" name="heading1" class="inputbox1" maxlength="100">&nbsp;</nobr>
                                </td>
                                <td valign='middle' colspan='1' class=''>
                                    <nobr>&nbsp;<B>Exam. Date</B></nobr>
                                </td>
                                <td valign='middle' colspan='1' class='' style='text-align:right;'>
                                    <nobr><b>:</b>&nbsp;&nbsp;</nobr>
                                </td>
                                <td valign='middle' colspan='1' class=''>
                                   <nobr><input size="40" type="text" id="heading2" name="heading2" class="inputbox1" maxlength="100"></nobr>
                                </td>
                           </tr>
                           </table>     
                        </span>
                        <span id='busPass' style='display:none;'>
                           <nobr><b>Receipt No. : </b></nobr>&nbsp;&nbsp;
                           <input size="40" type="text" id="busReceipt" name="busReceipt" class="inputbox1" maxlength="20">
                           &nbsp;&nbsp;&nbsp;&nbsp;<nobr><b>Valid : </b></nobr>&nbsp;&nbsp;
                           <input size="35" type="text" id="busValidity" name="busValidity" class="inputbox1" maxlength="50" >
                        </span>
                  </td>
            </tr>
                                        </tr>
                                            <?php echo $htmlFunctions->makeStudentDefaultSearch(); ?>
                                            <tr height='5'></tr>
                                            <?php echo $htmlFunctions->makeStudentAcademicSearch(); ?>
                                            <tr height='5'></tr>
                                            <?php echo $htmlFunctions->makeStudentAddressSearch(); ?>
                                            <tr height='5'></tr>
                                            <?php echo $htmlFunctions->makeStudentMiscSearch(); ?>
                                            <tr>
                                                <td valign='top' colspan='11' class='' align='center'>
                                                <input type="hidden" name="degs" value="">
                                                <input type="hidden" name="degsText" value="">
                                                <input type="hidden" name="brans" value="">
                                                <input type="hidden" name="branText" value="">
                                                
                                                <input type="hidden" name="subjectId" value="">
                                                <input type="hidden" name="periods" value="">
                                                <input type="hidden" name="periodsText" value="">

                                                <input type="hidden" name="course" value="">
                                                <input type="hidden" name="courseText" value="">

                                                <input type="hidden" name="grps" value="">
                                                <input type="hidden" name="grpsText" value="">
                                                
                                                <input type="hidden" name="univs" value="">
                                                <input type="hidden" name="univsText" value="">

                                                <input type="hidden" name="citys" value="">
                                                <input type="hidden" name="citysText" value="">

                                                <input type="hidden" name="states" value="">
                                                <input type="hidden" name="statesText" value="">
                                                
                                                <input type="hidden" name="cnts" value="">
                                                <input type="hidden" name="cntsText" value="">

                                                <input type="hidden" name="hostels" value="">
                                                <input type="hidden" name="hostelsText" value="">

                                                <input type="hidden" name="buss" value="">
                                                <input type="hidden" name="bussText" value="">

                                                <input type="hidden" name="routs" value="">
                                                <input type="hidden" name="routsText" value="">
                                                <input type="hidden" name="quotaText" value="">
                                                <input type="hidden" name="bloodGroupText" value="">
                                                
                                                </td>
                                            </tr>
                                            <tr>
                                                <td valign='top' colspan='11' class='' align='center'>
                                                    <input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateAddForm();return false;" />
                                                </td>
                                            </tr>
                                        </table>
                                </td>
                            </tr>
                        </table>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr id='nameRow' style='display:none;'>
                                <td class="" height="20">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="contenttab_border">
                                        <tr>
                                            <td colspan="1" class="content_title">Student Detail(s) :</td>
                                            <td colspan="1" class="content_title" align="right">
                                                <input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr id='resultRow' style='display:none;'>
                                <td colspan='1' class='contenttab_row'>
                                    <div id="resultsDiv" style="overflow:auto; vertical-align:top;" > 
                                    </div>
                                </td>
                            </tr>
                            <tr id='nameRow2' style='display:none;'>
                                <td class="" height="20">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="">
                                        <tr>
                                            <td colspan="2" class="content_title" align="right">
                                                <input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />
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
</form>        
<?php 
//$History: listICardContents.php $
//
//*****************  Version 17  *****************
//User: Gurkeerat    Date: 12/22/09   Time: 4:37p
//Updated in $/LeapCC/Templates/Icard
//removed border
//
//*****************  Version 16  *****************
//User: Gurkeerat    Date: 12/22/09   Time: 4:36p
//Updated in $/LeapCC/Templates/Icard
//Updated look n feel
//
//*****************  Version 15  *****************
//User: Parveen      Date: 12/22/09   Time: 4:21p
//Updated in $/LeapCC/Templates/Icard
//look & feel updated
//
//*****************  Version 14  *****************
//User: Parveen      Date: 12/19/09   Time: 5:04p
//Updated in $/LeapCC/Templates/Icard
//format updated
//
//*****************  Version 12  *****************
//User: Gurkeerat    Date: 12/17/09   Time: 10:19a
//Updated in $/LeapCC/Templates/Icard
//Updated breadcrumb according to the new menu structure
//
//*****************  Version 11  *****************
//User: Parveen      Date: 10/20/09   Time: 3:34p
//Updated in $/LeapCC/Templates/Icard
//sorting order updated (bug no. 1696)
//
//*****************  Version 10  *****************
//User: Parveen      Date: 10/15/09   Time: 4:38p
//Updated in $/LeapCC/Templates/Icard
//table name updated
//
//*****************  Version 9  *****************
//User: Parveen      Date: 10/09/09   Time: 5:35p
//Updated in $/LeapCC/Templates/Icard
//look & feel updated 
//1703, 1699, 1649, 1648, 1640 
//
//*****************  Version 8  *****************
//User: Parveen      Date: 10/01/09   Time: 4:59p
//Updated in $/LeapCC/Templates/Icard
//icard title input box added
//
//*****************  Version 7  *****************
//User: Parveen      Date: 10/01/09   Time: 10:50a
//Updated in $/LeapCC/Templates/Icard
//condition updated hasAttendance, hasMarks & formatting updated
//
//*****************  Version 6  *****************
//User: Gurkeerat    Date: 9/29/09    Time: 5:43p
//Updated in $/LeapCC/Templates/Icard
//resolved issue 1634
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 9/04/09    Time: 12:41p
//Updated in $/LeapCC/Templates/Icard
//Gurkeerat: updated breadcrumb
//
//*****************  Version 4  *****************
//User: Parveen      Date: 8/28/09    Time: 5:03p
//Updated in $/LeapCC/Templates/Icard
//issue fix format & conditions & alignment updated
//
//*****************  Version 3  *****************
//User: Parveen      Date: 6/11/09    Time: 5:23p
//Updated in $/LeapCC/Templates/Icard
//conditions, validation & formatting updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 4/23/09    Time: 2:48p
//Updated in $/LeapCC/Templates/Icard
//formatting update (admitCard, Buspass, Icard)
//
//*****************  Version 1  *****************
//User: Parveen      Date: 1/12/09    Time: 3:48p
//Created in $/LeapCC/Templates/Icard
//Icard added
//
//*****************  Version 2  *****************
//User: Parveen      Date: 12/27/08   Time: 4:26p
//Updated in $/Leap/Source/Templates/ScICard
//checkbox added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 12/26/08   Time: 4:29p
//Created in $/Leap/Source/Templates/ScICard
//initial checkin
//
//


?>
