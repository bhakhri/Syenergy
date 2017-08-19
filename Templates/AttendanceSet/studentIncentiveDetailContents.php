<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR student and message LISTING
//
//
// Author :Dipanjan Bhattacharjee
// Created on : (8.7.2008)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(BL_PATH.'/HtmlFunctions.inc.php');
$htmlFunctions = HtmlFunctions::getInstance();
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td valign="top" class="title">
 <?php require_once(TEMPLATES_PATH . "/breadCrumb.php");   ?>
  </td>
 </tr>
 <tr>
  <td valign="top">
   <table width="100%" border="0" cellspacing="0" cellpadding="0" height="450">
    <tr>
     <td valign="top" class="content">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
       <tr>
        <td class="contenttab_border" height="20">
         <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
          <tr>
           <td class="content_title">
            Attendance Incentive Details :
           </td>
           <td class="content_title" >
            &nbsp;
           </td>
          </tr>
         </table>
        </td>
       </tr>
       
       <tr>
        <td valign="top" class="contenttab_row" height="440">
         <table cellpadding="0" cellspacing="0" border="0" width="100%">
          <tr>
           <td>
            <div id="dhtmlgoodies_tabView1">
             <div class="dhtmlgoodies_aTab" style="overflow:auto" >
              <form name="incentiveMarksDetailsForm" action="" method="post" onSubmit="return false;">
               <!--Add Student Filter-->
               <nobr>
               <table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2" >
                <tr>
                 <td valign="top"  align="center">
                 	<input type="hidden" name="incentiveDetailId" id="incentiveDetailId" value="1" />  
                  <table border='0' width='100%' cellspacing='0' id="anyid">
                   <tbody id="anyidBody">
                              <tr class="rowheading">
                                <td class="searchhead_text" width="6%"  align="left"><nobr><b>#</b></nobr></td>
                                <td class="searchhead_text" width="30%" align="left"><nobr><b>Attendance % From</b></nobr></td>
<td class="searchhead_text" width="30%" align="left"><nobr><b>Attendance % To</b></nobr></td>
                                <td class="searchhead_text" width="30%" align="left"><nobr><b>Marks Weightage</b></nobr></td>
                                <td class="searchhead_text" width="6%" align="center"><nobr><b>Action</b></nobr></td>
                              </tr>
                            </tbody>
                   
                   </table>
                   <table width="100%" border="0">     
                        <tr>
                            <td class="padding" >        
                                <div class="searchhead_text1" align="left">Add Rows&nbsp;:&nbsp;&nbsp;
                                     <a href="javascript:addOneRow(1);" title="Add One Row"><b>+</b></a>
                                </div>    
                            </td>
                         </tr>
                      </table> 
                      <tr id="trAttendance" style="display:visible">
                 <td  align="right" style="padding-right:5px" colspan="9">
                    <input type="image" name="imgSubmit" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onclick="return validateMarksDescription(this.form);return false;" />&nbsp;
                    <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();" >&nbsp;
                    <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printCSV();" >&nbsp;
                </td>
           </tr>
                      
                  </td>
                 </tr>
                </table>
                </nobr>
                 <input type="hidden" name="selectedStudent" id="selectedStudent" value="" />
               </form>
                <br />
                
                </div>
                <!--Parent Info -->
                <div class="dhtmlgoodies_aTab" style="overflow:auto" >
              <form name="incentiveFeeDetailsForm" action="" method="post" onSubmit="return false;">
               <!--Add Parent Filter-->
               <table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2" >
                <tr>
                 <td valign="top"  align="center">
                 	<input type="hidden" name="incentiveDetailId" id="incentiveDetailId" value="2" />  
                   <table border='0' width='100%' cellspacing='0' id="anySingleid">
                   <tbody id="anySingleidBody">
                              <tr class="rowheading">
                                <td class="searchhead_text" width="6%"  align="left"><nobr><b>#</b></nobr></td>
                                 <td class="searchhead_text" width="30%" align="left"><nobr><b>Attendance % From</b></nobr></td>
<td class="searchhead_text" width="30%" align="left"><nobr><b>Attendance % To</b></nobr></td>
                                <td class="searchhead_text" width="30%" align="left"><nobr><b>Discount Amount</b></nobr></td>
                                <td class="searchhead_text" width="6%" align="center"><nobr><b>Action</b></nobr></td>
                              </tr>
                            </tbody>
                   
                   </table>
                   <table width="100%" border="0">     
                        <tr>
                            <td class="padding" >        
                                <div class="searchhead_text1" align="left">Add Rows&nbsp;:&nbsp;&nbsp;
                                     <a href="javascript:addSingleRow(1);" title="Add Single Row"><b>+</b></a>
                                </div>    
                            </td>
                         </tr>
                      </table> 
                      <tr id="trAttendance1" style="display:visible">
                 <td  align="right" style="padding-right:5px" colspan="9">
                    <input type="image" name="imgSubmit" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onclick="return validateFeeDescription(this.form);return false;" />&nbsp;
                    <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printFeeReport();" >&nbsp;
                    <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printFeeCSV();" >&nbsp;
                </td>
           </tr> 
                  </td>
                 </tr>
                </table>
               
               </form>
                <br />
               
                 </div>
                 
                 </div>
                 <script type="text/javascript">
                  initTabs('dhtmlgoodies_tabView1',Array('Marks Slab','Fee Slab'),0,970,400,Array(false,false));
                 </script>
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
    
