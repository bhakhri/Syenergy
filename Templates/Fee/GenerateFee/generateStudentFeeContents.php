<?php
//-------------------------------------------------------
// Purpose: to Show Contents Of Generate Student Fees
// Author : Nishu bindal
// Created on : 21-Mar-2012
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
?>
<form action="" method="post" name="allDetailsForm" id="allDetailsForm" onsubmit="return false;">
   
    
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td valign="top" class="title">
              <?php require_once(TEMPLATES_PATH . "/breadCrumb.php");?>    
            </td>
        </tr>
        <tr>
           <td valign="top">
              <table width="100%" border="0" cellspacing="0" cellpadding="5" height="405">
                <tr>
                  <td valign="top" class="content" align="center">
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
                    <td class="contenttab_border2" align="center">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20" class="contenttab_border" >
                        <tr>
                            <td class="content_title" align="left">Generate Student Fees :</td>
                        </tr>
                    </table>
                   
                    <div style="height:15px"></div>  
                    <table border="0" cellspacing="0" cellpadding="0px" width="20%" align="center">
                        <tr>
                            <td  class="contenttab_internal_rows" width="2%" style="padding-left:10px;"><nobr><b>Degree<?php echo REQUIRED_FIELD; ?></b></nobr></td>
                            <td  class="contenttab_internal_rows" width="2%"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
                              <td  class="contenttab_internal_rows" width="2%">  
                                <select name="degreeId" id="degreeId" style="width:230px" class="selectfield" onchange="getBranches()" >
                                    <option value="">Select</option>
                                     <?php
                                       require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                       echo HtmlFunctions::getInstance()->getAllDegree();
                                    ?>
                                </select>
                            </td>
                             <td  class="contenttab_internal_rows" width="2%" style="padding-left:10px;"><nobr><b>Branch<?php echo REQUIRED_FIELD; ?></b></nobr></td>
                            <td  class="contenttab_internal_rows" width="2%"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
                              <td  class="contenttab_internal_rows" width="2%">  
                                <select name="branchId" id="branchId"  class="selectfield" onchange="getBatch();">
                                    <option value="">Select</option>
                                   
                                </select>
                            </td>
                             <td  class="contenttab_internal_rows" width="2%" style="padding-left:10px;"><nobr><b>Batch<?php echo REQUIRED_FIELD; ?></b></nobr></td>
                            <td  class="contenttab_internal_rows" width="2%"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
                              <td  class="contenttab_internal_rows" width="2%">  
                                <select name="batchId" id="batchId"  class="selectfield" onchange="getClass();">
                                    <option value="">Select</option>
                                    
                                </select>
                            </td>
                            </tr>
                            <tr>
                               <td  class="contenttab_internal_rows" width="2%" style="padding-left:10px;padding-top:7px;"><nobr><b>Class<?php echo REQUIRED_FIELD; ?></b></nobr></td>
                            <td  class="contenttab_internal_rows" width="2%" style="padding-top:7px;"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
                            <td  class="contenttab_internal_rows" width="2%" style="padding-top:7px;">  
                                <select name="classId" id="classId" style="width:230px;" class="selectfield" onchange="resetResult();">
                                    <option value="">Select</option>
                                </select>
                            </td>
                            <td  class="contenttab_internal_rows" width="2%" style="padding-left:10px;padding-top:7px;" ><nobr><b>Fee Cycle<?php echo REQUIRED_FIELD; ?></b></nobr></td>
                            <td  class="contenttab_internal_rows" width="2%" padding-top:7px; ><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
                            <td  class="contenttab_internal_rows" width="2%" padding-top:7px; >  
                                <select name="feeCycleId" id="feeCycleId"  class="selectfield">
                                     <option value="">Select</option>
                                      <?php
                                        require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                        $condition = " AND status =1";
                                        echo HtmlFunctions::getInstance()->getFeeCycleDataNew('',$condition);
                                      ?>
                                    </select>
                            </td>
                            <td class="contenttab_internal_rows"  style="padding-left:15px" colspan=3>  
                             <input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateAddForm('allDetailsForm');  return false;" />  
                            <td>
                        </tr>
                        <tr>
                        </tr>
              </table>
    </td>
</tr> 
<tr id='cancelDiv' style='display:none;'>
<td height='15px' align='right' >
<input type="image" name="EditCancel" src="<?php echo IMG_HTTP_PATH;?>/close_big.gif"  
                    onclick="javascript:resetResult('all');return false;" />
</td>
</tr>
<tr>
<td>                  
	<div id="results">            
 	</div>
 </td></tr>
 <tr id='cancelDiv1' style='display:none;'>
<td height='15px' align='right' >
<input type="image" name="EditCancel" src="<?php echo IMG_HTTP_PATH;?>/close_big.gif"  
                    onclick="javascript:resetResult('all');return false;" />
</td>
</tr>
                   
</table>    
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
