<?php
//-------------------------------------------------------
// Purpose: to create time table coursewise.
// Author : PArveen Sharma
// Created on : 27.01.09
// Copyright 2009-2010: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
?>
<form action="" method="post" name="allDetailsForm" id="allDetailsForm" onsubmit="return false;">
    <select name="category" id="category" style="width:200px;display:none" class="selectfield"  >
        <option value="">Select</option>
         <?php
             require_once(BL_PATH.'/HtmlFunctions.inc.php');
             echo HtmlFunctions::getInstance()->getConcessionCategory();
         ?>
    </select>
    <select name="feeHead" id="feeHead" style="width:200px;display:none" class="selectfield"  >
        <option value="">Select</option>
         <?php
             require_once(BL_PATH.'/HtmlFunctions.inc.php');
             echo HtmlFunctions::getInstance()->getFeeHeadData('',' AND isVariable=0 AND isConsessionable=1');
         ?>
    </select>    
    <select name="leet" id="leet" style="width:200px;display:none" class="selectfield"  >
        <option value="">Select</option>    
        <option value="1">Leet</option>
        <option value="2">Non Leet</option>
        <option value="3">Leet & Non Leet</option>
    </select>  
    
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
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
                    <td class="" align="center" >
                      <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20" >
                              <tr>
                                <td class="contenttab_border" height="20">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                                    <tr>
                                        <td class="content_title" width="2%" align="left"><nobr>Copy Define Concession Value :</nobr></td>
                                        <td  class="content_title" width="98%" style="padding-left:10px" align="left"><nobr>
                                             <b><a href="javascript:void(0);" style="cursor:pointer;" onClick="getShowDetail();" class="link"><label id='lblMsg'>Show Copy Seats</label></a></b>
                                             <img id="showInfo" style="cursor:pointer;" src="<?php echo IMG_HTTP_PATH;?>/arrow-down.gif" onClick="getShowDetail(); return false;" /></b>
                                            </<nobr>
                                        </td>  
                                    </tr>                                               
                                    </table>
                                </td>
                             </tr>
                      </table>
                      <div id='showhideSeats'>
                        <div style="height:15px"></div>  
                        <table border="0" cellspacing="0" cellpadding="0px" width="100%" align="center">
                         <tr>
                           <td>
                             <table border="0" width="40%" cellspacing="0" cellpadding="0px" align="center"> 
                                <tr><td valign="top" colspan="10" height="2px"></td></tr>
                                <tr style="display:none;">
                                    <td class="" width="2%" valign="top" style="padding-left:5px" colspan="10" >
                                        <span style="font-weight: bold; font-size: 11px; FONT-FAMILY: Arial, Helvetica, sans-serif; color:#cc0000;">
                                            Note&nbsp;:&nbsp;Fee Head Values can only be copied for classes in same Fee Cycle only.
                                        </span>
                                    </td>
                                </tr>   
                                <tr>
                                    <td valign="top"> 
                                        <table cellspacing="5px" cellpadding="5px"> 
                                            <tr style='display:none'>
                                                <td  class="contenttab_internal_rows" width="2%"><nobr><b>Fee Cycle<?php echo REQUIRED_FIELD; ?></b></nobr></td>
                                                <td  class="contenttab_internal_rows" width="2%"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
                                                <td  class="contenttab_internal_rows" width="2%">  
                                                    <select name="copyFeeCycleId" id="copyFeeCycleId" style="width:280px" class="selectfield" onchange="getCopyFeeCylceClasses(); return false;" >
                                                        <option value="">Select</option>
                                                        <?php
                                                           require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                           echo HtmlFunctions::getInstance()->getFeeCycleListData();
                                                        ?>
                                                    </select>
                                                </td>
                                             </tr>
                                             </tr>   
                                                <td  class="contenttab_internal_rows" width="2%"><nobr><b>Class<?php echo REQUIRED_FIELD; ?></b></nobr></td>
                                                <td  class="contenttab_internal_rows" width="2%"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
                                                <td  class="contenttab_internal_rows" width="2%">  
                                                    <select name="souClassId" id="souClassId" style="width:280px" class="selectfield" onchange="getCopyClass(); return false;">
                                                        <option value="">Select</option>
                                                        <?php
                                                           require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                           echo HtmlFunctions::getInstance()->getAllFeeClass();
                                                        ?>
                                                    </select>
                                                </td>
                                            </tr>
                                        </table>
                                    </td> 
                                    <td  class="contenttab_internal_rows" width="2%" valign="top" style="padding-left:20px"><nobr><b>To<?php echo REQUIRED_FIELD; ?></b></nobr></td>
                                    <td  class="contenttab_internal_rows" width="2%" valign="top" ><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
                                    <td  class="contenttab_internal_rows" width="2%"><nobr>  
                                        <select multiple name='copyClassId[]' id='copyClassId' size='5' class='inputbox1' style='width:280px' >
                                        </select><br>
                                        <div align="left">
                                       <b>Select</b> &nbsp;
                                        <a class="allReportLink" href="javascript:makeSelection('copyClassId[]','All','allDetailsForm');">All</a> / 
                                        <a class="allReportLink" href="javascript:makeSelection('copyClassId[]','None','allDetailsForm');">None</a>
                                        </div></nobr>
                                    </td>
                                    <td class="contenttab_internal_rows" valign="top" style="padding-left:15px">  
                                        <input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/copy_concession.gif" onClick="return addFeeHeadValuesCopy();  return false;" />  
                                    <td>
                               </tr> 
                             </table>
                           </td>
                         </tr>
                         <tr><td valign="top" colspan="10" height="20px"><div class="searchhead_text"></div></td></tr>
                       </table>     
                    </div>
                    
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20" class="contenttab_border" >
                        <tr>
                            <td class="content_title" align="left">Define Concession Value :</td>
                        </tr>
                    </table>
                   
                    <div style="height:15px"></div>  
                    <table border="0" cellspacing="0" cellpadding="0px" width="20%" align="center">
                        <tr>  
                        <!--  
                            <td  class="contenttab_internal_rows" width="2%"><nobr><b>Fee Cycle<?php echo REQUIRED_FIELD; ?></b></nobr></td>
                            <td  class="contenttab_internal_rows" width="2%"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
                            <td  class="contenttab_internal_rows" width="2%">  
                                <select name="feeCycleId" id="feeCycleId" style="width:280px" class="selectfield" onchange="getFeeCylceClasses(); return false;" >
                                    <option value="">Select</option>
                                    <?php
                                       require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                       echo HtmlFunctions::getInstance()->getAllFeeClass();
                                    ?>
                                </select>
                            </td>
                        -->    
                            <td  class="contenttab_internal_rows" width="2%" style="padding-left:10px;"><nobr><b>Class<?php echo REQUIRED_FIELD; ?></b></nobr></td>
                            <td  class="contenttab_internal_rows" width="2%"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
                            <td  class="contenttab_internal_rows" width="2%">  
                                <select name="classId" id="classId" style="width:280px" class="selectfield" >
                                    <option value="">Select</option>
                                     <?php
                                       require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                       echo HtmlFunctions::getInstance()->getAllFeeClass();
                                    ?>
                                </select>
                            </td>
                            <td class="contenttab_internal_rows"  style="padding-left:15px">  
                             <input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return showFeeHeadValue();  return false;" />  
                            <td>
                        </tr>
              </table>
    </td>
</tr>                   
<div id="results" style="display:none" class="">      
            <tr id="results11" style="display:none">
                <td class="contenttab_border2" valign="top" >   
                   <!-- <div class="searchhead_text" align="left">Add Rows:&nbsp;&nbsp;
                        <a href="javascript:addOneRow(1);" title="Add One Row"><b>+</b></a>
                    </div>   --> 
                     <input type="hidden" name="editFeeHeadId" id="editFeeHeadId" value="" />                        
                     <table class="padding" width="100%" border="0"  id="anyid">
                            <tbody id="anyidBody">
                              <tr class="rowheading">
                                <td class="searchhead_text" width="3%"  align="left"><nobr><b>#</b></nobr></td>
                                <td class="searchhead_text" width="30%" align="left"><nobr><b>Fee Concession Category</b></nobr></td>
                                <td class="searchhead_text" width="30%" align="left"><nobr><b>Fee Head</b></nobr></td>
                                <td class="searchhead_text" width="10%" align="left"><nobr><b>Applicable To</b></nobr></td>
                                <td class="searchhead_text" width="21%" align="left"><nobr><b>Concession </b></nobr></td>                                
                                <td class="searchhead_text" width="6%" align="center"><nobr><b>Action</b></nobr></td>
                              </tr>
                            </tbody>
                        </div>
                     </table>       
                     <table width="100%" border="0">     
                        <tr>
                            <td class="padding" >        
                                <div class="searchhead_text1" align="left">Add Rows&nbsp;:&nbsp;&nbsp;
                                     <a href="javascript:addOneRow(1);" title="Add One Row"><b>+</b></a>
                                </div>    
                            </td>
                            <td class="" align="right" style='display:none'>        
                              <span class="contenttab_internal_rows"><B>Total Amount&nbsp;:&nbsp;&nbsp;</b><label width='100px' id='totAmount'>0</label>&nbsp;&nbsp;</span>
                            </td>
                         </tr>
                      </table>          
                 </td>
            </tr>
            <tr id="trAttendance" style="display:none">
                 <td  align="right" style="padding-right:5px" colspan="9">
                     <input type="image" name="imgSubmit" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onclick="return validateAddForm(this.form);return false;" />
                     &nbsp;<input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();" >
                     &nbsp;<input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printReportCSV();" >
                </td>
           </tr>
           </tr><td style="height:5px"></td></tr>
 </div>                  
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
