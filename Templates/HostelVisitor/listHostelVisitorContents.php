<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR HOSTEL VISITOR LISTING 
//
//
// Author :Gurkeerat Sidhu 
// Created on : (18.04.2009)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(TEMPLATES_PATH . "/breadCrumb.php");  
?>
    
    <tr>
        <td valign="top" colspan=2>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
             <td valign="top" class="content">
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
             <tr>
                <td class="contenttab_border" height="20">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                        <td class="contenttab_border" height="20" style="border-right:0px;">
                                    <?php require_once(TEMPLATES_PATH . "/searchForm.php"); ?>
                                </td>
                        <td class="content_title" title="Add"  class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;padding-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" 
                        align="right" onClick="displayWindow('AddHostelVisitor',330,250);blankValues();return false;" />&nbsp;</td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" >
                <div id="results"> 
                 <table width="100%" border="0" cellspacing="0" cellpadding="0"  id="anyid">
                 <tr class="rowheading">
                    <td width="3%" class="searchhead_text">&nbsp;&nbsp;<b>#</b></td>
                    <td width="250" class="searchhead_text"><b>Visitor Name</b><img src="<?php echo IMG_HTTP_PATH;?>/arrow-up.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=DESC&sortField='+sortField)" /></td>
                    <td width="150" class="searchhead_text"><b>To Visit</b><img src="<?php echo IMG_HTTP_PATH;?>/arrow-none.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=ASC&sortField=stopAbbr')" /></td>
                    <td width="200" class="searchhead_text"><b>Address</b></td>
                    <td width="150" class="searchhead_text"><b>Date Of Visit</b></td>
                    <td width="150" class="searchhead_text"><b>Time</b></td>
                    <td width="150" class="searchhead_text"><b>Purpose</b></td>
                    <td width="150" class="searchhead_text"><b>Contact No</b></td>
                    <td width="150" class="searchhead_text"><b>Relation</b></td>
                    <td width="10%" class="searchhead_text" align="right"><b>Action</b></td>
                 </tr>
                              
                 </table></div>           
             </td>
          </tr>
          <tr><td height="10px"></td></tr>
          <tr>
			<td class="content_title" align="right" ><input type="image" name="print" src="<?php  echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport();" title="Print" />&nbsp;<input type="image" name="printGroupSubmit" id='generateCSV' onClick="printCSV();return false;" value="printGroupSubmit" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" title="Export to Excel"/></td>
		  </tr>
          </table>
        </td>
    </tr>
    
    </table>
    </td>
    </tr>
    </table>
    <!--Start Add Div-->
    
<?php floatingDiv_Start('AddHostelVisitor','Add Hostel Visitor'); ?>
<form name="AddHostelVisitor" action="" method="post">  
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td  class="contenttab_internal_rows"><nobr>&nbsp;<b>Visitor Name<?php echo REQUIRED_FIELD;?></b></nobr></td>
        <td  class="padding"><nobr>:&nbsp;</td>
        <td><input type="text" id="visitorName" name="visitorName" class="inputbox" maxlength="30" onkeydown="return sendKeys(1,'visitorName',event);" /></nobr></td>
    </tr>
    <tr> 
      <td class="contenttab_internal_rows" valign="top"><nobr>&nbsp;<strong>Address<?php echo REQUIRED_FIELD;?></strong></nobr></td>
      <td class="padding" style="vertical-align:top;">:</td>
      <td><textarea name="address" id="address" cols="25" rows="4" class="inputbox" style="vertical-align:top;"></textarea>
     </td>
    </tr>
    <tr>
        
        <td valign="center" align="right" class="contenttab_internal_rows"><b>&nbsp;Date of Visit</b></td>
        
        <td valign="center" align="left" ><b>&nbsp;:&nbsp;</b></td>
        <td><?php
          require_once(BL_PATH.'/HtmlFunctions.inc.php');
           echo HtmlFunctions::getInstance()->datePicker('visitDate1',date('Y-m-d')); 
           
        ?>
        </td>
    </tr>
    <tr>    
        <td  class="contenttab_internal_rows"><nobr>&nbsp;<b>Time<?php echo REQUIRED_FIELD;?></b></nobr></td>
        <td  class="padding"><nobr>:&nbsp;</td>
        <td><input type="text" id="timeOfVisit" name="timeOfVisit" class="inputbox" maxlength="5" style="width:100px;" onkeydown="return sendKeys(1,'timeOfVisit',event);"  />(HH:MM)</nobr></td>
    </tr>
    <tr>    
        <td  class="contenttab_internal_rows"><nobr>&nbsp;<b>To Visit<?php echo REQUIRED_FIELD;?></b></nobr></td>
        <td  class="padding"><nobr>:&nbsp;</td>
        <td><input type="text" id="toVisit" name="toVisit" class="inputbox" maxlength="30" onkeydown="return sendKeys(1,'toVisit',event);"/></nobr></td>
    </tr>
    <tr> 
      <td class="contenttab_internal_rows" valign="top"><nobr>&nbsp;<strong>Purpose<?php echo REQUIRED_FIELD;?></strong></nobr></td>
      <td class="padding" style="vertical-align:top;">:</td>
      <td><textarea name="purpose" id="purpose" cols="25" rows="4" class="inputbox" style="vertical-align:top;"></textarea>
     </td>
    </tr>
     <tr>    
        <td  class="contenttab_internal_rows"><nobr>&nbsp;<b>Contact No.<?php echo REQUIRED_FIELD;?></b></nobr></td>
        <td  class="padding"><nobr>:&nbsp;</td>
        <td><input type="text" id="contactNo" name="contactNo" class="inputbox" maxlength="20" onkeydown="return sendKeys(1,'contactNo',event);"/></nobr></td>
    </tr>
    <tr>
        <td  class="contenttab_internal_rows"><nobr>&nbsp;<b>Relation<?php echo REQUIRED_FIELD;?></b></nobr></td>
        <td  class="padding"><nobr>:&nbsp;</td>
        <td><select size="1" class="selectfield" name="relation" id="relation" >
        <option value="" selected="selected">SELECT</option>
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getHostelVisitorRel();
              ?>
        </select></nobr>
    </td>
  </tr>
   <tr>
    <td height="5px" colspan="3"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="3">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
      <input type="image" name="addCancell"  src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddHostelVisitor');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
        </td>
</tr>
<tr>
    <td height="5px" colspan="3"></td></tr>
<tr>
</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->

<!--Start Edit Div-->
<?php floatingDiv_Start('EditHostelVisitor','Edit Hostel Visitor'); ?>
<form name="EditHostelVisitor" action="" method="post">  
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <input type="hidden" name="visitorId" id="visitorId" value="" />  
   <tr>
        <td  class="contenttab_internal_rows"><nobr>&nbsp;<b>Visitor Name<?php echo REQUIRED_FIELD;?></b></nobr></td>
        <td  class="padding"><nobr>:&nbsp;</td>
        <td><input type="text" id="visitorName" name="visitorName" class="inputbox" maxlength="30" onkeydown="return sendKeys(2,'visitorName',event);"/></nobr></td>
    </tr>
    <tr> 
      <td class="contenttab_internal_rows" valign="top"><nobr>&nbsp;<strong>Address<?php echo REQUIRED_FIELD;?></strong></nobr></td>
      <td class="padding" style="vertical-align:top;">:</td>
      <td><textarea name="address" id="address" cols="25" rows="4" class="inputbox" style="vertical-align:top;"></textarea>
     </td>
    </tr>
    <tr>
        
        <td valign="center" align="right" class="contenttab_internal_rows"><b>&nbsp;Date of Visit</b></td>
        
        <td valign="center" align="left" ><b>&nbsp;:&nbsp;</b></td>
        <td><?php
          require_once(BL_PATH.'/HtmlFunctions.inc.php');
           echo HtmlFunctions::getInstance()->datePicker('visitDate2',date('Y-m-d')); 
           
        ?>
        </td>
    </tr>
    <tr>    
        <td  class="contenttab_internal_rows"><nobr>&nbsp;<b>Time<?php echo REQUIRED_FIELD;?></b></nobr></td>
        <td  class="padding"><nobr>:&nbsp;</td>
        <td><input type="text" id="timeOfVisit" name="timeOfVisit" class="inputbox" maxlength="5" style="width:100px;"  onkeydown="return sendKeys(2,'timeOfVisit',event);"/>(HH:MM)</nobr></td>
    </tr>
    <tr>    
        <td  class="contenttab_internal_rows"><nobr>&nbsp;<b>To Visit<?php echo REQUIRED_FIELD;?></b></nobr></td>
        <td  class="padding"><nobr>:&nbsp;</td>
        <td><input type="text" id="toVisit" name="toVisit" class="inputbox" maxlength="30" onkeydown="return sendKeys(2,'toVisit',event);"/></nobr></td>
    </tr>
    <tr> 
      <td class="contenttab_internal_rows" valign="top"><nobr>&nbsp;<strong>Purpose<?php echo REQUIRED_FIELD;?></strong></nobr></td>
      <td class="padding" style="vertical-align:top;">:</td>
      <td><textarea name="purpose" id="purpose" cols="25" rows="4" class="inputbox" style="vertical-align:top;"></textarea>
     </td>
    </tr>
     <tr>    
        <td  class="contenttab_internal_rows"><nobr>&nbsp;<b>Contact No.<?php echo REQUIRED_FIELD;?></b></nobr></td>
        <td  class="padding"><nobr>:&nbsp;</td>
        <td><input type="text" id="contactNo" name="contactNo" class="inputbox" maxlength="20" onkeydown="return sendKeys(2,'contactNo',event);"/></nobr></td>
    </tr>
    <tr>
        <td  class="contenttab_internal_rows"><nobr>&nbsp;<b>Relation<?php echo REQUIRED_FIELD;?></b></nobr></td>
        <td  class="padding"><nobr>:&nbsp;</td>
        <td><select size="1" class="selectfield" name="relation" id="relation">
        <option value="" selected="selected">SELECT</option>
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getHostelVisitorRel();
              ?>
        </select></nobr>
    </td>
  </tr>

<tr>
    <td height="5px" colspan="3"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="3">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
      <input type="image" name="editCancell" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('EditHostelVisitor');return false;" />
        </td>
</tr>
<tr>
    <td height="5px" colspan="3"></td></tr>
<tr>
</table>
</form>
    <?php floatingDiv_End(); ?>
    <!--End: Div To Edit The Table-->


