<?php 
//it contain the template to show details of internal student re-appear 
//
// Author :Parveen Sharma
// Created on : 19-01-2009
// Copyright 2009-2010: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
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
                                     <table align="left" border="0" cellpadding="0" cellspacing="0" width="40%">  
                                        <tr>
                                           <td valign="top" class="contenttab_internal_rows" width="10%"><b>
                                             <nobr>Re-appear Application Form Submitted Date From<?php echo REQUIRED_FIELD ?></b></nobr>
                                           </td>
                                           <td class="contenttab_internal_rows" width="2%" valign="top"><b><nobr>&nbsp;:&nbsp;</b></nobr></td>
                                           <td class="contenttab_internal_rows"valign="top" width="5%"><nobr>
                                              <?php
                                                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                echo HtmlFunctions::getInstance()->datePicker('startDate',date('Y-m-d'));
                                              ?></nobr>
                                           </td>
                                           <td valign="top" class="contenttab_internal_rows" width="2%">
                                              <nobr><b>&nbsp;&nbsp;To<?php echo REQUIRED_FIELD ?></b></nobr>
                                           </td>
                                           <td class="contenttab_internal_rows" width="2%" valign="top"><b><nobr>&nbsp;:&nbsp;</b></nobr></td>
                                           <td valign="top" class="contenttab_internal_rows" width="4%"> 
                                              <nobr>
                                              <?php
                                                   require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                   echo HtmlFunctions::getInstance()->datePicker('endDate',date('Y-m-d'));
                                              ?>
                                               </nobr>
                                           </td>
                                           </tr>
                                           <tr>
                                           <td valign="top" class="contenttab_internal_rows"><nobr><b>
                                              Choose Class for Re-appear Form Submitted<?php echo REQUIRED_FIELD ?></b></nobr>
                                           </td>
                                           <td valign="top" class="contenttab_internal_rows" width="1%"><b><nobr>&nbsp;:&nbsp;</b></nobr></td> 
                                           <td valign="top" class="contenttab_internal_rows" width="12%"><nobr> 
                                            <select size="1" class="selectfield" name="reappearClassId" id="reappearClassId" onChange="hideResults(); return false;" style="width:220px">
                                            <option value="" selected="selected">Select</option>
                                                <?php
                                                    require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                    echo HtmlFunctions::getInstance()->getReappearClassData();
                                                ?>
                                            </select>
                                            </nobr>
                                           </td>
                                           <td valign="top" class="contenttab_internal_rows"><nobr><b>&nbsp;
                                            Roll No.</b></nobr></td>
                                            <td valign="top"  class="contenttab_internal_rows" width="1%"><b><nobr>&nbsp;:&nbsp;</b></nobr></td>
                                            <td valign="top"  class="contenttab_internal_rows" width="12%" ><nobr>
                                              <input type="text" name="rollNo" id="rollNo" class="inputbox" maxlength="50" style="width:190px" onkeydown="return sendKeys(event);"><br>
                                              <span style="font-size:11px">(Enter Roll No. to Find Specific Student)</span>
                                            </nobr>
                                            </td>
                                            <td valign="top"  class="contenttab_internal_rows" style="padding-left:20px"><nobr>  
                                                <input type="image" name="sSubmit1" id="sSubmit" value="sSubmit1" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateAddForm(); return false;" />
                                              </nobr>
                                            </td>
                                        </tr>
                                     </table>
                                </td>
                        </tr>
                        <tr>
                            <td height="5px"></td>
                        </tr>
                    </table>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr id='nameRow' style='display:none;'>
                                <td class="" height="20">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="contenttab_border">
                                        <tr>
                                            <td class="content_title">Display Student Internal Re-appear Details :</td>
                                            <td class="content_title" align="right">
                                          <!--  <nobr><input type="image" name="sUpdate" value="sUpdate" src="<?php echo IMG_HTTP_PATH;?>/update.gif" onClick="return approveValidation(); return false;" /></nobr> -->
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
                            <tr id='nameRow4' style='display:none;'>
                             <td colspan='1' valign="top" class="contenttab_row"> 
                                    <table border="0" cellspacing="0" cellpadding="0" align="left" class=""  width="50%" >
                                      <tr>
                                          <td colspan="14" class="contenttab_internal_rows">
                                             <b><nobr>Select action to be performed on above selected students :</nobr></b>
                                          </td>   
                                      </tr>
                                      <tr>
                                          <td colspan="14" valign="top" height="2px" ></td>
                                      </tr>     
                                      <tr>
                                          <td class="contenttab_internal_rows" align="left"><nobr><b>Re-appear Status</b></nobr></td>
                                          <td class="contenttab_internal_rows" align="left"><nobr><b>:&nbsp;</b></nobr></td>
                                          <td class="contenttab_internal_rows" align="left"><nobr>
                                          <?php
                                            global $reppearStatusArr;
                                             if(isset($reppearStatusArr) && is_array($reppearStatusArr)) {
                                                $count = count($reppearStatusArr);
                                                 foreach($reppearStatusArr as $key=>$value) {
                                                    echo "<input type='radio' value='".$key."' name='reappearStatus' id='reappearStatus' />&nbsp;$value"; 
                                                }
                                            }
                                          ?></nobr>
                                           </td>
                                           <td class="contenttab_internal_rows" align="left" style="padding-left:20px">
                                              <nobr><b>Detained Student</nobr></b>
                                           </td>
                                           <td class="contenttab_internal_rows" align="left"><nobr><b>:&nbsp;</b></nobr></td>
                                           <td class="contenttab_internal_rows" align="left"><nobr><b>
                                           <input type="checkbox" id="studentDetained" name="studentDetained" value="1"></nobr></b>
                                          </td>    
                                      </tr>
                                      <tr>
                                        <td height="5px"> </td>
                                      </tr>
                                     </table>
                              </td>
                            </tr> 
                            <tr id='nameRow2' style='display:none;'>
                                <td class="" height="20">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="">
                                        <tr>
                                            <td colspan="2" class="content_title" align="right">
                                            <nobr><input type="image" name="sUpdate" value="sUpdate" src="<?php echo IMG_HTTP_PATH;?>/update.gif" onClick="return approveValidation(); return false;" /></nobr>
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

<?php floatingDiv_Start('divInfo','Student Subjects Details','',''); ?>
<div id="showFullResulttDiv" style="overflow:auto; WIDTH:800px; height:410px; vertical-align:top;"> 
    <form action="" method="POST" name="showDetailsForm" id="showDetailsForm" onSubmit="return false;">  
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="">
            <tr>
                <td height="5px" ></td>
            </tr>
            <tr>
                <td width="89%" align="left">   
                    <table border="0" cellspacing="0" cellpadding="0" class="" align="left">
                        <tr>
                            <td class="contenttab_internal_rows" width="6%" align="left"><nobr><b>&nbsp;&nbsp;&nbsp;Student Name</b></nobr></td>
                            <td class="contenttab_internal_rows" width="2%" align="left"><nobr><b>:&nbsp;</b></nobr></td>
                            <td class="contenttab_internal_rows" width="30%" align="left"><nobr><span id='divStudentName'></span></nobr></td>
                            <td class="contenttab_internal_rows" width="6%" align="left"><nobr><b>Roll No.</b></nobr></td>
                            <td class="contenttab_internal_rows" width="2%" align="left"><nobr><b>:&nbsp;</b></nobr></td>
                            <td class="contenttab_internal_rows" width="15%" align="left"><nobr><span id='divRollNo'></span></nobr></td>
                            <td class="contenttab_internal_rows" width="6%" align="left"><nobr><b>Re-appear Class</b></nobr></td>
                            <td class="contenttab_internal_rows" width="2%" align="left"><nobr><b>:&nbsp;</b></nobr></td>
                            <td class="contenttab_internal_rows" width="31%" align="left"><nobr><span id='divRepClassName'></span></nobr></td>
                         </tr>   
                    </table>
                </td>
            </tr>
            <tr>
                <td height="5px" ></td>
            </tr>
            <tr>  
                <td width="89%" align="center">
                    <div id="scroll2" style="overflow:auto; width:100%; vertical-align:top;">
                         <div id="showResultDiv" style="width:98%; vertical-align:top;"></div>
                     </div>
                </td>
            </tr>
            <tr>
               <td width="89%" align="right" style="padding-right:8px">
                 <nobr><input type="image" name="sUpdate" value="sUpdate" src="<?php echo IMG_HTTP_PATH;?>/update.gif" onClick="return updateApproval(); return false;" /></nobr>
               </td>
            </tr>
        </table>
    </form> 
 </div>   
<?php floatingDiv_End(); ?> 
                           
<?php
//$History: listStudentInternalReappearContents.php $
//
//*****************  Version 6  *****************
//User: Parveen      Date: 2/04/10    Time: 11:32a
//Updated in $/LeapCC/Templates/AdminTasks
//look & feel updated
//
//*****************  Version 5  *****************
//User: Parveen      Date: 2/01/10    Time: 5:12p
//Updated in $/LeapCC/Templates/AdminTasks
//sendKey function added 
//
//*****************  Version 4  *****************
//User: Parveen      Date: 1/19/10    Time: 6:27p
//Updated in $/LeapCC/Templates/AdminTasks
//function & validation message and format updated
//
//*****************  Version 3  *****************
//User: Parveen      Date: 1/15/10    Time: 5:35p
//Updated in $/LeapCC/Templates/AdminTasks
//format and validation updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 1/15/10    Time: 2:11p
//Updated in $/LeapCC/Templates/AdminTasks
//format update
//
//*****************  Version 1  *****************
//User: Parveen      Date: 1/14/10    Time: 5:16p
//Created in $/LeapCC/Templates/AdminTasks
//initial checkin

?>