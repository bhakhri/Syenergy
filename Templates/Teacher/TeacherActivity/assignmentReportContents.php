<?php
//----------------------------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR class wise grade template
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (8.7.2008)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------
?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
           
            <tr>
                	 <?php require_once(TEMPLATES_PATH . "/breadCrumb.php");        ?> 
                <td valign="top" align="right"></td>
            </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
             <td valign="top" class="content">
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
            
             <tr>
             <td class="contenttab_border1" align="left" >
             <form action="" method="" name="searchForm"> 
                 <table border="0" cellspacing="0" cellpadding="0" >
                  <tr>
                        <td class="contenttab_internal_rows" align="left"><nobr><b>Time Table </b></nobr></td>
                        <td class="padding" align="left" style="padding-right:10px;"><nobr>:
                        <select size="1" name="timeTableLabelId" id="timeTableLabelId" class="selectfield" onChange="getClassData(this.value);">
                         <option value="">Select</option>
                         <?php
                          require_once(BL_PATH.'/HtmlFunctions.inc.php');
                          echo HtmlFunctions::getInstance()->getTimeTableLabelData('-1');
                         ?>
                        </select></nobr>
                        </td>
                  </tr>
                  <tr>      
                        <td class="contenttab_internal_rows" align="left"><nobr><b>Class </b></nobr></td>
                        <td class="padding" align="left" style="padding-right:10px;"><nobr>:
                        <select size="1" name="classId" id="classId" class="selectfield" onChange="getSubjectData(document.searchForm.timeTableLabelId.value,this.value);">
                         <option value="">Select</option>
                        </select></nobr>
                        </td>
                        <td class="contenttab_internal_rows" align="left"><nobr><b>Subject </b></nobr></td>
                        <td class="padding" align="left" style="padding-right:10px;"><nobr>:
                        <select size="1" name="subjectId" id="subjectId" class="selectfield" onChange="getGroupData(document.searchForm.timeTableLabelId.value,document.searchForm.classId.value,this.value);">
                         <option value="-1">All</option>
                        </select></nobr>
                        </td>
                        <td class="contenttab_internal_rows" align="left"><nobr><b>Group </b></nobr></td>
                        <td class="padding" align="left" style="padding-right:10px;"><nobr>:
                        <select size="1" name="groupId" id="groupId" class="selectfield" onChange="vanishData();">
                         <option value="-1">All</option>
                        </select></nobr>
                        </td>
                        <td class="padding">
                         <input type="image" name="imageField" onClick="getListData();return false" src="<?php echo IMG_HTTP_PATH;?>/show_list.gif" />
                        </td>
                  </tr>      
                 </table>
             </form>
             </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" >&nbsp;
                 <div id="resultsDiv">
                </div>
             </td>
          </tr>
          <tr><td height="5px"></td></tr>
          <tr id="printTRId" style="display:none">
           <td align="right">
            <input type="image" name="imageField" onClick="printReport();return false" src="<?php echo IMG_HTTP_PATH;?>/print.gif" />&nbsp;
            <input type="image" name="imageField" onClick="printCSV();return false" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" />
           </td>
         </tr>  
          </table>
        </td>
    </tr>
    
    </table>
    </td>
    </tr>
    </table>
<?php
// $History: listSubjectPerformanceContents.php $
?>