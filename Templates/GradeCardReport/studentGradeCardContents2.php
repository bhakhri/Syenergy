<?php 
//-------------------------------------------------------
//  This File contains Student grade card template
//
//
// Author :Parveen Sharma
// Created on : 06-03-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(BL_PATH.'/HtmlFunctions.inc.php');
$htmlFunctions = HtmlFunctions::getInstance();
?>
<form action="" method="POST" name="allDetailsForm" id="allDetailsForm" onSubmit="return false;">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top" class="title">
       <table border="0" cellspacing="0" cellpadding="0" width="100%">
          <tr>
             <td height="10"></td>
          </tr>
       </table> 
    <?php require_once(TEMPLATES_PATH . "/breadCrumb.php");?> 
    </td>
  </tr>
   <tr>
     <td valign="top">
        <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
           <tr>
               <td valign="top" class="content" align="center">
               <!-- form table starts -->
                  <table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2" >
                      <tr>
                         <td valign="top" class="contenttab_row1">
                             <table align="center" border="0" width="40%" cellspacing="0px" cellpadding="0px"   >
                                 <tr>
                                     <td align="left" valign="top" >
                                          <table border="0" cellspacing="0px" cellpadding="5px"  >
                                              <tr>
                                                  <td align="left" class="contenttab_internal_rows" >    
                                                   <nobr><strong>Batch<?php echo REQUIRED_FIELD ?></strong></nobr>
                                                  </td>   
                                                  <td align="left" class="contenttab_internal_rows"><nobr><strong>&nbsp;:&nbsp;</strong></nobr>
                                                  </td>
                                                  <td align="left" class="contenttab_internal_rows"><nobr>    
                                                  <select size="1" class="inputbox" name="batchId" id="batchId" style="width:250px" onChange="getDegreeData(); hideDetails1(); return false;">
                                                  <option value="">Select</option>
                                                  <?php 
                                                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                  echo HtmlFunctions::getInstance()->getBatches();
                                                  ?>
                                                  </select></nobr>
                                                  </td>
                                                </tr>
                                                <tr>
                                                  <td  align="left" class="contenttab_internal_rows" >    
                                                  <nobr><strong>Degree<?php echo REQUIRED_FIELD ?></strong></nobr>
                                                  </td>   
                                                  <td align="left" class="contenttab_internal_rows"><nobr><strong>&nbsp;:&nbsp;</strong></nobr>
                                                  </td>
                                                  <td align="left" class="contenttab_internal_rows"><nobr>    
                                                  <select size="1" class="inputbox" name="degreeId" id="degreeId" style="width:250px" onChange="getBranchData(); hideDetails();">
                                                  <option value="">Select</option>
                                                  </select>
                                                  </nobr>
                                                  </td> 
                                                </tr>  
                                                <tr>
                                                  <td  align="left" class="contenttab_internal_rows" >    
                                                  <nobr><strong>Branch<?php echo REQUIRED_FIELD ?></strong></nobr>
                                                  </td>   
                                                  <td align="left" class="contenttab_internal_rows"><nobr><strong>&nbsp;:&nbsp;</strong></nobr>
                                                  </td>
                                                  <td align="left" class="contenttab_internal_rows"><nobr>    
                                                  <select size="1" class="inputbox" name="branchId" id="branchId" style="width:250px" onChange="getTrimesterData(); hideDetails();">
                                                  <option value="">Select</option>
                                                  </select>
                                                  </nobr>
                                                  </td>
                                                </tr>  
                                                <tr>
                                                   <td align="left" class="contenttab_internal_rows" >    
                                                   <nobr><strong>Roll No.</strong></nobr>
                                                   </td>   
                                                   <td align="left" class="contenttab_internal_rows"><nobr><strong>&nbsp;:&nbsp;</strong></nobr>
                                                   </td>
                                                   <td align="left" class="contenttab_internal_rows"><nobr>    
                                                   <input type="text" name="rollno" id="rollno" class="inputbox" maxlength="50" style="width:245px" />
                                                   </nobr>
                                                   </td>
                                                 </tr>
                                                </table>  
                                             </td>
                                             <td align="left" class="contenttab_internal_rows" valign='top' style='padding-left:10px'> 
                                             <nobr>                                                         
                                             &nbsp;<strong>Semester<?php echo REQUIRED_FIELD ?> :<br>&nbsp;<select multiple name='semesterId[]' id='semesterId' size='5' class="inputbox" style='width:120px'>
                                             <?php 
                                             //  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                             // echo HtmlFunctions::getInstance()->getStudyPeriodData();
                                             ?>
                                             </select><br>
                                             <div style="text-align:left;">&nbsp;Select 
                                             <a class="allReportLink" href="javascript:makeSelection('semesterId[]','All','allDetailsForm');">All</a> / 
                                             <a class="allReportLink" href="javascript:makeSelection('semesterId[]','None','allDetailsForm');">None</a>
                                             </div>
                                             </nobr>
                                             </td>
											 <td align="center" valign="top" style="padding-left:20px">
                                               <table border="0" cellspacing="0px" cellpadding="0px"  >  
                                                   <tr>
                                                      <td align="left" class="contenttab_internal_rows" colspan="6">    
                                                      <nobr><strong>Authorized Signatory</strong></nobr>
                                                      </td> 
                                                      </tr>
                                                   <tr>   
                                                     <td align="left" class="contenttab_internal_rows" >    
                                                     <nobr>Name</nobr>
                                                     </td> 
                                                     <td align="left" class="contenttab_internal_rows"><nobr><strong>&nbsp;:&nbsp;</strong></nobr>
                                                     </td>
                                                     <td align="left" class="contenttab_internal_rows"><nobr>    
                                                     <input type="text" name="authName" id="authName" value="" class="inputbox" maxlength="20" style="width:245px" />
                                                     </nobr>
                                                     </td>
                                                   </tr>
                                                   <tr>
                                                      <td align="left" class="contenttab_internal_rows" >    
                                                        <nobr>Designation</nobr>
                                                      </td>   
                                                      <td align="left" class="contenttab_internal_rows"><nobr><strong>&nbsp;:&nbsp;</strong></nobr>
                                                      </td>
                                                      <td align="left" class="contenttab_internal_rows"><nobr>    
                                                      <input type="text" name="authDesignation" id="authDesignation" value="Registrar" class="inputbox" maxlength="20" style="width:245px" />
                                                      </nobr>
                                                      </td>
                                                   </tr>
                                                   <tr>
                                                     <td align="left" class="contenttab_internal_rows" >    
                                                     <nobr>Place of Printing</nobr>
                                                     </td>   
                                                     <td align="left" class="contenttab_internal_rows"><nobr><strong>&nbsp;:&nbsp;</strong></nobr>
                                                     </td>
                                                     <td align="left" class="contenttab_internal_rows"><nobr>    
                                                     <input type="text" name="placeCity" id="placeCity" value="" class="inputbox" maxlength="20" style="width:245px" />
                                                     </nobr>
                                                     </td>
                                                   </tr>
                                                   <tr>
                                                     <td align="left" class="contenttab_internal_rows" valign="top"><nobr>Date</nobr>\
                                                     </td>
                                                     <td align="left" class="contenttab_internal_rows" valign="top">
                                                     <nobr><strong>&nbsp;:&nbsp;</strong></nobr>
                                                     </td>
                                                     <td class="contenttab_internal_rows" nowrap valign="top"><nobr>
                                                        <table border="0" cellspacing="0px" cellpadding="0px"  > 
                                                            <tr>
                                                               <td class="contenttab_internal_rows" nowrap><nobr>
                                                                <?php 
                                                                   require_once(BL_PATH.'/HtmlFunctions.inc.php');        
                                                                     echo HtmlFunctions::getInstance()->datePicker('gradePrintDate',date('Y-m-d'));
                                                                    ?></nobr>
                                                                 </td>
                                                                 <td style="padding-left:20px">
                                                                 <nobr>&nbsp;<input id="showHeader" name="showHeader" type="checkbox"></nobr>   
                                                                  </td>    
                                                                  <td class="contenttab_internal_rows" align="left">
                                                                    <nobr>Show Header</nobr> 
                                                                  </td>
                                                              </tr>
                                                              <tr>
                                                                   <td class="padding" colspan="5" height="15px" nowrap align="center"> 
                                                                   <input type="image" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateAddForm(this.form);return false;" /                                                          </td>
                                                              </tr>
                                                         </table>
                                                        </td>  
                                                      </tr>
                                               </tr>
                                                <tr style="display:none">
                                                   <td align="left" valign="top" class="contenttab_internal_rows" colspan="3"><nobr>
                                                      <table border="0" cellspacing="0px" cellpadding="0px"  >  
                                                         <tr> 
                                                            <td class="contenttab_internal_rows"><nobr>
                                                               <input type="checkbox" checked="checked" id="branchChk" name="branchChk" value="1"><nobr>&nbsp;Include Branch Name</b>&nbsp;&nbsp; 
                                                               </nobr>
                                                            </td>
                                                            <td class="contenttab_internal_rows"><nobr>
                                                               <input type="checkbox" id="headerChk" name="headerChk" value="1"><nobr>&nbsp;Print Header</b>&nbsp;&nbsp; 
                                                               </nobr>
                                                            </td>
                                                             <td class="contenttab_internal_rows" colspan='2'><nobr>
                                                              <input type="checkbox" id="studentDetailChk" name="studentDetailChk" value="1"><nobr>&nbsp;Include Student Details&nbsp;&nbsp; 
                                                              </nobr>
                                                           </td>
                                                       </tr>
                                                       <tr> 
                                                          <td class="contenttab_internal_rows"><nobr>
                                                          <input type="checkbox" id="prvChk" checked="checked" name="prvChk" value="1"><nobr>&nbsp;Include Previous Credit Detail&nbsp;&nbsp;
                                                           </nobr>
                                                            </td>
                                                            <td class="contenttab_internal_rows"><nobr>
                                                            <input type="checkbox" id="lessChk" name="lessChk" value="1"><nobr>&nbsp;Include Less Credit Detail&nbsp;&nbsp; 
                                                            </nobr>
                                                             </td>
                                                             <td class="contenttab_internal_rows"><nobr>
                                                             <input type="checkbox" id="netChk" name="netChk" value="1"><nobr>&nbsp;Include Net Credit Detail&nbsp;&nbsp; 
                                                              </nobr>
                                                               </td>
                                                     </tr>                                                           
                                                     <tr> 
                                                       <td class="contenttab_internal_rows"><nobr>
                                                       <input type="checkbox" id="gpaChk" checked="checked" name="gpaChk" value="1"><nobr>&nbsp;Include GPA&nbsp;&nbsp;
                                                       </nobr>
                                                       </td>
                                                       <td class="contenttab_internal_rows"><nobr>
                                                       <input type="checkbox" id="cgpaChk" name="cgpaChk" value="1"><nobr>&nbsp;Include CGPA&nbsp;&nbsp; 
                                                        </nobr>
                                                        </td>
                                                        <td class="contenttab_internal_rows"><nobr>
                                                          <input type="checkbox" id="titleChk" name="titleChk" value="1"><nobr>&nbsp;Include Report Title&nbsp;&nbsp; 
                                                          </nobr>
                                                           </td>
                                                     </tr>
                                                      <tr>
                                                         <td class="contenttab_internal_rows" colspan="6"><nobr>   
                                                             <table border="0" cellspacing="0" cellpadding="0">
                                                                  <tr>
                                                                      <td class="contenttab_internal_rows" nowrap><nobr>
                                                                      <label><nobr>&nbsp;Alignment of Authorized Signatory&nbsp;&nbsp; </label>   </nobr>
                                                                      </td>
                                                                     <td class="contenttab_internal_rows" nowrap>
                                                                     <input type="radio"  name="authAlign" value="left" checked>
                                                                     </td>   
                                                                     <td class="contenttab_internal_rows" nowrap>Left
                                                                     </td> 
                                                                     <td class="contenttab_internal_rows" nowrap>   
                                                                     <input type="radio"  name="authAlign" value="right">
                                                                     </td>
                                                                     <td class="contenttab_internal_rows" nowrap>Right
                                                                     </td> 
                                                                     <td class="contenttab_internal_rows" style="padding-left:10px"><nobr> 
                                                                     Date</nobr>
                                                                     </td>
                                                                     <td class="contenttab_internal_rows"><nobr>
                                                                    <?php 
                                                                       require_once(BL_PATH.'/HtmlFunctions.inc.php');        
                                                                       echo HtmlFunctions::getInstance()->datePicker('fromDate',date('Y-m-d'));
                                                                    ?></nobr>
                                                                     </td>
                                                                     <td style="padding-left:20px" >
                                                                     <input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateAddForm(this.form);return false;" />
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
           </td>
        </tr>
        <tr>
		   <td class="contenttab_internal_rows" align="left" valign="top">
			<fieldset>
			<b><u>Please Note:</u><br></b>
			<font color="red">1. The Top header which is 4cm has been set only left and right alignment has to be made while printing.</font><br/>
			</fieldset>
			</td>
		</tr>
         <tr>
         <td>
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr id='nameRow' style='display:none;'>
                                <td class="" height="20">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="contenttab_border">
                                        <tr>
                                            <td colspan="1" class="content_title" style="text-align:left">Student Grade Card Report :
                                            </td>
                                            <td colspan="1" class="content_title" style="text-align:right">
                                                <input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr id='resultRow' style='display:none;'>
                                <td colspan='1' class='contenttab_row'>
                                    <div id = 'resultsDiv'>
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
</form>        
