<?php 
//This file creates Html Form output for create parent Login
//
// Author :Parveen Sharma
// Created on : 20-oct-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>

<form name="allDetailsForm" action="" method="post" onSubmit="return false;">
  <input readonly type="hidden" name="currentDate" id="currentDate" value="<?php echo date('Y-m-d'); ?>" />      
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
                                   <table border='0' width='70%' cellspacing="2px" cellpadding="0"  >
                                        <tr>             
                                            <td class="contenttab_internal_rows"><nobr><b>&nbsp;Students Counseling From Sr. No.<?php echo REQUIRED_FIELD ?></B></nobr></td>
                                            <td class="contenttab_internal_rows"><nobr><b>:&nbsp;</b></nobr></td>
                                            <td class="contenttab_internal_rows">
                                              <nobr> 
                                                <input type="text" id="startingRecord" name="startingRecord" class="inputbox1" maxlength="5" value="1" style="width:50px">
                                              </nobr>
                                            </td>
                                            <td class="contenttab_internal_rows">                                                                                                   
                                            <nobr><b>&nbsp;Counseling To Sr. No.&nbsp;<?php echo REQUIRED_FIELD ?></b></nobr></td>
                                            <td class="contenttab_internal_rows"><nobr><b>:&nbsp;</b></nobr></td>
                                            <td class="contenttab_internal_rows">
                                              <input id="totalRecords" name="totalRecords" type="text" class="inputbox1" maxlength="5" value="500" style="width:50px">
                                            </td>
                                            <td class="contenttab_internal_rows">                                                                                                   
                                            <nobr><b>&nbsp;Sort Field<?php echo REQUIRED_FIELD ?></b></nobr></td>
                                            <td class="contenttab_internal_rows"><nobr><b>:&nbsp;</b></nobr></td>
                                            <td class="contenttab_internal_rows">
                                              <select name="sortField1" id="sortField1" size="1" class="selectfield" style="width:95px">  
                                               <option value='1'>Rankwise</option> 
                                               <option value='2'>Namewise</option>
                                              </select> 
                                            </td>
                                            <td class="contenttab_internal_rows">                                                                                                   
                                            <nobr><b>&nbsp;Order By<?php echo REQUIRED_FIELD ?></b></nobr></td>
                                            <td class="contenttab_internal_rows"><nobr><b>:&nbsp;</b></nobr></td>
                                            <td class="contenttab_internal_rows">
                                              <select name="sortOrder1" id="sortOrderBy1" size="1" class="selectfield" style="width:135px">  
                                               <option value='1'>Descending (Z to A)</option> 
                                               <option value='2'>Ascending  (A to Z)</option>
                                              </select> 
                                            </td>
                                        </tr>
                                        <tr>  
                                            <td class="contenttab_internal_rows" ><nobr><b>&nbsp;Filter By Exam.&nbsp;</b></nobr></td>
                                             <td class="contenttab_internal_rows" ><nobr><b>:&nbsp;</b></nobr></td>
                                            <td class="contenttab_internal_rows" ><nobr>
                                            <select name="entranceExam" id="entranceExam" size="1" class="selectfield" style="width:105px"> 
                                                <option value=''>Select</option>
                                                <option value='all'>All</option>
                                             <?php
                                                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                echo HtmlFunctions::getInstance()->getEntranceExamData();
                                              ?>
                                            </select>
                                            </nobr>
                                            </td>
                                            <td class="contenttab_internal_rows" ><nobr><b>&nbsp;Rank From</b></nobr></td>
                                            <td class="contenttab_internal_rows" ><nobr><b>:&nbsp;</b></nobr></td>
                                            <td class="contenttab_internal_rows" >
                                                <input type="text" id="rankFrom" name="rankFrom" class="inputbox" maxlength="40" style="width:90px" />
                                            </td>
                                            <td class="contenttab_internal_rows" ><nobr><b>&nbsp;Rank To</b></nobr></td>
                                            <td class="contenttab_internal_rows" ><nobr><b>:&nbsp;</b></nobr></td>
                                            <td class="contenttab_internal_rows" >
                                                <input type="text" id="rankTo" name="rankTo" class="inputbox" maxlength="40" style="width:90px" />
                                            </td>
                                         </tr>
                                         <tr>
                                        <td class="contenttab_internal_rows"  align="left"><nobr>
                                            <strong>&nbsp;Set Counseling Period From Date&nbsp;<?php echo REQUIRED_FIELD ?></strong></nobr>
                                        </td>
                                        <td class="contenttab_internal_rows" ><nobr><b>:&nbsp;</b></nobr></td>    
                                        <td class="contenttab_internal_rows" align="left" width="29%"><nobr>
                                            <?php 
                                            require_once(BL_PATH.'/HtmlFunctions.inc.php'); 
                                            echo HtmlFunctions::getInstance()->datePicker('startDate',date('Y-m-d'));
                                            ?></nobr>
                                        </td>
                                        <td class="contenttab_internal_rows" align="left"><nobr>
                                            <strong>&nbsp;Set Counseling Period To Date&nbsp;<?php echo REQUIRED_FIELD ?></strong></nobr>
                                        </td>
                                        <td class="contenttab_internal_rows" align="left" ><nobr><b>:&nbsp;</b></nobr></td>
                                        <td class="contenttab_internal_rows" align="left"><nobr>
                                            <?php 
                                            require_once(BL_PATH.'/HtmlFunctions.inc.php'); 
                                            echo HtmlFunctions::getInstance()->datePicker('endDate',date('Y-m-d'));
                                            ?></nobr>
                                        </td>
                                        <td class="padding" align="right" style="padding-left:50px" colspan="8">
                                          <nobr>                                                                                 
                                           <input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="validateAddForm(); return false;" />
                                          </nobr>  
                                        </td> 
                                   </tr>
                        </table>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr id='nameRow' style='display:none;'>
                                <td class="" height="20" colspan="2" width="100%">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="contenttab_border">
                                        <tr>
                                            <td colspan="1" class="content_title"  valign="middle">Student Enquiry List :</td>
                                            <td colspan="1" class="content_title" align="right" valign="middle">
                                               
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr id='resultRow' style='display:none;'>
                                <td class='contenttab_row'>
                                  <div id="ResultDiv" style="overflow:auto; HEIGHT:520px; vertical-align:top;">    
                                    <div id='results'>                               
                                    </div>
                                  </div>  
                                </td>
                            </tr>
                            <tr id='nameRow2' style='display:none;'>
                                <td class="" height="20">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="">
                                        <tr>
                                            <td colspan="2" class="content_title" align="right" valign="middle">
                                               <input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/send.gif" onClick="addCounseling(); return false;" />&nbsp;                                            
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

<!--Start Topic  Div-->
<?php floatingDiv_Start('divInformation','Error(s)/Warning(s)','',''); ?>
<form name="divForm" action="" method="post">  
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td height="5px"></td></tr>
    <tr>
    <tr>
      <td valign="top" align="center">
        <div class='report'>For following list(s) student created.</div> 
      </td>
    </tr>
    <tr>
        <td height="5px"></td></tr>
    <tr>
    <tr>    
        <td width="95%" align="center" valign="top">                          
          <div  style="overflow:auto; width:550px; height:350px; vertical-align:top;">
            <div id="userNotGenerateInfo" style="width:530px; height:350px; vertical-align:top;"></div>
          </div>  
        </td>
    </tr>
</table>
</form> 
<?php floatingDiv_End(); ?>

<?php 
//$History: listCounselingContents.php $
//
//*****************  Version 3  *****************
//User: Parveen      Date: 3/23/10    Time: 6:34p
//Updated in $/LeapCC/Templates/StudentEnquiry
//query & condition format updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 3/18/10    Time: 12:45p
//Updated in $/LeapCC/Templates/StudentEnquiry
//validation & condition updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 3/15/10    Time: 12:04p
//Created in $/LeapCC/Templates/StudentEnquiry
//initial checkin
//
//*****************  Version 4  *****************
//User: Parveen      Date: 9/21/09    Time: 1:15p
//Updated in $/LeapCC/Templates/CreateParentLogin
//Resolved the sorting, conditions, alignment issues updated
//
//*****************  Version 3  *****************
//User: Parveen      Date: 9/18/09    Time: 10:53a
//Updated in $/LeapCC/Templates/CreateParentLogin
//sorting & validations updated & CSV file created
//
//*****************  Version 2  *****************
//User: Parveen      Date: 7/28/09    Time: 4:55p
//Updated in $/LeapCC/Templates/CreateParentLogin
//internet browser base div format updated(login not created div
//updated).
//
//*****************  Version 1  *****************
//User: Parveen      Date: 7/28/09    Time: 4:11p
//Created in $/LeapCC/Templates/CreateParentLogin
//initial checkin
//
?>
