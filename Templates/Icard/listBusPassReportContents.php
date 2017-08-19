<?php
//This file creates Html Form output in Bus Pass Report Module 
//
// Author :Parveen Sharma
// Created on : 18-June-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<form name="searchForm" action="" method="post" onSubmit="return false;"> 
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
                    <td valign="top" class="content"><a id="lk1"  class="set_default_values">Set Default Values for Report Parameters</a>
                        <!-- form table starts -->
                        <table width="100%" border="0" cellspacing="0px" cellpadding="0px" class="contenttab_border2">
                            <tr>
                                <td valign="top" class="contenttab_row1">
                                        <table width="100%" align="center" border="0" cellspacing="0px" cellpadding="0px">
                                            <tr>
                                               <td valign='top' colspan='10' class='' style='text-align:left' >
                                                  <table align="left" border="0" cellpadding="0px" cellspacing="0px">
                                                   <tr>
                                                      <td valign='top' colspan='1' class='' style='text-align:left' ><b>
                                                        <nobr>Bus Pass Date Format&nbsp;</nobr>
                                                      </td>
                                                      <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>  
                                                      <td valign='top' colspan='1' class='' style='text-align:left'>
                                                        <nobr>
                                                            <select name='dateFormat' id='dateFormat' size='1' class='htmlElement2' style="width:80px" onChange="setLabel(); return false;">
                                                               <option value='1'>Expiry</option>
                                                               <option value='2'>Issue</option>
                                                            </select>&nbsp;
                                                        </nobr>
                                                      </td>
                                                      <td valign='top' colspan='2' class='' style='text-align:left;padding-left:24px' width="170px" ><b>
                                                        <nobr><span id='setDateFormat'></span>&nbsp;:&nbsp;</b></nobr>
                                                      </td>
                                                      <td valign='top' colspan='1' class='' style='text-align:left' > <nobr>
                                                          <?php
                                                            require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                            echo HtmlFunctions::getInstance()->datePicker('startDate',date('Y-m-d'));
                                                          ?></nobr>
                                                      </td>
                                                      <td valign='top' colspan='1' class='' style='text-align:leftl;padding-left:10px' ><nobr><b>&nbsp;To&nbsp;</b></nobr></td>
                                                      <td valign='top' colspan='1' class=''><b>:&nbsp;</b></td>  
                                                      <td valign='top' colspan='1' class='' style='text-align:left' > 
                                                        <nobr><?php
                                                               require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                               echo HtmlFunctions::getInstance()->datePicker('endDate',date('Y-m-d'));
                                                            ?>
                                                        </nobr>
                                                      </td>
                                                   </tr>
                                                 </table>
                                               </td> 
                                            </tr>
                                            <tr>
                                                <td height="5"></td>
                                            </tr>    
                                            <tr>
                                                <td valign='top' colspan='1' class='' style='text-align:left' nowrap><b>Bus Route&nbsp;</b></td>
                                                <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
                                                <td valign='middle' colspan='1' class='' style='text-align:left' >
                                                    <select multiple name='busRouteId[]' id='busRouteId' size='5' class='htmlElement2' style='width:165px' >
                                                       <?php 
                                                           require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                           echo HtmlFunctions::getInstance()->getBusRouteName();
                                                        ?>
                                                    </select><br>Select &nbsp;
                                                    <a class="allReportLink" href="javascript:makeSelection('busRouteId[]','All','searchForm');">All</a> / 
                                                    <a class="allReportLink" href="javascript:makeSelection('busRouteId[]','None','searchForm');">None</a>
                                                </td>
                                                <td valign='top' colspan='1' class='' style='text-align:left'><b>Bus Stop&nbsp;</b></td>
                                                <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
                                                <td valign='middle' colspan='1' class='' style='text-align:left' >
                                                    <select multiple name='busStopId[]' id='busStopId' size='5' class='htmlElement2' style='width:200px' >
                                                        <?php 
                                                           require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                           echo HtmlFunctions::getInstance()->getBusStopName();
                                                        ?>
                                                    </select><br>Select &nbsp;
                                                    <a class="allReportLink" href="javascript:makeSelection('busStopId[]','All','searchForm');">All</a> / 
                                                    <a class="allReportLink" href="javascript:makeSelection('busStopId[]','None','searchForm');">None</a>
                                                </td>
												
                                               <td valign='top' colspan='1' class='' style='text-align:left' >
                                                 <table> 
                                                 <tr>
                                                     <td valign='top' colspan='1' class='' style='text-align:left'><b>Current Classes&nbsp; </b></td> 
                                                     <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>   
                                                     <td valign='top' colspan='1' class='' style='text-align:left' >  
                                                        <select size="1" class="htmlElement" name="classId" id="classId" onChange="hideResults(); return false;" style="width:262px;">
                                                            <option value="">Select</option>
                                                            <?php 
                                                                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                                echo HtmlFunctions::getInstance()->getAllInstituteClass();?>
                                                        </select>
                                                    </td>
                                                    </tr>
                                                    <tr>
                                                     <td valign='top' colspan='1' class='' style='text-align:left'><b>Roll No. / Reg. No.&nbsp; </b></td> 
                                                     <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>   
                                                     <td valign='top' colspan='1' class='' style='text-align:left' > 
                                                        <input type="text" id="sRollNo" name="sRollNo" style="width:258px" maxlength="20" class="inputbox" />  
                                                    </td>
                                                    </tr>
                                                    <tr>
                                                     <td valign='top' colspan='1' class='' style='text-align:left'><b>Student Name&nbsp; </b></td> 
                                                     <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>   
                                                     <td valign='top' colspan='1' class='' style='text-align:left' > 
                                                        <input type="text" id="sName" name="sName" style="width:258px" maxlength="40" class="inputbox" />  
                                                    </td>
                                                    </tr>
                                                    <tr>
                                                     <td valign='top' colspan='1' class='' style='text-align:left'><b>Receipt No.&nbsp; </b></td> 
                                                     <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>   
                                                     <td valign='top' colspan='1' class='' style='text-align:left' > 
                                                        <input type="text" id="sReceiptNo" name="sReceiptNo" style="width:258px" maxlength="40" class="inputbox" />  
                                                    </td>
                                                    </tr>
                                                 </tr>
                                                </table>
                                               </td> 
                                                   
                                               <td valign='bottom' colspan='1' class='' style='text-align:left' >
                                                    <input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateAddForm1(this.form,'Show');return false;" />
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
                                            <td colspan="1" class="content_title">Display Bus Pass Report Details :</td>
                                            <td colspan="1" class="content_title" align="right" >
                                                <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/print.gif"  onClick="printReport();" />&nbsp;
                                                <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/excel.gif"  onClick="printCSV();" />&nbsp;
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr id='resultRow' style='display:none;'>
                                <td colspan='1' class='contenttab_row' valign="top">
                                    <div id = 'resultsDiv' style="overflow:auto; "></div>
                                </td>
                            </tr>
                            <tr id='nameRow2' style='display:none;'>
                                <td class="" height="20">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="">
                                        <tr>
                                            <td colspan="2" class="content_title" align="right">
                                                <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/print.gif"  onClick="printReport();" />&nbsp;
                                                <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/excel.gif"  onClick="printCSV();" />&nbsp;
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
/*
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
            <form action="" method="" name="searchForm" onSubmit="return false;">   
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
            <tr>
                <td height="10"></td>
            </tr>
            <tr>
                <td valign="top">Reports&nbsp;&raquo;&nbsp;Student Bus Pass</td>
                <td valign="middle" align="right"><input type="text" name="searchbox" class="inputbox" value="<?php echo $REQUEST_DATA['searchbox'];?>" style="margin-bottom: 2px;" size="30" />
                </td> 
                <td valign="top" align="right" width="80"><input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/search.gif"  onClick="sendReq(listURL,divResultName,searchFormName,'');return false;"/>&nbsp;</td> 
            </tr>
            </table>
            </form>
        </td>
    </tr>
    <tr>
        <td valign="top">
        <form action="" method="" name="allDetailsForm" onSubmit="return false;"> 
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
             <td valign="top" class="content">
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
             <tr>
                <td class="contenttab_border" height="20">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                        <td class="content_title">Student Bus Pass Details : </td>
                        <td class="content_title" title="Add">
                        <img src="<?php echo IMG_HTTP_PATH;?>/add.gif" 
                        align="right" onClick="displayWindow('AddBusPass',340,250);blankValues();return false;"  />&nbsp;</td>
                        <td width="10px" class="content_title" title="Print" align="right" style="padding-right:10px"><input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport()"/></td>  
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" ><div id="results"> </div>
            </td>
           </tr>
         </table>
      </form>
    </td>
    </tr>
    </table>   

<!--Start Add Div-->
<?php floatingDiv_Start('AddBusPass',''); ?>
<form name="addBusPass" action="" method="post">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <input type="hidden" name="busPassId" id="busPassId" value="" />
    <tr> 
      <td width="15%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<strong>Student Details</strong></nobr></td>    
      <td width="4%" class="contenttab_internal_rows" style="text-align:right"><nobr><strong>:</strong></nobr></td>    
      <td width="65%" class="padding" align="left" valign="top">
        <b><div id="studentRollNo"></div></b>
        <input type="hidden" id="regNo" name="regNo" style="width:180px" maxlength="20" class="inputbox" onBlur="getStudentDetails(); return false;"/>
      </td>
     </tr>
     <tr id='studentDetail' style='display:none;'>
      <td width="15%" class="contenttab_internal_rows">&nbsp;&nbsp;</td>
      <td width="4%"  class="contenttab_internal_rows" style="text-align:right">&nbsp;</td>    
      <td width="65%" class="padding" align="left" valign="top">
        <b><div id="studentName"></div></b><br>
        <b><div id="className"></div></b>
        <input type="hidden" name="studentId" id="studentId" value="" />
        <input type="hidden" name="classId" id="classId" value="" />
        <input type="hidden" name="currentDate" id="currentDate" value="<?php echo date('Y-m-d'); ?>" />
      </td>
     </tr>
     
     <tr> 
     <td width="15%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<strong>Bus Route</strong></nobr></td>                          
     <td width="4%" class="contenttab_internal_rows" style="text-align:right"><nobr><strong>:</strong></nobr></td>    
      <td width="65%" class="padding" align="left">
       <div id="busRouteDiv" style="display:'';">
          <select size="1" class="selectfield" name="studentRoute" id="studentRoute" onChange="autoPopulate(this.value,'busRoute','addBusPass','','studentStop');">
          <option value="">Select</option>
            <?php
              require_once(BL_PATH.'/HtmlFunctions.inc.php');
              echo HtmlFunctions::getInstance()->getBusRouteName();
            ?>
           </select>
       </div>
       <div id="busRouteDiv1" style="display:'none';"></div> 
     </td>
    </tr>
    <tr> 
     <td width="15%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<strong>Bus Stoppage</strong></nobr></td>
     <td width="4%" class="contenttab_internal_rows" style="text-align:right"><nobr><strong>:</strong></nobr></td>    
      <td width="65%" class="padding" align="left">
      <div id="busStopDiv" style="display:'';">
        <select size="1" class="selectfield" name="studentStop" id="studentStop" >
          <option value="">Select</option>
        </select>
      </div>
      <div id="busStopDiv1" style="display:'none';"></div> 
     </td>
    </tr>
    <tr> 
      <td width="15%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<strong>Receipt No.</strong></nobr></td>
      <td width="4%" class="contenttab_internal_rows" style="text-align:right"><nobr><strong>:</strong></nobr></td>    
      <td width="65%" class="padding" align="left">
        <div id="receiptDiv" style="display:'';">
           <input type="text" id="receiptNo" name="receiptNo" style="width:180px" class="inputbox" />
        </div>
        <div id="receiptDiv1" style="display:'none';"> </div>
     </td>
    </tr>
    <tr>
        <td width="15%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Valid Upto</b></nobr></td>
        <td width="4%" class="contenttab_internal_rows" style="text-align:right"><nobr><strong>:</strong></nobr></td>    
        <td width="65%" class="padding"  align="left" colspan="2">
        <div id="validDiv" style="display:'';">
        <?php
              require_once(BL_PATH.'/HtmlFunctions.inc.php');
              echo HtmlFunctions::getInstance()->datePicker('validUpto','');
        ?>
        </div>
        <div id="validDiv1" style="display:'none';"></div>  
      </td>
   </tr>
   <tr id="busPassStatusDiv" style="display:'none';"> 
      <td width="15%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<strong>Bus Pass Status</strong><?php echo REQUIRED_FIELD;?></nobr></td>                          <td width="4%" class="contenttab_internal_rows" style="text-align:right"><nobr><strong>:</strong></nobr></td>    
      <td width="65%" class="padding" align="left">
       <select id="busPassStatus" name="busPassStatus" class="selectfield">
            <option value="">Select</option>
            <?php
                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                echo HtmlFunctions::getInstance()->getBusPassStatus();
            ?>
        </select>
     </td>
    </tr>
   <tr>
    <td height="5px"></td></tr>
  <tr>
    <td align="center" colspan="4">
       <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif"  onClick="return validateAddForm(this.form,'Add');return false;" />&nbsp;
       <span id="editStudentDetail" style="display:'';">
       <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save_print.gif"  onClick="return validateAddForm(this.form,'Print');return false;" />&nbsp;
       </span>
       <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('AddBusPass');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;"/>&nbsp;
    </td>
  </tr>
  <tr>
    <td height="5px"></td></tr>
  <tr>
</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->
* 
*/
#000000?>

<!--Start Topic  Div-->

<?php floatingDiv_Start('divDuplicateReceiptInformation','Error(s)/Warning(s)'); ?>
<form name="divReceiptForm" action="" method="post">  
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td height="5px"></td></tr>
    <tr>
    <tr>    
        <td width="95%" align="center">
            <div id="receiptInfo" style="overflow:auto; width:400px; height:350px"></div>
        </td>
    </tr>
</table>
</form> 
<?php floatingDiv_End(); ?>


<?php 
// $History: listBusPassReportContents.php $	
//
//*****************  Version 3  *****************
//User: Parveen      Date: 2/12/10    Time: 3:14p
//Updated in $/LeapCC/Templates/Icard
//bus receiptNo. field added (format & validation updated)
//
//*****************  Version 2  *****************
//User: Parveen      Date: 2/02/10    Time: 5:54p
//Updated in $/LeapCC/Templates/Icard
//look & feel updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/02/10    Time: 3:56p
//Created in $/LeapCC/Templates/Icard
//inital checkin
//
//*****************  Version 14  *****************
//User: Gurkeerat    Date: 12/17/09   Time: 10:19a
//Updated in $/LeapCC/Templates/Icard
//Updated breadcrumb according to the new menu structure
//
//*****************  Version 13  *****************
//User: Parveen      Date: 8/21/09    Time: 5:40p
//Updated in $/LeapCC/Templates/Icard
//formatting & role permission added
//
//*****************  Version 12  *****************
//User: Parveen      Date: 6/29/09    Time: 12:20p
//Updated in $/LeapCC/Templates/Icard
//regNo. wise search conditions updated
//
//*****************  Version 11  *****************
//User: Parveen      Date: 6/23/09    Time: 6:42p
//Updated in $/LeapCC/Templates/Icard
//formatting & deactive code update
//
//*****************  Version 10  *****************
//User: Parveen      Date: 6/22/09    Time: 4:13p
//Updated in $/LeapCC/Templates/Icard
//issue fix Format, validation added
//
//*****************  Version 9  *****************
//User: Parveen      Date: 6/22/09    Time: 2:29p
//Updated in $/LeapCC/Templates/Icard
//formating, validations, messages, conditions  changes 
//
//*****************  Version 8  *****************
//User: Parveen      Date: 6/17/09    Time: 6:09p
//Updated in $/LeapCC/Templates/Icard
//rollno by search condition update
//
//*****************  Version 7  *****************
//User: Parveen      Date: 6/16/09    Time: 12:41p
//Updated in $/LeapCC/Templates/Icard
//busPass Status add disabled / edit enabled  
//
//*****************  Version 6  *****************
//User: Parveen      Date: 6/16/09    Time: 11:18a
//Updated in $/LeapCC/Templates/Icard
//validation & formatting updated
//
//*****************  Version 5  *****************
//User: Parveen      Date: 6/15/09    Time: 5:30p
//Updated in $/LeapCC/Templates/Icard
//display messages update
//
//*****************  Version 4  *****************
//User: Parveen      Date: 6/15/09    Time: 4:36p
//Updated in $/LeapCC/Templates/Icard
//issue fix (timetableLabelId remove)
//
//*****************  Version 3  *****************
//User: Parveen      Date: 6/15/09    Time: 4:04p
//Updated in $/LeapCC/Templates/Icard
//link menu update
//
//*****************  Version 2  *****************
//User: Parveen      Date: 6/15/09    Time: 12:34p
//Updated in $/LeapCC/Templates/Icard
//validation, conditions & formatting updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 6/13/09    Time: 2:54p
//Created in $/LeapCC/Templates/Icard
//initial checkin
//

?>
    

