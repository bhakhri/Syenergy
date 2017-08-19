<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR CITY LISTING 
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<form name="listFrm" id="listFrm"  action="" method="post" onSubmit="return false;">  
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title"><?php require_once(TEMPLATES_PATH."/breadCrumb.php");?>
           
        </td>
    </tr>
    <tr>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
             <td valign="top" class="content">
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
             <tr>
                <td class="contenttab_border" height="20">
                
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
				        <td class="content_title">Change Mentor : </td>
                        <td class="content_title"></td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" >
                    <table border="0" cellpadding="5px" cellspacing="2px">
                     <tr><td height="10px"></td></tr>    
                     <tr>
                      <td class="contenttab_internal_rows" nowrap style="padding-left:5px"><b>Current Mentor</b></td>
                      <td class="contenttab_internal_rows" nowrap><b>:</b></td>
                      <td class="contenttab_internal_rows" nowrap>
                        <select name="currentMentorId" id="currentMentorId" style="width:325px;" class="selectfield" size="1" onchange="getClearList(); return false;">
                           <option value="" selected="selected">Select</option>
                        </select>
                      </td>
                      <td class="contenttab_internal_rows" nowrap style="padding-left:5px"><b>New Mentor</b></td>
                      <td class="contenttab_internal_rows" nowrap><b>:</b></td>
                      <td class="contenttab_internal_rows" nowrap>
                        <select name="newMentorId" id="newMentorId" style="width:325px;" class="selectfield" size="1" >
                           <option value="" selected="selected">Select</option>
                           <?php
                             require_once(BL_PATH.'/HtmlFunctions.inc.php');
                             echo HtmlFunctions::getInstance()->getEmployeeHighlightedNew('','1');
                           ?> 
                        </select>
                      </td>
                      <td class="contenttab_internal_rows" style="padding-left:25px">
                        <input align="right" type="image" name="submit" src="<?php echo IMG_HTTP_PATH;?>/update_mentor.gif" title="Update Mentor"   style="margin-bottom: -2px;" onClick="newMentorAlloted();return false;"/>
                      </td>
                     </tr> 
                     <tr><td height="10px"></td></tr>
                    </table>           
               </td>
             </tr>
             <tr style="display:none" id="showMentorList1">  
                <td class="contenttab_border" height="20">
                  <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                        <td class="content_title">  
                          Student Details:
                        </td>
                    </tr>
                    </table>
                </td>
             </tr>   
             <tr style="display:none" id="showMentorList2">  
               <td class="contenttab_row" valign="top" >
                 <div id="scroll2" style="overflow:auto; height:410px; vertical-align:top;">
                  <div id="resultsDiv" style="width:98%; vertical-align:top;"></div>
                 </div>
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
