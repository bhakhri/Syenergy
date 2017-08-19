<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR CITY LISTING 
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

require_once(BL_PATH.'/HtmlFunctions.inc.php');
$timeTableLabelDataString=HtmlFunctions::getInstance()->getTimeTableLabelData();
?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
            <tr>
                <td height="10"></td>
            </tr>
            <tr>
                <td valign="top">Time Table&nbsp;&raquo;&nbsp;Adjust Time Table</td>
                <td valign="top" align="right">
                 </td>
            </tr>
            </table>
        </td>
    </tr>
     <tr>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="750px">
            <tr>
             <td valign="top" class="content">
             <table width="100%" border="0" cellspacing="0" cellpadding="0" height="730px">
             <tr>
                <td class="contenttab_border" height="20">
                
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                        <td class="content_title">Adjust Time Table Detail : </td>
                        <td class="content_title"></td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top">
                 <!--Allocation Tab Starts-->
                  <div id="dhtmlgoodies_tabView1">
                  <div class="dhtmlgoodies_aTab">
                  <form name="searchForm" action="" method="post" onsubmit="return false;">
                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                     <tr>
                       <td class="contenttab_internal_rows" width="9%"><nobr><b>Substitution for Time Table</b></nobr></td>
                        <td class="padding" width="1%">:</td>
                        <td class="padding" colspan="9">
                        <select size="1" class="inputbox" name="labelId" id="labelId" onChange="getTeachersForThisTimeTable(this.value);">
                        <option value="">Select</option>
                        <?php
                          echo $timeTableLabelDataString;
                        ?>
                        </select></td>
                     
                    </tr>
                    <tr> 
                      <td class="contenttab_internal_rows"><b>Substitute</b></td>
                      <td class="padding">:</td>
                      <td class="padding"><nobr> 
                       <select name="replaceTeacherId" id="replaceTeacherId" class="inputbox" onchange="clearText(1);">
                        <option value="">Select</option>
                       </select>&nbsp;&nbsp;<span class="contenttab_internal_rows">teacher</span></nobr>
                      </td>
                      <td class="contenttab_internal_rows">&nbsp;</td>
                      <td class="contenttab_internal_rows">&nbsp;</td>
                      <td class="padding"><b>by</b></td>
                      <td class="padding">:</td>
                      <td class="padding"><nobr>
                       <select name="replacingTeacherId" id="replacingTeacherId" class="inputbox" onchange="clearText(2);">
                        <option value="">Select</option>
                       </select></nobr>
                      </td>
                      <td class="contenttab_internal_rows" colspan="3">
                       <b>teacher</b>
                      </td>
                     </tr>
                     <tr>
                      <td class="contenttab_internal_rows" width="7%"><b>Changes to take effect for the period between</td>
                     <td class="padding" width="1%">:</td>
                     <td class="padding"  width="15%"><nobr>
                      <?php
                        require_once(BL_PATH.'/HtmlFunctions.inc.php');
                        echo HtmlFunctions::getInstance()->datePicker('fromDate',date('Y-m-d')); 
                      ?>&nbsp;&nbsp;&nbsp;&nbsp;<span class="contenttab_internal_rows">and&nbsp;&nbsp;</span>:
                      <?php
                        require_once(BL_PATH.'/HtmlFunctions.inc.php');
                        echo HtmlFunctions::getInstance()->datePicker('toDate',date('Y-m-d')); 
                      ?></nobr>
                     </td>
                     <td class="padding" width="1%"></td>
                     <td class="padding" width="1%"></td>
                     <td class="contenttab_internal_rows" width="15%" colspan="5">
                       <input type="image" src="<?php echo IMG_HTTP_PATH;?>/showtimetable.gif" onclick="getTimeTableData();return false;">
                     </td>
                     </tr>
                     <tr><td colspan="12" height="5px"></td></tr>
                     <tr>
                      <td colspan="12">
                      <div id="placeHolderDiv" style="height:250px;overflow:auto">
                       <table border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                         <td valign="top" width="50%">
                          <div id="empDiv1"></div>
                         </td>
                        <td valign="top" width="50%">
                          <div id="empDiv2"></div>
                        </td>
                       </tr>
                       </table>
                       </div>
                      </td>
                     </tr> 
                     <tr>
                      <td colspan="12">
                       <div id="resultDiv" style="width:100%;height:280px;overflow:auto;display:none;"></div>
                      </td>
                     </tr>
                     <tr><td colspan="12" height="10px"></td></tr>
                     <tr id="saveTr1" style="display:none">
                      <td colspan="12" align="center">
                       <input type="image" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onclick="validateData();return false;">&nbsp;
                       <input type="image" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="clearData();return false;">
                      </td>
                     </tr>
                    </table>
                    </form>
                    </div>
                   <!--Allocation Tab Ends-->
                   <!--De-Allocation Tab Starts-->
                   <div class="dhtmlgoodies_aTab" style="overflow:auto">
                   <form name="searchForm2" action="" method="post" onsubmit="return false;">
                     <table border="0" cellpadding="0" cellspacing="0" width="100%">
                      <tr>
                       <td class="contenttab_internal_rows" width="6%"><nobr><b>Time Table</b></nobr></td>
                       <td class="padding" width="1%">:</td>
                       <td class="padding" width="10%" style="padding-right:15px">
                        <select size="1" class="inputbox" name="labelId2" id="labelId2" onChange="getAdjustedTeachersForThisTimeTable(this.value);">
                        <option value="">Select</option>
                        <?php
                          echo $timeTableLabelDataString;
                        ?>
                        </select>
                       </td>
                       <td class="contenttab_internal_rows" width="6%"><b>Teacher</b></td>
                       <td class="padding" width="1%">:</td>
                       <td class="padding" width="10%" style="padding-right:15px">
                        <select size="1" class="inputbox" name="teacherId" id="teacherId" onChange="cleanUpData(1);">
                        <option value="">Select</option>
                        </select>
                       </td>
                       <td class="contenttab_internal_rows"><b>From</b></td>
                       <td class="padding">:</td>
                       <td class="padding">
                        <?php
                            require_once(BL_PATH.'/HtmlFunctions.inc.php');
                            echo HtmlFunctions::getInstance()->datePicker('fromDate2',date('Y-m-d'));
                        ?>
                       </td>
                       <td class="contenttab_internal_rows"><b>To</b></td>
                       <td class="padding">:</td>
                       <td class="padding">
                        <?php
                            require_once(BL_PATH.'/HtmlFunctions.inc.php');
                            echo HtmlFunctions::getInstance()->datePicker('toDate2',date('Y-m-d'));
                        ?>
                       </td>
                       <td>
                        <input type="image" src="<?php echo IMG_HTTP_PATH;?>/show_adjustments.gif" style="margin-bottom: -5px;" onclick="getAdjustedTimeTableData();return false;">
                       </td>
                      </tr>
                      <tr>
                       <td colspan="13">
                        <div id="cancelResultDiv" style="width:99%;height:580px;overflow:auto;"></div>
                       </td>
                      </tr>
                      <tr>
                       <td id="cancelOptionTd" colspan="13" align="center" style="display:none" >
                        <input type="image" src="<?php echo IMG_HTTP_PATH;?>/delete_big.gif" onclick="validateData2();return false;">&nbsp;
                        <input type="image" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="cleanUpData(2);return false;">
                       </td>
                      </tr>
                     </table>
                   </form>  
                   </div>
                   <!--De-Allocation Tab Ends-->
                   </div>
                   </td>
                  </tr>
                 </table>   
                <script type="text/javascript">
                  initTabs('dhtmlgoodies_tabView1',Array('Adjust Time Table','Delete Adjustment'),0,985,650,Array(false,false));
                  //initTabs('dhtmlgoodies_tabView1',Array('Adjust Time Table'),0,985,650,Array(false,false));
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
    
<?php floatingDiv_Start('conflictsDivId','Conflicts','',' '); ?>
<table border="0" cellspacing="0" cellpadding="0" class="border">
<div id='conflictMessageDiv' style="width:800px;height:300px;overflow:auto;"></div>
</table>
<?php floatingDiv_End(); ?>
    
<?php
// $History: swapTimeTableContents.php $
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 11/11/09   Time: 10:16
//Updated in $/LeapCC/Templates/TimeTable
//Corrected logic of deleting adjustment time table entries 
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 5/11/09    Time: 17:14
//Updated in $/LeapCC/Templates/TimeTable
//Increased width of popup div as error messages get longer
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 3/11/09    Time: 11:20
//Updated in $/LeapCC/Templates/TimeTable
//Done bug fixing.
//Bug ids---
//00001931,00001930
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 22/10/09   Time: 17:57
//Updated in $/LeapCC/Templates/TimeTable
//Modified javascript and tab names
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 22/10/09   Time: 13:19
//Updated in $/LeapCC/Templates/TimeTable
//Added code "time table adjustment cancellation"
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 21/10/09   Time: 13:10
//Updated in $/LeapCC/Templates/TimeTable
//Checked in for time being
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 13/10/09   Time: 9:39
//Created in $/LeapCC/Templates/TimeTable
//Created "Swap time table" module
?>