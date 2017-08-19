 <?php 
//-------------------------------------------------------
// THIS FILE Is Used As A Template For Registration Form
// Author : Ankur Aggarwal
// Created on : 25-July-2011
// Copyright 2011-2012: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
                <tr>
                    <td height="10"></td>
                </tr>
                <tr>
                    <td valign="top" class="title"> 
                       <?php require_once(TEMPLATES_PATH . "/breadCrumb.php"); ?>   
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td valign="top" class="content">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr height="30">
                    <td class="contenttab_border" height="20" style="border-right:0px;">
                       <span class="contenttab_row1"><b>Registration Form</b></span>
                     </td>
                     <td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;">
                         <img alt="Add Registration Form" title="Add Registration Form" src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="editWindow('','Add'); return false;" />&nbsp;
                     </td>
                </tr>
            </table>
         </td>
    </tr>            
    <tr id="divShowSearch" style="display:none">
       <td valign="top" class="contenttab_row1">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2">
               <tr>
                    <td class="padding" align="left" nowrap><strong>From Date</strong> </td>
                    <td class="padding" align="left" nowrap><strong>:</strong></td>
                    <td class="padding" align="left" nowrap>
                        <?php 
                        require_once(BL_PATH.'/HtmlFunctions.inc.php');        
                        echo HtmlFunctions::getInstance()->datePicker('fromDate',date('Y-m-d'));
                        ?>&nbsp;&nbsp;&nbsp;&nbsp;
                    </td>    
                    <td align="left" class="padding" nowrap>
                      <input type="image" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateAddForm(this.form);return false;" />
                    </td>
               </tr>  
            </table>
        </td>
     </tr>   
     <tr id="divShowList">
        <td valign="top" class="contenttab_row1">
            <table valign="top"  width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2" height="405"> 
               <tr>
                 <td>
                    <div id="scroll2" style="overflow:auto; height:410px; vertical-align:top;">
                      <div id="resultsDiv" style="width:98%; vertical-align:top;"></div>
                    </div>
                  </td>
               </tr>
            </table>     
        </td>          
    </tr>
    <tr id='nameRow2'>
        <td height="20" style="display:none">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="">
            <tr>
                <td colspan="2" class="content_title" align="right">
                  <input type="image" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp; 
                  <input type="image" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" onClick="printReportCSV()" />&nbsp;
                </td>
             </tr> 
             </table>
          </td>
    </tr>  
    
     <tr style='display:none' id="divShowAddEdit">
        <td valign="top" >
            <form method="POST" name="addForm" id="addForm" onSubmit="return false;">
            <input type="hidden" readonly name="studentId" id="studentId" value="">    
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
                    <tr>
                        <td valign="top" class="content">
                            <!-- form table starts -->
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2">
                                <tr> 
                                    <td class="contenttab_internal_rows"><nobr><b> 
                                 <?php require_once(TEMPLATES_PATH . "/PreAdmission/preAdmission.php"); ?>    
                                   </td>    
                                </tr>   
                             </table>
                        </td>                                
                    </tr>    
                    <tr>
                       <td class="contenttab_internal_rows"><nobr><b>
                          <input type="checkbox" name="undertaking" id="undertaking" value="yes" >
                           All the details given by me in this Application Form, declaration/undertaking are true and correct to the best of my knowledge.
                          </b></nobr>
                       </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="content_title" align="right">
                          <input type="image" src="<?php echo IMG_HTTP_PATH;?>/submit.gif" onclick="return validateLoginForm(this.form);return false;" />&nbsp;
                          <input type="image" src="<?php echo IMG_HTTP_PATH;?>/reset.gif" onclick="refereshData();" />
                       </td>
                    </tr>
                    <tr><td height="50px"></td> </tr>   
                </table>
                </form>     
        </td>
     </tr>  
</table>          

